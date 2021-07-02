<?php

namespace App\Command;

use App\Downloader;

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
     * @return Downloader\DownloaderInterface
     */
    protected static function chooseDownloader(string $transport): Downloader\DownloaderInterface
    {
        switch ($transport) {
            case 'filesystem':
                return new Downloader\Filesystem();
                break;

            case 'restic':
                return new Downloader\Restic();
                break;

            default:
                throw new \Exception("Transport '{$transport}' not found.");
                break;
        }
    }
}
