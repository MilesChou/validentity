<?php

namespace App;

use Symfony\Component\Console\Application;

class App extends Application
{
    public function __construct()
    {
        parent::__construct('Validentity', 'dev-master');

        $this->addCommands([
            new Commands\TestCommand('test'),
        ]);
    }
}
