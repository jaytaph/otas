<?php

namespace Otas\Parser;

/*
 * Proof of concept parser
 *
 *

 */

use Otas\Engine\Adventure;
use Otas\Parser\Exception\ParseException;

class Parser {
    /** @var Adventure */
    protected $adventure;


    /**
     * @param Adventure $adventure
     */
    function __construct(Adventure $adventure) {
        $this->adventure = $adventure;
    }

    /**
     * Returns an action map with the generic commands, plus the commands from the current scene
     *
     * @return array
     */
    protected function getActionMap()
    {
        // Generic actions
        $actions = $this->adventure->getActionMap();

        // Merge with current scene actions
        $actions = array_merge($actions, $this->adventure->getState()->getScene()->getActionMap());

        return $actions;
    }

    /**
     * Returns an action alias map from the generic commands, plus the commands from the current scene
     *
     * @return array
     */
    protected function getActionAliasMap() {
        // Generic aliases
        $aliases = $this->adventure->getActionAliasMap();

        // Merge with current scene aliases
        $aliases = array_merge($aliases, $this->adventure->getState()->getScene()->getActionAliasMap());

        return $aliases;
    }


    /**
     * Parse() function is to reduce the number of candidates to 1. If 0, no action matches, if 1, we found the
     * action, when > 1, action is ambiguous (ie: 'use hammer' on what?)
     *
     * @param $str
     * @return mixed    (actually: array[Action, array args])
     * @throws ParseException
     */
    function parse($str)
    {
        $actions = $this->getActionMap();

        // Create a simple map with offsets to detect possible candidates
        $candidates = array();
        foreach ($actions as $k => $v) {
            $candidates[$k] = array_fill_keys(array_keys($v), 0);
        }

        $args = array();

        // Iterate each word
        foreach (new WordIterator($str) as $word) {

            $added = false;

            // Iterate each action
            foreach (array_keys($candidates) as $fqcn) {

                // Normalize word based on the current iterated action (for instance, 'h' will be normalized to
                // 'help', but only when we are checking the 'help' action'.
                $word = $this->normalizeAliases($fqcn, $word);


                /* Check if the word matches in the action map. It's not a very good way of matching, but it
                 * seems to work well enough for now. */
                foreach ($candidates[$fqcn] as $idx => $ck) {
                    if (count($actions[$fqcn][$idx]) > $ck && $actions[$fqcn][$idx][$ck] == $word) {
                        $candidates[$fqcn][$idx]++;
                        $added = true;
                    }
                }
            }
            if ($added) {
                $args[] = $word;
            }
        }

        // Next, we check which of the candidates have matched their FULL array (ie: array('give', 'help')).
        $action = null;
        foreach (array_keys($candidates) as $fqcn) {
            foreach ($candidates[$fqcn] as $i => $ck) {
                if (count($actions[$fqcn][$i]) == $ck) {
                    // If we already found an action, it means that more than one action matches our
                    // command. That is not ok.
                    if ($action && $action != $fqcn) {
                        throw new ParseException("Ambiguous action: previous action found: ".$action);
                    }

                    // Found an action, so store it for later use
                    $action = $fqcn;
                }
            }
        }

        // No action was actually found
        if (! $action) {
            throw new ParseException("Not sure what you mean...");
        }

        // Return the FQCN from the action, plus a (fixed) list of arguments. This can be used by the action for later
        // parsing: (ie: turn light on|off).
        return array($action, $args);
    }


    /**
     * Normalizes an action based on its map. For instance, 'h' and '?' are normalized to 'help', 'inv' to 'inventory'
     * etc.
     *
     * @param $fqcn
     * @param $word
     * @return int|string
     */
    protected function normalizeAliases($fqcn, $word) {

        $aliasMap = $this->getActionAliasMap();

        // Try and find (first) normalized word that may be an alias inside the alias map for the given command
        foreach ($aliasMap[$fqcn] as $k => $aliases) {
            if (in_array($word, $aliases)) {
                return $k;
            }
        }

        return $word;
    }

}
