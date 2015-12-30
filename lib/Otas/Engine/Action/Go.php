<?php

namespace Otas\Engine\Action;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;
use Symfony\Component\Console\Helper\Table;

class Go implements Action {

    function getActionMap() {
        return array(
            array('go'),
            array('go', 'north'),
            array('go', 'south'),
            array('go', 'east'),
            array('go', 'west'),
            array('north'),
            array('south'),
            array('east'),
            array('west'),
        );
    }

    function getActionAliasMap() {
        return array(
            'go' => array('g'),

            'north' => array('n'),
            'east' => array('e'),
            'south' => array('s'),
            'west' => array('w'),
        );
    }

    function getUsage() {
        return "Move around";
    }

    function execute(Adventure $adventure, Scene $scene, array $args) {
        if (count($args) <= 1) {
            $adventure->output("Where to?");
            return;
        }

        $direction = $args[1];

        if (! $scene->getExits()->containsKey($direction)) {
            $adventure->output('Cannot go ' . $direction);
            return;
        }

        $key = $scene->getExits()->get($direction);
        $newScene = $adventure->getScene($key);

        $adventure->getState()->setScene($newScene);

        list($action, $args) = $adventure->parse("look around");
        $action->execute($adventure, $adventure->getState()->getScene(), $args);

    }

}
