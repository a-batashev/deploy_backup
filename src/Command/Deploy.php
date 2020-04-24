<?php

namespace App\Command;

class Deploy extends Command
{
    public function run()
    {
        Clean::run();
        Download::run();
        Configure::run();

        echo 'Deploy is successfull';
    }
}
