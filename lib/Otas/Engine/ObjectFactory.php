<?php

namespace Otas\Engine;

/* Creates new objects based on the configuration */
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectFactory {

    static public function create(array $config) {
        return new Object($config);
    }

}

