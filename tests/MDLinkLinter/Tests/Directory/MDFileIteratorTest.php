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

namespace MDLinkLinter\Tests\Directory;

use MDLinkLinter\Directory\MDFileIterator;
use PHPUnit\Framework\TestCase;

final class MDFileIteratorTest extends TestCase
{
    public function test_iterating_over_not_nested_directory()
    {
        $iterator = new MDFileIterator(__DIR__ . "/fixtures/not_nested", []);

        $mdFiles = \iterator_to_array($iterator->iterate());

        $this->assertSame('file_with_valid_link.md', $mdFiles[0]->getFilename());
        $this->assertSame('LICENSE.md', $mdFiles[1]->getFilename());
        $this->assertSame('file_with_invalid_link.md', $mdFiles[2]->getFilename());
        $this->assertCount(3, $mdFiles);
    }

    public function test_iterating_over_nested_directory()
    {
        $iterator = new MDFileIterator(__DIR__ . "/fixtures/nested", []);

        $mdFiles = \iterator_to_array($iterator->iterate());

        $this->assertSame('file_with_valid_link.md', $mdFiles[0]->getFilename());
        $this->assertSame('file_with_valid_link.md', $mdFiles[1]->getFilename());
        $this->assertCount(2, $mdFiles);
    }

    public function test_iterating_over_nested_directory_with_excluded_directory()
    {
        $iterator = new MDFileIterator(__DIR__ . "/fixtures/nested", ['one']);

        $mdFiles = \iterator_to_array($iterator->iterate());

        $this->assertSame('file_with_valid_link.md', $mdFiles[0]->getFilename());
        $this->assertCount(1, $mdFiles);
    }
}