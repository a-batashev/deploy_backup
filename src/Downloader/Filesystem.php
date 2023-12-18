<?php

namespace App\Downloader;

use App\Config;
use App\ConsoleInput;
use Symfony\Component\Filesystem\Filesystem as FS;

/**
 * Filesystem
 */
class Filesystem implements DownloaderInterface
{
    /**
     * Copy the files from the backup to the site path
     */
    public static function get(): void
    {
        $options = ConsoleInput::getInstance()->getOptions();
        $cfg = Config::getInstance()->getConfigByPreset();

        $from = $cfg['transport']['options']['from'];
        $to = $cfg['sitePath'];

        if (!file_exists($from)) {
            throw new \Exception("Directory isn't exist: '{$from}'.");
        }

        if (!$options['quiet']) {
            echo "Copy from '{$from}' to '{$to}'", PHP_EOL;
        }

        if (!$options['dry-run']) {
            $filesystem = new FS();
            $filesystem->mirror($from, $to);
        }
    }
}
