<?php

namespace App\Command;

class Deploy extends Command
{
    public function __construct()
    {
        new Clean();
        new Download();
        new Configure();

        echo 'Deploy is successfull';
    }
}
