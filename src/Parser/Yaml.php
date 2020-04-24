<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml as Yml;

class Yaml implements ParserInterface
{
    public static function parseFile($file)
    {
        return Yml::parseFile($file);
    }
}
