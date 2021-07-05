<?php

namespace App;

use App\Command\Command;

/**
 * Main application
 */
class App
{
    /**
     * Default name of the configuration file
     */
    protected const CONFIG_FILENAME = 'config.yaml';

    /**
     * Construct
     *
     * @param array $rawArgs
     */
    public function __construct(array $rawArgs)
    {
        ArgumentsProcessor::getInstance()->process($rawArgs);
    }

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        // Parse CLI arguments
        $args = ArgumentsProcessor::getInstance();

        $presetName = $args->getArgument('preset');
        $commandName = $args->getCommand();

        // Get configuration from file
        $config = Config::getInstance();

        // Get configuration by preset's name
        $config->setPreset($presetName);

        // Parse the configuration file
        $configFilename = $args->getOption('config');

        $configFile = $this->makePathToConfig($configFilename);

        $config->parse($configFile);

        // Run command
        $command = Command::dispatch($commandName);
    }

    /**
     * Return path to the configuration file
     *
     * @return string
     */
    protected function makePathToConfig($configFilename = null): string
    {
        $basedir = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $filename = $configFilename ?? self::CONFIG_FILENAME;

        $fs = new \Symfony\Component\Filesystem\Filesystem();

        if ($fs->isAbsolutePath($filename)) {
            return $filename;
        }

        return "{$basedir}{$filename}";
    }
}
