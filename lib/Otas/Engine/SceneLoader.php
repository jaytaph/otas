<?php

namespace Otas\Engine;

use Doctrine\Common\Collections\ArrayCollection;

/*
 * This class loads all scenes from a given adventure configuration
 */

class SceneLoader  {

    static function load(Config $config) {
        $scenes = new ArrayCollection();

        // Start with the entry scene
        $stack = array();
        $scene = $config['entry_scene'];
        array_push($stack, $scene);

        // Loop until our stack is empty
        while (count($stack)) {
            // Load a scene from the stack
            $scenePath = array_pop($stack);
            $data = Yaml::load($config['base_directory'].'/'.$scenePath . '.yml');

            // Create a scene from the data
            $newScene = new Scene($scenePath, $data);
            $scenes->set($scenePath, $newScene);

            // If we have exit-scenes, add them to the stack so we can load them
            foreach ($data['scene']['exit'] as $direction => $exitScene) {
                $tmp['exit'][strtolower($direction)] = $exitScene;

                // Only add to stack when we haven't already processed that scene
                if ($scenes->containsKey($exitScene)) {
                    array_push($stack, $exitScene);
                }
            }
        }

        return $scenes;
    }

}

