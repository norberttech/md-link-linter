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

use Cocur\Slugify\Slugify;
use MDLinkLinter\Markdown\Link;

final class AssertionFactory
{
    private $rootDirectory;
    private $slugify;
    private $mentionWhitelist;

    public function __construct(
        \DirectoryIterator $rootDirectory,
        Slugify $slugify,
        array $mentionWhitelist
    ) {
        $this->rootDirectory = $rootDirectory;
        $this->slugify = $slugify;
        $this->mentionWhitelist = $mentionWhitelist;
    }

    public function create(Link $link, \SplFileObject $markdownFile) : Assertion
    {
        if ($link->isAnchor()) {
            return new AnchorLink($this->slugify, $link, $markdownFile);
        }

        if ($link->isMention()) {
            return new MentionLink($link, $this->mentionWhitelist);
        }

        if ($link->isRelative()) {
            return new RelativeLink($link, $markdownFile, $this->rootDirectory);
        }

        return new UrlLink($link);
    }
}