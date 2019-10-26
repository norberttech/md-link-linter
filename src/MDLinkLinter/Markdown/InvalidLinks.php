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

namespace MDLinkLinter\Markdown;

final class InvalidLinks implements \Countable
{
    private $invalidLinks;

    public function __construct()
    {
        $this->invalidLinks = [];
    }

    public function add(InvalidLink $link) : void
    {
        $this->invalidLinks[] = $link;
    }

    public function count() : int
    {
        return \count($this->invalidLinks);
    }

    public function map(callable $callback) : array
    {
        return \array_map($callback, $this->invalidLinks);
    }

    public function filesCount() : int
    {
        return \count(\array_unique($this->map(
            function (InvalidLink $invalidLink) {
                return $invalidLink->markdownFile()->getPathname();
            }
        )));
    }
}
