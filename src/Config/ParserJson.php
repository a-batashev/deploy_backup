<?php

namespace App\Config;

class ParserJson implements ParserInterface
{
    public static function parseFile($file)
    {
        $json = file_get_contents($file);

        return json_decode($json, true);
    }
}
