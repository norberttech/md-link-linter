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

namespace MDLinkLinter\Assertion;

use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Markdown\Link;

final class MentionLink implements Assertion
{
    private $link;
    private $whitelist;

    public function __construct(Link $link, array $whitelist)
    {
        $this->link = $link;
        $this->whitelist = \array_map(
            function (string $mention) {
                return \mb_strtolower($mention);
            },
            $whitelist
        );
    }

    public function assert() : void
    {
        if (!\count($this->whitelist)) {
            return ;
        }

        if (!\in_array(\ltrim(\mb_strtolower($this->link->path()), '@'), $this->whitelist, true)) {
            throw new AssertionException();
        }
    }
}
