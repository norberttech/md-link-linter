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

namespace MDLinkLinter\Tests\Directory;

use MDLinkLinter\Directory\MDFileIterator;
use PHPUnit\Framework\TestCase;

final class MDFileIteratorTest extends TestCase
{
    public function test_iterating_over_not_nested_directory() : void
    {
        $iterator = new MDFileIterator(__DIR__ . '/fixtures/not_nested', []);

        $mdFiles = \array_map(
            function (\SplFileObject $mdFile) {
                return $mdFile->getFilename();
            },
            \iterator_to_array($iterator->iterate())
        );

        $this->assertContains('file_with_valid_link.md', $mdFiles);
        $this->assertContains('LICENSE.md', $mdFiles);
        $this->assertContains('file_with_invalid_link.md', $mdFiles);
        $this->assertCount(4, $mdFiles);
    }

    public function test_iterating_over_nested_directory() : void
    {
        $iterator = new MDFileIterator(__DIR__ . '/fixtures/nested', []);

        $mdFiles = \array_map(
            function (\SplFileObject $mdFile) {
                return $mdFile->getFilename();
            },
            \iterator_to_array($iterator->iterate())
        );

        $this->assertContains('file_with_valid_link.md', $mdFiles);
        $this->assertContains('file_with_valid_link.md', $mdFiles);
        $this->assertCount(2, $mdFiles);
    }

    public function test_iterating_over_nested_directory_with_excluded_directory() : void
    {
        $iterator = new MDFileIterator(__DIR__ . '/fixtures/nested', ['one']);

        $mdFiles = \array_map(
            function (\SplFileObject $mdFile) {
                return $mdFile->getFilename();
            },
            \iterator_to_array($iterator->iterate())
        );

        $this->assertContains('file_with_valid_link.md', $mdFiles);
        $this->assertCount(1, $mdFiles);
    }
}
