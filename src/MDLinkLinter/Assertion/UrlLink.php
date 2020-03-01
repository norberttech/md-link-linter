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

final class UrlLink implements Assertion
{
    private $link;

    public function __construct(Link $link)
    {
        $this->link = $link;
    }

    public function assert(LoggerInterface $logger) : void
    {
        $logger->debug('Skipping URL Link validation ' . $this->link->path());
    }
}
