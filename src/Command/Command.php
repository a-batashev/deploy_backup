<?php

namespace App\Command;

/**
 * Parent class for commands
 */
class Command
{
    /**
     * Commands dispatching
     *
     * @param string $command
     */
    public function __construct($command)
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

        new $className();
    }
}
