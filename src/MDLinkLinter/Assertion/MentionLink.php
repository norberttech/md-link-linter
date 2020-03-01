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

    public function assert(LoggerInterface $logger) : void
    {
        if (!\count($this->whitelist)) {
            $logger->debug('Skipping Mention Link validation, whitelist is empty.');

            return ;
        }

        if (!\in_array(\ltrim(\mb_strtolower($this->link->path()), '@'), $this->whitelist, true)) {
            throw new AssertionException();
        }

        $logger->debug(\sprintf('Mentions %s available at whitelist [%s]', $this->link->path(), \implode(', ', $this->whitelist)));
    }
}
