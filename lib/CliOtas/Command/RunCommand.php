<?php

namespace CliOtas\Command;

use Otas\Engine\Adventure;
use Otas\Engine\Config;
use Otas\Engine\SceneLoader;
use Otas\IO\Cli;
use Otas\Parser\Exception\ParseException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/* Actually runs a adventure */

class RunCommand extends Command {


    protected function configure()
    {
        $this
            ->setName('adventure:run')
            ->setDescription('Run a text adventure on the command line')
            ->setDefinition(array(
                new InputArgument('dir', InputArgument::REQUIRED, 'The directory containing the adventure'),
            ))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @TODO: Make sure we validate our configuration

        $io = new Cli($input, $output);
        $config = Config::load($input->getArgument('dir'));
        $scenes = SceneLoader::load($config);


        $adventure = new Adventure($io, $config, $scenes);

        // Display the start of the adventure
        $output->writeln($adventure->getConfig()['start']);
        list($action, $args) = $adventure->parse("help");
        $action->execute($adventure, $adventure->getState()->getScene(), $args);

        // Initialize vars
        $wrongParsedOrders = 0;

        // And go!
        while (true) {
            $output->writeln("");

            // Ask a question
            $question = new Question("<comment>[scene: <info>".$adventure->getState()->getScene()->getTitle()."</info>] [inv: <info>".count($adventure->getState()->getInventory())."</info>]</comment> > ");
            $qh = new QuestionHelper();
            $action = $qh->ask($input, $output, $question);
            if (trim($action) == "") continue;


            try {
                list($action, $args) = $adventure->parse($action);
                $wrongParsedOrders = 0;
            } catch (ParseException $e) {
                $output->writeln($e->getMessage());
                $wrongParsedOrders++;

                // If we have three unprocessed actions, it's time to hint the user for help
                if ($wrongParsedOrders > 3) {
                    // Maybe the user is stuck?
                    $output->writeln("Do you need some <info>help</info>?");
                }

                continue;
            }

            $action->execute($adventure, $adventure->getState()->getScene(), $args);
        }
    }
}
