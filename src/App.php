<?php

namespace App;

use App\Config\Config;
use App\Command\Command;

/**
 * Main application
 */
class App
{
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

        $configFile = $this->makePathToConfig();

        // Get configuration by preset's name
        $config->setPreset($presetName);

        // Parse the configuration file
        $config->parse($configFile);

        // Run command
        $command = Command::dispatch($commandName);
    }

    /**
     * Return path to the configuration file
     *
     * @return string
     */
    private function makePathToConfig(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.json';
    }
}
