<?php

namespace Otas\Engine\Action;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;
use Symfony\Component\Console\Helper\Table;

class Inventory implements Action {

    function getActionMap() {
        return array(
            array('look', 'inventory'),
            array('inventory'),
        );
    }

    function getActionAliasMap() {
        return array(
            'inventory' => array('inv')
        );
    }

    function getUsage() {
        return "Display all items in your inventory";
    }

    function execute(Adventure $adventure, Scene $scene, array $args) {
        $inv = $adventure->getState()->getInventory();

        if (count($inv) == 0) {
            $adventure->output("You don't carry anything with you.");
            return;
        }

        $adventure->output(sprintf("You carry %d item(s) in your inventory:\n", count($inv)));
        foreach ($inv as $object) {
              $adventure->output(sprintf(" *  <info>%s</info> : %s\n", $object->getName(), $object->getSummary()));
        }
    }

}
