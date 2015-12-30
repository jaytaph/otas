<?php

namespace Otas\Engine;

/* Simple YAML loader */

use Symfony\Component\Yaml\Yaml as SymfonyYaml;

class Yaml {

    /**
     * @param $file
     * @return array
     */
    static function load($file) {
        if (! is_readable($file)) {
            throw new \RuntimeException(sprintf("Cannot read file '%s'", $file));
        }

        $yaml = SymfonyYaml::parse(file_get_contents($file));
        if (! $yaml) {
            throw new \RuntimeException(sprintf("Cannot read file '%s'", $file));
        }

        return $yaml;
    }

}

