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

final class InvalidLink
{
    private $link;
    private $markdownFile;

    public function __construct(Link $link, \SplFileObject $markdownFile)
    {
        $this->link = $link;
        $this->markdownFile = $markdownFile;
    }

    public function link(): Link
    {
        return $this->link;
    }

    public function markdownFile(): \SplFileObject
    {
        return $this->markdownFile;
    }
}
