<?php

namespace App;

use App\Environment\DotEnv;
use App\Environment\Php;

/**
 * Works with environment files and their parsers
 */
class Environment
{
    protected $envPath = '';

    public function __construct($envParserType = 'php')
    {
        $config = Config::getInstance()->getConfigByPreset();

        $this->envPath = $config['envPath'] ?? '';

        $this->checkEnvPath();

        $envParser = $this->chooseEnvParser($envParserType);

        $envParser->parseFile($this->envPath);
    }

    /**
     * Check environment path at configuration file
     *
     * @return void
     */
    protected function checkEnvPath()
    {
        if ($this->envPath === '') {
            throw new \Exception("Configuration option 'envPath' is empty.");
        }

        if (!file_exists($this->envPath)) {
            throw new \Exception("Incorrect path to the environment file: '{$this->envPath}'.");
        }
    }

    protected function chooseEnvParser(string $type = 'php')
    {
        switch ($type) {
            case 'php':
                return new Php();
                break;
            case 'dotenv':
                return new DotEnv();
                break;

            default:
                throw new \Exception("Environment parser for '{$type}' not found.");
                break;
        }
    }
}
