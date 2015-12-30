<?php

namespace Otas\Engine\Action;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;
use Symfony\Component\Console\Helper\Table;

/** Not functional yet! */

class Look implements Action {

    function getActionMap() {
        return array(
            array('look'),
            array('look', 'around'),
            array('look', '%object%'),      // %object% is a wildcard that matches all known objects (not functional yet)
        );
    }

    function getActionAliasMap() {
        return array(
            'look' => array('l')
        );
    }

    function getUsage() {
        return "Look around or at something";
    }

    function execute(Adventure $adventure, Scene $scene, array $args) {
        if (count($args) <= 1) {
            $adventure->output("Look at what?");
            return;
        }

        if ($args[1] == 'around') {
            // Look around

            $adventure->output("<question>".$adventure->getState()->getScene()->getTitle()."</question>\n");
            $adventure->output("\n");

            $adventure->output($adventure->getState()->getScene()->getDescription()."\n");
            $adventure->output("\n");

            $exits = $adventure->getState()->getScene()->getExits();

            $str = join(", ",$adventure->getState()->getScene()->getExits()->getKeys());

            if (count($exits) == 1) {
                $adventure->output("There is " . count($exits) . " exit to the ". $str . ".\n");
            } else {
                $adventure->output("There are " . count($exits) . " exits: " . $str . "\n");
            }
            $adventure->output("\n");



        } else {

            //Look at object
            $object = $scene->getObject($args[1]);
            if (!$object) {
                $adventure->output("There is no " . $args[1] . " to look at.");
            }

            $adventure->output("You look at " . $object->getName());
            $adventure->output($object->getDescription());
        }
    }

}
