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
        if (count($args) == 0) {
            $adventure->output("Look at what?");
            return;
        }

        if ($args[0] == 'around') {
            // Look around
        } else {

            //Look at object
            $object = $scene->getObject($args[0]);
            if (!$object) {
                $adventure->output("There is no " . $args[0] . " to look at.");
            }

            $adventure->output("You look at " . $object->getName());
            $adventure->output($object->getDescription());
        }
    }

}
