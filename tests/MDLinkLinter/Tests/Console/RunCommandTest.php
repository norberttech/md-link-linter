<?php

declare(strict_types=1);

namespace MDLinkLinter\Tests\Console;

use MDLinkLinter\Console\Command\RunCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class RunCommandTest extends TestCase
{
    public function test_run_against_folder_with_relative_files() : void
    {
        $tester = new CommandTester(new RunCommand());

        $result = $tester->execute(['run', 'path' => __DIR__ . '/fixtures/with_relative']);

        $this->assertSame(0, $result);
    }
}
