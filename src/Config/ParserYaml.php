<?php

namespace App\Config;

use Symfony\Component\Yaml\Yaml;

class ParserYaml implements ParserInterface
{
    public static function parseFile($file)
    {
        return Yaml::parseFile($file);
    }
}
