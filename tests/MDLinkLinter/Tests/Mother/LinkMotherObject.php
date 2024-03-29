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

namespace MDLinkLinter\Tests\Mother;

use MDLinkLinter\Markdown\Link;

final class LinkMotherObject
{
    public static function mention(string $name) : Link
    {
        return new Link($name, '@' . $name, new \DOMDocument());
    }
}
