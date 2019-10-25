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

namespace MDLinkLinter\Markdown;

final class LinkIterator
{
    private $document;

    public function __construct(\DOMDocument $document)
    {
        $this->document = $document;
    }

    /**
     * @return Link[]
     */
    public function iterate() : \Generator
    {
        $xpath = new \DOMXPath($this->document);

        $hrefs = $xpath->evaluate("//a");

        for ($i = 0; $i < $hrefs->length; $i++) {
            yield new Link($hrefs->item($i)->textContent, $hrefs->item($i)->getAttribute('href'), $this->document);
        }
    }
}