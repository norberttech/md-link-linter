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

namespace MDLinkLinter\Assertion;

use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Markdown\Link;
use Psr\Log\LoggerInterface;

final class RelativeLink implements Assertion
{
    /**
     * @var Link
     */
    private $link;

    /**
     * @var \SplFileObject
     */
    private $markdownFile;

    /**
     * @var string
     */
    private $rootPath;

    public function __construct(Link $link, \SplFileObject $markdownFile, string $rootPath)
    {
        $this->link = $link;
        $this->markdownFile = $markdownFile;
        $this->rootPath = $rootPath;
    }

    public function assert(LoggerInterface $logger) : void
    {
        $paths = [
            $this->link->path(),
            $this->composePath($this->rootPath, $this->link->path()),
            $this->composePath($this->markdownFile->getPathInfo()->getPathname(), $this->link->path()),
            $this->composePath($this->rootPath, $this->markdownFile->getPathInfo()->getPathname(), $this->link->path()),
        ];

        $logger->debug(\sprintf('Relative Link validation, paths to test: [%s]', \implode(', ', $paths)));

        foreach ($paths as $path) {
            if (!$path) {
                continue;
            }

            // GitHub allows to link to specific lines in files
            $path = \preg_replace('/(#L\d+?)$/', '', $path);

            if (\file_exists($path)) {
                $logger->debug(\sprintf('Relative Link %s points to valid existing file: %s', $this->link->text(), $path));

                return;
            }
        }

        throw new AssertionException();
    }

    private function composePath(string ...$pathElement) : string
    {
        return \implode(
            DIRECTORY_SEPARATOR,
            \array_map(
                function (string $path) {
                    return $this->trimPath($path);
                },
                $pathElement
            )
        );
    }

    private function trimPath(string $path) : string
    {
        return \rtrim($path, DIRECTORY_SEPARATOR);
    }
}
