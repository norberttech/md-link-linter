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

use Cocur\Slugify\Slugify;
use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Markdown\Link;
use Psr\Log\LoggerInterface;

final class AnchorLink implements Assertion
{
    private $slugify;

    private $link;

    private $markdownFile;

    public function __construct(Slugify $slugify, Link $link, \SplFileObject $markdownFile)
    {
        $this->link = $link;
        $this->markdownFile = $markdownFile;
        $this->slugify = $slugify;
    }

    public function assert(LoggerInterface $logger) : void
    {
        if ($this->link->path() === '#') {
            return;
        }

        $xpath = new \DOMXPath($this->link->document());
        $targetId = \ltrim($this->link->path(), '#');

        $target = $xpath->evaluate(\sprintf('//*[@id="%s"]', $targetId));

        if ($target->length) {
            return;
        }

        // This is mostly for github that is adding anchors to all headers
        for ($h = 1; $h <= 6; $h++) {
            $headers = $xpath->evaluate(\sprintf('//h%d', $h));

            for ($i = 0; $i < $headers->length; $i++) {
                $headerSlug = $this->slugify->slugify($headers->item($i)->textContent);

                if ($headerSlug === $targetId) {
                    return;
                }

                // Github does not removes "_" when generating slug
                if ($headerSlug === \str_replace('_', '', $targetId)) {
                    return;
                }

                // Github is changing ":" into "-" when generating slug
                if ($targetId === \str_replace('-', '', $headerSlug)) {
                    return;
                }
            }
        }

        throw new AssertionException();
    }
}
