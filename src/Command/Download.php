<?php

namespace App\Command;

use App\Downloader;
use App\Downloader\DownloaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'download', description: 'Get files from remote/local repository')]
class Download extends Command
{
    /** @inheritDoc */
    protected function executeChild(InputInterface $input, OutputInterface $output)
    {
        $transportType = $this->cfg['transport']['type'];

        $downloader = $this->chooseDownloader($transportType);

        $downloader->get();

        if (!$this->quiet) {
            $output->writeln('Download complete');
        }
    }

    /**
     * Downloader dispatching
     *
     * @param string $transport
     * @throws \Exception
     * @return DownloaderInterface
     */
    protected function chooseDownloader(string $transport): DownloaderInterface
    {
        $downloaderClass = Downloader::class . '\\' . ucfirst($transport);

        if (class_exists($downloaderClass)) {
            return new $downloaderClass();
        }

        throw new \Exception("Transport '{$transport}' not found.");
    }
}
