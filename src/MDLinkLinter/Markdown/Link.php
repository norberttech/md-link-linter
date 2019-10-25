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

final class Link
{
    private $text;
    private $path;
    private $document;

    public function __construct(string $text, string $path, \DOMDocument $document)
    {
        $this->text = $text;
        $this->path = $path;
        $this->document = $document;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function document(): \DOMDocument
    {
        return $this->document;
    }

    public function isRelative() : bool
    {
        return !filter_var($this->path, FILTER_VALIDATE_URL) && !$this->isAnchor() && !$this->isMention();
    }

    public function isUrl() : bool
    {
        return (bool) filter_var($this->path, FILTER_VALIDATE_URL);
    }

    public function isAnchor() : bool
    {
        return \strpos($this->path, '#', 0) === 0;
    }

    public function isMention() : bool
    {
        return \strpos($this->path, '@', 0) === 0;
    }
}