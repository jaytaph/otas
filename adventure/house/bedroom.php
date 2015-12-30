<?php

namespace House;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;

/* This stuff is not functional yet */

class Bedroom extends Hooks {

    /**
     * Turn a lamp on and off
     *
     * @otas\command('turn_lamp_on');
     * @otas\command('turn_lamp_off');
     */
    function turn_lamp_on(Adventure $adventure, Scene $scene, array $command) {
        if ($command[2] == 'on') {
            if ($scene->objects['lamp']->vars['lit']) {
                $adventure->output('The lamp is already on');
            } else {
                $scene->objects['lamp']->vars['lit'] = true;
                $scene->lit = true;

                $adventure->output('You turn on the light. The room seems a lot less darker now. You notice a small piece of paper lying on the floor.');
            }
        }

        if ($command[2] == 'off') {
            if (! $scene->objects['lamp']->vars['lit']) {
                $adventure->output('The lamp is already off');
            } else {
                $scene->objects['lamp']->vars['lit'] = false;
                $scene->lit = false;

                $adventure->output('You turn off the light. Strangely enough, you can see less.');
            }

        }
    }
}
