<?php

namespace App\Environment;

class DotEnv
{
    public function parseFile($envPath)
    {
        if (class_exists('\Symfony\Component\Dotenv\Dotenv')) {
            $dotenv = new \Symfony\Component\Dotenv\Dotenv();
            return $dotenv->load($envPath);
        }

        if (class_exists('\Dotenv\Dotenv')) {
            $dir = is_file($envPath) ? dirname($envPath) : $envPath;
            $dotenv = \Dotenv\Dotenv::createImmutable($dir);
            return $dotenv->load();
        }

        throw new \Exception(
            "DotEnv parser not found. Use 'composer require symfony/dotenv' or 'composer require vlucas/phpdotenv'."
        );
    }
}
