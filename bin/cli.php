#!/usr/bin/env php
<?php

use App\Command;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->addCommands([
    new Command\Clean(),
    new Command\Download(),
    new Command\Database(),
    new Command\Configure(),
    new Command\Deploy(),
]);

$app->run();
