<?php

namespace App\Environment;

class Php
{
    /**
     * Load environment from a PHP file
     *
     * @param string $envFile
     * @return void
     */
    public function parseFile($envFile)
    {
        if (!is_file($envFile) || !is_readable($envFile)) {
            throw new \Exception("Can't read environment from file: '{$envFile}'.");
        }

        $_ENV = require($envFile);

        if ($_ENV === 1) {
            throw new \Exception("Incorrect format of the environment file: '{$envFile}'.");
        }
    }
}
