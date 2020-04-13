<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\ArgumentsProcessor;

class ArgumentsProcessorTest extends TestCase
{
    private $instance;

    public function setUp(): void
    {
        $rawArgs = [
            'command1' => 1,
            'command2' => 2,
            'command3' => null,
            '--option-1' => 'test',
            '--option-2' => null,
            '<argument1>' => 'test',
            '<argument2>' => null,
        ];

        $this->instance = ArgumentsProcessor::getInstance()->process($rawArgs);
    }

    public function testGetArgumentsArray()
    {
        $actual = $this->instance->getArguments();
        $expected = ['argument1' => 'test'];
        $this->assertSame($expected, $actual);
    }

    public function testGetArgumentValue()
    {
        $actual = $this->instance->getArgument('argument1');
        $expected = 'test';
        $this->assertSame($expected, $actual);
    }

    public function testGetOptionsArray()
    {
        $actual = $this->instance->getOptions();
        $expected = ['option-1' => 'test'];
        $this->assertSame($expected, $actual);
    }

    public function testGetOptionValue()
    {
        $actual = $this->instance->getOption('option-1');
        $expected = 'test';
        $this->assertSame($expected, $actual);
    }

    public function testGetCommandsArray()
    {
        $actual = $this->instance->getCommands();
        $expected = ['command1', 'command2'];
        $this->assertSame($expected, $actual);
    }

    public function testGetCommandValue()
    {
        $actual = $this->instance->getCommand();
        $expected = 'command1';
        $this->assertSame($expected, $actual);

        $actual2 = $this->instance->getCommand(1);
        $expected2 = 'command2';
        $this->assertSame($expected2, $actual2);
    }
}
