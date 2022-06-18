<?php

namespace App\Downloader;

use App\ArgumentsProcessor;
use App\Config;
use Symfony\Component\Filesystem\Filesystem as FS;

/**
 * Filesystem
 */
class Filesystem implements DownloaderInterface
{
    /**
     * Copy files from backup to sitepath
     *
     * @return void
     */
    public static function get()
    {
        $args = ArgumentsProcessor::getInstance();

        $config = Config::getInstance()->getConfigByPreset();

        $sitePath = $config['sitePath'];
        $from = $config['transport']['from'];

        if (!file_exists($from)) {
            throw new \Exception("Directory isn't exist: '{$from}'.");
        }

        if (!$args->isQuiet()) {
            echo "Copy from '{$from}' to '{$sitePath}'", PHP_EOL;
        }

        if (!$args->isDryRun()) {
            $filesystem = new FS();
            $filesystem->mirror($from, $sitePath);
        }
    }
}
