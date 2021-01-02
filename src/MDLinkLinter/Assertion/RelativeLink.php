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
    private $link;

    private $markdownFile;

    private $rootDirectory;

    public function __construct(Link $link, \SplFileObject $markdownFile, \DirectoryIterator $rootDirectory)
    {
        $this->link = $link;
        $this->markdownFile = $markdownFile;
        $this->rootDirectory = $rootDirectory;
    }

    public function assert(LoggerInterface $logger) : void
    {
        $paths = [
            $this->composePath($this->rootDirectory->getPath(), $this->link->path()),
            $this->composePath($this->rootDirectory->getRealPath(), $this->link->path()),
            \realpath($this->composePath($this->rootDirectory->getPath(), $this->link->path()))
                ? \realpath($this->composePath($this->rootDirectory->getPath(), $this->link->path()))
                : 'invalid realpath',
            \realpath($this->composePath($this->rootDirectory->getRealPath(), $this->link->path()))
                ? \realpath($this->composePath($this->rootDirectory->getRealPath(), $this->link->path()))
                : 'invalid realpath',

            $this->composePath($this->markdownFile->getPathInfo()->getPath(), $this->link->path()),
            $this->composePath($this->markdownFile->getPathInfo()->getRealPath(), $this->link->path()),
            \realpath($this->composePath($this->markdownFile->getPathInfo()->getPath(), $this->link->path()))
                ? \realpath($this->composePath($this->markdownFile->getPathInfo()->getPath(), $this->link->path()))
                : 'invalid realpath',
            \realpath($this->composePath($this->markdownFile->getPathInfo()->getRealPath(), $this->link->path()))
                ? \realpath($this->composePath($this->markdownFile->getPathInfo()->getRealPath(), $this->link->path()))
                : 'invalid realpath',
        ];

        $logger->debug(\sprintf('Relative Link validation, paths to test: [%s]', \implode(', ', $paths)));

        foreach ($paths as $path) {
            if (!$path) {
                continue;
            }

            if (\file_exists($path)) {
                $logger->debug(\sprintf('Relative Link %s points to valid existing file: %s', $this->link->text(), $path));

                return;
            }
        }

        throw new AssertionException();
    }

    private function composePath(string ...$pathElement) : string
    {
        return DIRECTORY_SEPARATOR . \implode(
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
        return \ltrim($path, DIRECTORY_SEPARATOR);
    }
}
