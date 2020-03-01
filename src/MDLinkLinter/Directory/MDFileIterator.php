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

namespace MDLinkLinter\Directory;

final class MDFileIterator
{
    private $path;
    private $excludes;

    public function __construct(string $path, array $excludes)
    {
        $this->path = $path;
        $this->excludes = $excludes;
    }

    public function iterate() : \Generator
    {
        $regex = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new DirectoryFilterIterator(
                    $this->excludes,
                    new \RecursiveDirectoryIterator($this->path)
                )
            ),
            '/^.+\.md$/i',
            \RecursiveRegexIterator::GET_MATCH
        );

        foreach ($regex as $path) {
            yield new \SplFileObject(\current($path));
        }
    }

    public function directory() : \DirectoryIterator
    {
        return new \DirectoryIterator($this->path);
    }
}
