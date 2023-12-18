<?php

namespace App;

use App\Parser;
use App\Parser\ParserInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Stores settings from configuration file
 */
class Config
{
    /** @var Config Instance of Config */
    protected static $instance;

    /** @var string Current preset */
    protected static $preset = '';

    /** @var string Config filepath */
    protected static $configFile = '';

    /** @var array Configuration options */
    protected static $config = [];

    /**
     * Disables constructor for singleton
     */
    private function __construct()
    {
    }

    /**
     * Return instance of Config
     *
     * @return Config
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Sets current preset
     *
     * @param string $presetName
     * @return Config
     */
    public function setPreset(string $presetName): static
    {
        static::$preset = $presetName;

        return static::$instance;
    }

    /**
     * Gets current preset
     *
     * @return string
     */
    public function getPreset(): string
    {
        return static::$preset;
    }

    /**
     * Sets config file
     *
     * @param string $configFile
     * @return Config
     */
    public function setConfig(string $configFile): static
    {
        static::$configFile = (new Filesystem())->isAbsolutePath($configFile)
            ? $configFile
            : dirname(__DIR__) . DIRECTORY_SEPARATOR . $configFile;

        return static::$instance;
    }

    /**
     * Parses configuration file
     *
     * @param string $file Absolute path to the configuration file
     * @return array Parameters from the configuration file
     */
    public function parse(string $file = null): array
    {
        $file = $file ?? static::$configFile;

        static::checkConfigFile($file);

        $type = static::getType($file);

        $parser = static::chooseParser($type);

        static::$config = $parser::parseFile($file);

        // static::checkSitePath();

        if (static::$preset) {
            return static::$instance->getConfigByPreset();
        }

        return static::$config;
    }

    /**
     * Returns configuration for the chosen preset
     *
     * @param string $preset
     * @return array
     */
    public function getConfigByPreset(string $preset = null)
    {
        $preset = $preset ?? static::$preset;

        $config = static::$config;

        if (!isset($config['presets'][$preset])) {
            throw new \Exception("Preset '{$preset}' not found.");
        }

        return $config['presets'][$preset];
    }

    /**
     * Check configuration file
     *
     * @param string $file
     * @throws \Exception
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
        $config = static::$instance->getConfigByPreset();

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
     * @throws \Exception
     * @return ParserInterface
     */
    protected static function chooseParser(string $type): ParserInterface
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
