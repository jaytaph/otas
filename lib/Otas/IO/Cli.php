<?php

/* Commandline interface */

namespace Otas\IO;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cli implements IO {

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     *
     */
    function input() {
    }

    /**
     * @param $str
     */
    function output($str) {
        $this->output->write($str);
    }

}
