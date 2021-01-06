<?php

declare(strict_types=1);

/*
 * This file is part of the Markdown Link Linter library.
 *
 * (c) Norbert Orzechowicz <contact@norbert.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MDLinkLinter\Console\Command;

use Cocur\Slugify\Slugify;
use MDLinkLinter\Assertion\AssertionFactory;
use MDLinkLinter\Directory\MDFileIterator;
use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Markdown\HtmlConverter;
use MDLinkLinter\Markdown\InvalidLink;
use MDLinkLinter\Markdown\InvalidLinks;
use MDLinkLinter\Markdown\Link;
use MDLinkLinter\Markdown\LinkIterator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends Command
{
    protected static $defaultName = 'run';

    /**
     * @var null|LoggerInterface
     */
    private $logger;

    protected function configure() : void
    {
        $this->addArgument('path', InputArgument::OPTIONAL, 'Path in which md link linter should validate all markdown files');
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Scan path and output md files');
        $this->addOption('exclude', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Exclude folders with this name');
        $this->addOption('mention', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Mentions whitelist (can include all team members or groups), if empty mentions are not validated');
        $this->addOption('break-on-failure', 'bf', InputOption::VALUE_NONE, 'Break the inspection on first failure');
    }

    protected function initialize(InputInterface $input, OutputInterface $output) : void
    {
        $this->logger = new ConsoleLogger($output);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new LinterStyle($input, $output);

        $path = \getenv('MD_LINTER_SCAN_DIR') ? \getenv('MD_LINTER_SCAN_DIR') : $input->getArgument('path');
        $excludes = $input->getOption('exclude');
        $mentionWhitelist = $input->getOption('mention');

        if (!$path) {
            $io->error('Missing path to directory, please provide it as command first argument or env var MD_LINTER_SCAN_DIR');

            return 1;
        }

        if (!\file_exists($path) || !\is_dir($path)) {
            $io->error(\sprintf('Path "%s" does not exists or it\'s not a directory.', $path));

            return 1;
        }

        if ($input->getOption('dry-run')) {
            $io->note('Dry run');
        }

        $iterator = new MDFileIterator($path, $excludes);
        $htmlConverter = new HtmlConverter();
        $assertionFactory = new AssertionFactory($iterator->directory(), new Slugify(), $mentionWhitelist);
        $invalidLinks = new InvalidLinks();

        $io->note(\sprintf('Scanning directory: %s', $iterator->directory()->getPath()));

        $scannedFiles = 0;

        /** @var \SplFileObject $markdownFile */
        foreach ($iterator->iterate() as $markdownFile) {
            $scannedFiles++;

            $this->logger->info(\sprintf('Checking markdown file: %s', $markdownFile->getRealPath()));

            $fileAssertionFailed = false;

            $linkIterator = new LinkIterator($htmlConverter->convert($markdownFile));

            foreach ($linkIterator->iterate() as $link) {
                try {
                    /** @var Link $link */
                    $this->logger->debug(\sprintf('Checking link: [%s](%s)', $link->text(), $link->path()));

                    if (!$input->getOption('dry-run')) {
                        $assertionFactory->create($link, $markdownFile)->assert($this->logger);
                    }
                } catch (AssertionException $assertionException) {
                    $invalidLinks->add(new InvalidLink($link, $markdownFile));
                    $fileAssertionFailed = true;
                }
            }

            if ($fileAssertionFailed) {
                $io->write('<fg=red>F</>');
            } else {
                $io->write('.');
            }

            if ($input->getOption('break-on-failure')) {
                if ($invalidLinks->count()) {
                    break;
                }
            }

            if ($scannedFiles >= 120) {
                $io->newLine();
                $scannedFiles = 0;
            }
        }

        $io->note('Scan finished');

        if ($input->getOption('dry-run')) {
            return 0;
        }

        if ($invalidLinks->count()) {
            $io->error('Invalid links found');

            $io->table(
                ['Broken markdown file', '(text)', '[link]'],
                $invalidLinks->map(function (InvalidLink $invalidLink) {
                    return [
                        $invalidLink->markdownFile()->getPathname(),
                        '[' . $invalidLink->link()->text() . ']',
                        '(' . $invalidLink->link()->path() . ')',
                    ];
                })
            );

            $io->note(\sprintf('Total files: %d', $invalidLinks->filesCount()));
            $io->note(\sprintf('Total invalid links: %d', $invalidLinks->count()));

            return 1;
        }

        $io->success('All links in markdown files are valid!');

        return 0;
    }
}
