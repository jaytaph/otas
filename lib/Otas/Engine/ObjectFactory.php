<?php

namespace Otas\Engine;

/* Creates new objects based on the configuration */
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjectFactory {

    static public function create(array $config) {
        $resolver = new OptionsResolver();
        self::configureOptions($resolver);
        $resolver->resolve($config);

        return new Object(
            $config['name'],
            $config['summary'],
            $config['description'],
            $config
        );
    }

    static public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'name',
            'type',
            'state',
            'summary',
            'description'
        ));

        $resolver->setDefaults(array(
            'actions' => array(),
            'contains' => array(),
            'action_aliases' => array(),
            'simple_hooks' => array(),
        ));
    }

}

