<?php

namespace App\Parser;

class Json implements ParserInterface
{
    public static function parseFile($file)
    {
        $json = file_get_contents($file);

        return json_decode($json, true);
    }
}
