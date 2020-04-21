<?php

namespace App\Command;

use App\ArgumentsProcessor;
use App\Config\Config;

/**
 * Parent class for commands
 */
class Command
{
    /**
     * Instance of Config
     *
     * @var Config
     */
    protected static $config;

    /**
     * Instance of ArgumentsProcessor
     *
     * @var ArgumentsProcessor
     */
    protected static $args;

    /**
     * Constructor
     */
    protected function __construct()
    {
        self::$args = ArgumentsProcessor::getInstance();

        self::$config = Config::getInstance()->getConfigByPreset();
    }

    /**
     * Commands dispatching
     *
     * @param string $command
     */
    public static function dispatch($command)
    {
        $commands = [
            'clean',
            'download',
            'configure',
            'deploy',
        ];

        if (!in_array($command, $commands)) {
            throw new \Exception("Unknown command: '{$command}'.");
        }

        $className = __NAMESPACE__ . '\\' . ucfirst($command);

        $command = new $className();

        $command->run();
    }

    /**
     * Check sitepath at configuration file
     *
     * @return string
     */
    protected static function checkSitePath(array $config)
    {
        if (!isset($config['sitePath'])) {
            throw new \Exception("Configuration option 'sitePath' is empty.");
        }

        if (!file_exists($config['sitePath'])) {
            throw new \Exception("Incorrect path to site directory: '{$config['sitePath']}'.");
        }

        return $config['sitePath'];
    }
}
