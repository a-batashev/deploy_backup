<?php

namespace App;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Symfony Console input
 */
class ConsoleInput
{
    /** @var ConsoleInput Instance of Config */
    protected static $instance;

    /** @var InputInterface Instance of InputInterface */
    protected static $input;

    /**
     * Disables constructor for singleton
     */
    private function __construct()
    {
    }

    /**
     * Return instance of ConsoleInput
     *
     * @return ConsoleInput
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Set input
     *
     * @param InputInterface $input
     */
    public function setInput(InputInterface $input): void
    {
        static::$input = $input;
    }

    /**
     * Get input options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return static::$input->getOptions();
    }

    /**
     * Get input arguments
     *
     * @return array
     */
    public function getArguments(): array
    {
        return static::$input->getArguments();
    }
}
