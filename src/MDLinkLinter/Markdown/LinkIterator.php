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

namespace MDLinkLinter\Markdown;

final class LinkIterator
{
    private $document;

    public function __construct(\DOMDocument $document)
    {
        $this->document = $document;
    }

    public function iterate() : \Generator
    {
        $xpath = new \DOMXPath($this->document);

        $hrefs = $xpath->evaluate('//a');
        $imgs = $xpath->evaluate('//img');

        for ($i = 0; $i < $hrefs->length; $i++) {
            yield new Link($hrefs->item($i)->textContent, $hrefs->item($i)->getAttribute('href'), $this->document);
        }

        for ($i = 0; $i < $imgs->length; $i++) {
            yield new Link($imgs->item($i)->textContent, $imgs->item($i)->getAttribute('src'), $this->document);
        }
    }
}
