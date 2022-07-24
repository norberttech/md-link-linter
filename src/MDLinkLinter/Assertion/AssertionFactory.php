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
use MDLinkLinter\Markdown\Link;

final class AssertionFactory
{
    /**
     * @var string[]
     */
    private $mentionWhitelist;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var Slugify
     */
    private $slugify;

    /**
     * @param string $rootPath
     * @param Slugify $slugify
     * @param string[] $mentionWhitelist
     */
    public function __construct(
        string $rootPath,
        Slugify $slugify,
        array $mentionWhitelist
    ) {
        $this->rootPath = $rootPath;
        $this->slugify = $slugify;
        $this->mentionWhitelist = $mentionWhitelist;
    }

    public function create(Link $link, \SplFileObject $markdownFile) : Assertion
    {
        if ($link->isAnchor()) {
            return new AnchorLink($this->slugify, $link);
        }

        if ($link->isMention()) {
            return new MentionLink($link, $this->mentionWhitelist);
        }

        if ($link->isRelative()) {
            return new RelativeLink($link, $markdownFile, $this->rootPath);
        }

        if ($link->isGitSSH()) {
            return new GitSSHLink($link);
        }

        return new UrlLink($link);
    }
}
