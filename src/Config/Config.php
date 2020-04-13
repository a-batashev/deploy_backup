<?php

namespace App\Config;

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
    private static $config = [];

    /**
     * Instance of Config
     *
     * @var Config
     */
    private static $instance;

    /**
     * Current preset
     *
     * @var string
     */
    private static $preset;

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

        $parser = new Parser($file);

        self::$config = $parser->run();

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
    private function checkConfigFile(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception("Configuration file doesn't exist: '{$file}'.");
        }

        if (!is_readable($file)) {
            throw new \Exception("Configuration file isn't readable: '{$file}'.");
        }
    }
}
