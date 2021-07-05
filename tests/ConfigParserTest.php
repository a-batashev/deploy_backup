<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Config\Parser;

class ConfigParserTest extends TestCase
{
    private $instance;

    public function setUp(): void
    {
        $this->filename =

        $rawArgs = [
            'command1' => 1,
            'command2' => 2,
            'command3' => null,
            '--option-1' => 'test',
            '--option-2' => null,
            '<argument1>' => 'test',
            '<argument2>' => null,
        ];

        $this->instance = new ConfigParser($filename);
    }


}
