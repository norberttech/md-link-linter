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

namespace MDLinkLinter\Tests\Assertion;

use MDLinkLinter\Assertion\MentionLink;
use MDLinkLinter\Exception\AssertionException;
use MDLinkLinter\Tests\Mother\LinkMotherObject;
use PHPUnit\Framework\TestCase;

final class MentionLinkTest extends TestCase
{
    public function test_do_nothing_when_whitelist_is_empty()
    {
        $assertion = new MentionLink(LinkMotherObject::mention('norzechowicz'), []);

        $this->assertNull($assertion->assert());
    }

    public function test_assertion_when_mention_not_on_whitelist()
    {
        $this->expectException(AssertionException::class);

        (new MentionLink(LinkMotherObject::mention('test'), ['norzechowicz']))->assert();
    }

    public function test_assertion_not_case_sensitive()
    {
        $assertion = new MentionLink(LinkMotherObject::mention('NorzechowiCZ'), ['Norzechowicz']);

        $this->assertNull($assertion->assert());
    }
}