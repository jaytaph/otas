<?php

namespace Otas\Engine;

/* Creates new objects based on the configuration */
class ObjectFactory {

    static function create($config) {
        // @TODO: use option resolved for array validation

        return new Object(
            $config['name'],
            $config['summary'],
            $config['description'],
            $config
        );
    }

}

