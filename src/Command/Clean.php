<?php

namespace App\Command;

use App\Config\Config;

class Clean extends Command
{
    public function __construct()
    {
        $config = Config::getInstance()->getConfigByPreset();

        print_r($config);

        if (!isset($config['sitePath'])) {
            throw new \Exception("Configuration option 'sitePath' is empty.");
        }

        $dir = $config['sitePath'];

        if (!file_exists($dir)) {
            throw new \Exception("Incorrect path to site directory: '{$dir}'.");
        }

        echo 'Clean', "\n";
    }
}
