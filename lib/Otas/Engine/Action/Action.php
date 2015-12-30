<?php

namespace Otas\Engine\Action;

use Otas\Engine\Adventure;
use Otas\Engine\Scene;

interface Action {

    function getActionMap();
    function getActionAliasMap();

    function getUsage();

    function execute(Adventure $adventure, Scene $scene, array $args);

}
