<?php

namespace App\Downloader;

use App\ArgumentsProcessor;
use App\Config;

/**
 * Restic
 */
class Restic implements DownloaderInterface
{
    /**
     * Get files from Restic's repository
     *
     * @return void
     */
    public static function get()
    {
        $args = ArgumentsProcessor::getInstance();

        $config = Config::getInstance()->getConfigByPreset();

        $sitePath = $config['sitePath'];

        $tag = $args->getArgument('preset');

        $options = $config['transport']['options'];

        $cmd = 'restic restore';
        foreach ($options as $key => $value) {
            $cmd .= " --{$key}";

            if (strlen($value)) {
                $cmd .= '=' . escapeshellarg($value);
            }
        }

        if ($args->isDryRun()) {
            echo $cmd, PHP_EOL;
        } else {
            exec($cmd, $output, $return);

            if ($return) {
                throw new \Exception("Can't get files.");
            }
        }
    }
}
