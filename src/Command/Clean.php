<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'clean', description: 'Removes content of the directory')]
class Clean extends Command
{
    /** @inheritDoc */
    protected function executeChild(InputInterface $input, OutputInterface $output)
    {
        // Cleaning
        $sitePath = $this->cfg['sitePath'];

        $this->checkDir($sitePath);

        $this->cleanDir($sitePath);

        if (!$this->quiet) {
            $output->writeln('Cleaning complete.');
        }
    }

    /**
     * Check directory for reading/writing
     *
     * @param string $dir Path to the directory
     * @throws \Exception
     * @return void
     */
    public function checkDir(string $dir): void
    {
        if (!is_readable($dir)) {
            throw new \Exception("Directory isn't readable: '{$dir}'.");
        }

        if (!is_writable($dir)) {
            throw new \Exception("Directory isn't writable: '{$dir}'.");
        }
    }

    /**
     * Remove children of the directory
     *
     * @param string $dir Path to the directory
     * @return void
     */
    public function cleanDir(string $dir): void
    {
        $this->rrmdir($dir, $this->dryRun);

        if (!file_exists($dir)) {
            mkdir($dir, 0775);
        }
    }

    /**
     * Remove recursively content of a directory
     *
     * @param string $dir
     * @param boolean $deleteThisDir Remove self?
     * @return void
     */
    protected function rrmdir(string $dir, bool $deleteThisDir = false)
    {
        if (!is_readable($dir) || !is_writable($dir)) {
            echo "Can't remove {$dir}", PHP_EOL;

            return;
        }

        $scan = scandir($dir);

        if ($scan === false) {
            echo "Can't access to directory {$dir}", PHP_EOL;

            return;
        }

        $files = array_diff(
            $scan,
            ['.', '..']
        );

        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (!is_readable($path)) {
                if (is_link($path)) {
                    if ($this->dryRun) {
                        echo "unlink symlink {$path}", PHP_EOL;
                    } else {
                        unlink($path);
                    }

                    continue;
                }

                echo "Can't access to {$path}", PHP_EOL;
                continue;
            }

            if (is_dir($path)) {
                self::rrmdir($path, $this->dryRun, true);
            } else {
                if ($this->dryRun) {
                    echo "unlink {$path}", PHP_EOL;
                } else {
                    unlink($path);
                }
            }
        }

        if ($deleteThisDir) {
            if ($this->dryRun) {
                echo "rmdir {$dir}", PHP_EOL;
            } else {
                rmdir($dir);
            }
        }
    }
}
