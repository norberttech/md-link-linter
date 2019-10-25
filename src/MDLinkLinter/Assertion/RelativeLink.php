<?php

declare(strict_types=1);

/*
 * This file is part of the Hire in Social project.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MDLinkLinter\Assertion;

use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Markdown\Link;

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

    public function assert() : void
    {
        if (\file_exists($this->rootDirectory->getRealPath() . DIRECTORY_SEPARATOR . ltrim($this->link->path(), DIRECTORY_SEPARATOR))) {
            return ;
        }

        if (\file_exists($this->markdownFile->getPathInfo()->getRealPath() . DIRECTORY_SEPARATOR . ltrim($this->link->path(), DIRECTORY_SEPARATOR))) {
            return ;
        }

        throw new AssertionException();
    }
}