<?php

namespace App\Environment;

class Php
{
    /**
     * Load environment from a PHP file
     *
     * @throws \Exception
     * @param string $envFile
     */
    public function parseFile($envFile): void
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
