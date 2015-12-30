<?php

namespace Otas\Engine\Action;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;

class Help implements Action {

    function getActionMap()
    {
        return array(
            array('help')
        );
    }

    function getActionAliasMap()
    {
        return array(
            'help' => array('h')
        );
    }


    function getUsage() {
        return "Give help info";
    }

    function execute(Adventure $adventure, Scene $scene, array $args) {

        $help = <<< EOT
Generic actions:
  <info>help</info>                   <comment>Give help info</comment>
  <info>inventory</info>              <comment>Display all items in your inventory</comment>
  <info>look [around]</info>          <comment>Get detailed information about your surroundings</comment>
  <info>look [at [object]]</info>     <comment>View object details</comment>
  <info>pick up [object]</info>       <comment>Pick up object into inventory</comment>
  <info>drop [object]</info>          <comment>Drop object from inventory</comment>
  <info>go north | north | n</info>   <comment>Go north (or south, west, east)</comment>

Other actions are available too, just use your imagination to '<info>use key on chest</info>' or '<info>talk to stranger</info>'.

EOT;

        $adventure->output($help);
    }

}
