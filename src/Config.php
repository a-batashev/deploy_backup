<?php

namespace App;

use App\Parser;

/**
 * Stores settings from configuration file
 */
class Config
{
    /**
     * Configuration options
     *
     * @var array
     */
    protected static $config = [];

    /**
     * Instance of Config
     *
     * @var Config
     */
    protected static $instance;

    /**
     * Current preset
     *
     * @var string
     */
    protected static $preset;

    /**
     * Construct is hidden for singleton
     */
    private function __construct()
    {
    }

    /**
     * Disables cloning for singleton
     */
    private function __clone()
    {
    }

    /**
     * Disables unserialize for singleton
     */
    private function __wakeup()
    {
    }

    /**
     * Return instance of Config
     *
     * @return Config
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Sets current preset
     *
     * @param string $presetName
     * @return void
     */
    public function setPreset(string $presetName)
    {
        self::$preset = $presetName;
    }

    /**
     * Parses configuration file
     *
     * @param string $file
     * @return void
     */
    public function parse(string $file)
    {
        self::checkConfigFile($file);

        $type = self::getType($file);

        $parser = self::chooseParser($type);

        self::$config = $parser::parseFile($file);

        self::checkSitePath();

        return self::$config;
    }

    /**
     * Returns configuration for the chosen preset
     *
     * @param string $preset
     * @return array
     */
    public function getConfigByPreset(string $preset = null)
    {
        $preset = $preset ?? self::$preset;

        $config = self::$config;

        if (!isset($config['presets'][$preset])) {
            throw new \Exception("Preset '{$preset}' not found.");
        }

        return $config['presets'][$preset];
    }

    /**
     * Check configuration file
     *
     * @param string $file
     * @return void
     */
    protected static function checkConfigFile(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception("Configuration file doesn't exist: '{$file}'.");
        }

        if (!is_readable($file)) {
            throw new \Exception("Configuration file isn't readable: '{$file}'.");
        }
    }

    /**
     * Check sitepath at configuration file
     *
     * @return void
     */
    protected static function checkSitePath()
    {
        $config = self::$instance->getConfigByPreset();

        if (!isset($config['sitePath'])) {
            throw new \Exception("Configuration option 'sitePath' is empty.");
        }

        if (!file_exists($config['sitePath'])) {
            throw new \Exception("Incorrect path to the site directory: '{$config['sitePath']}'.");
        }
    }

    /**
     * Parser dispatching
     *
     * @param string $type
     * @return void
     */
    protected static function chooseParser(string $type): Parser\ParserInterface
    {
        switch ($type) {
            case 'yaml':
            case 'json':
                return new Parser\Yaml();
                break;

            default:
                throw new \Exception("Parser of '{$type}' not found.");
                break;
        }
    }

    /**
     * Get extension of file
     *
     * @param string $file
     * @return string
     */
    protected static function getType(string $file): string
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        return strtolower($extension);
    }
}
