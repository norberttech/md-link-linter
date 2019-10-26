<?php

declare(strict_types=1);

/*
 * This file is part of the Markdown Link Linter library.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RunCommand extends Command
{
    protected static $defaultName = 'run';

    protected function configure()
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'Path in which md link linter should validate all markdown files');
        $this->addOption('exclude', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Exclude folders with this name');
        $this->addOption('mention', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Mentions whitelist (can include all team members or groups), if empty mentions are not validated');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $input->getArgument('path');
        $excludes = $input->getOption('exclude');
        $mentionWhitelist = $input->getOption('mention');

        if (!\file_exists($path) || !\is_dir($path)) {
            $io->error(sprintf('Path "%s" does not exists or it\'s not a directory.', $path));

            return 1;
        }

        $iterator = new MDFileIterator($path, $excludes);
        $htmlConverter = new HtmlConverter();
        $assertionFactory = new AssertionFactory($iterator->directory(), new Slugify(), $mentionWhitelist);
        $invalidLinks = new InvalidLinks();


        foreach ($iterator->iterate() as $markdownFile) {
            $io->write('.');

            $linkIterator = new LinkIterator($htmlConverter->convert($markdownFile));

            foreach ($linkIterator->iterate() as $link) {
                try {
                    $assertionFactory->create($link, $markdownFile)->assert();
                } catch (AssertionException $assertionException) {
                    $invalidLinks->add(new InvalidLink($link, $markdownFile));
                }
            }
        }

        $io->note('Scan finished');


        if ($invalidLinks->count()) {
            $io->error('Invalid links found');

            $io->table(
                ['Broken markdown file', '(text)', '[link]'],
                $invalidLinks->map(function (InvalidLink $invalidLink) {
                    return [
                            $invalidLink->markdownFile()->getPathname(),
                            '(' . $invalidLink->link()->text() . ')',
                            '[' . $invalidLink->link()->path() . ']',
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
