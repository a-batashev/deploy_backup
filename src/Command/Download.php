<?php

namespace App\Command;

use App\Downloader;
use App\Downloader\DownloaderInterface;

/**
 * Get files from remote/local repository
 */
class Download extends Command
{
    /**
     * Run command
     *
     * @return void
     */
    public static function run()
    {
        $transportType = self::$config['transport']['type'];

        $downloader = self::chooseDownloader($transportType);

        $downloader->get();

        if (!self::$args->isQuiet()) {
            echo 'Download complete', PHP_EOL;
        }
    }

    /**
     * Downloader dispatching
     *
     * @param string $transport
     * @throws \Exception
     * @return DownloaderInterface
     */
    protected static function chooseDownloader(string $transport): DownloaderInterface
    {
        $downloaderClass = Downloader::class . '\\' . ucfirst($transport);

        if (class_exists($downloaderClass)) {
            return new $downloaderClass();
        }

        throw new \Exception("Transport '{$transport}' not found.");
    }
}
