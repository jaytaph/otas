<?php

require_once __DIR__ . "/vendor/autoload.php";

use Symfony\Component\Console\Application;
use CliOtas\Command as Commands;

$application = new Application();
$application->setName('Open Text Adventure System (OTAS)');
$application->add(new Commands\RunCommand());

$application->add(new Commands\MapCommand());
$application->run();
