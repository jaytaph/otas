<?php

namespace Otas\Engine;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class SceneConfiguration implements ConfigurationInterface {

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('scene');

        $rootNode
            ->children()
                ->scalarNode('title')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('description')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('exit')
                    ->children()
                        ->scalarNode('north')->end()
                        ->scalarNode('east')->end()
                        ->scalarNode('south')->end()
                        ->scalarNode('west')->end()
                    ->end()
                ->end()
                ->arrayNode('hidden_exit')
                    ->children()
                        ->scalarNode('north')->end()
                        ->scalarNode('east')->end()
                        ->scalarNode('south')->end()
                        ->scalarNode('west')->end()
                    ->end()
                ->end()


                ->arrayNode('objects')
                    ->prototype('array')
                        ->children()
                            ->enumNode('type')->values(array('object', 'container'))->defaultValue('object')->end()
                            ->enumNode('state')->values(array('visible', 'hidden'))->defaultValue('visible')->end()
                            ->scalarNode('summary')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->beforeNormalization()
                                    ->always()
                                    ->then(function($v) {
                                        return trim($v);
                                    })
                                ->end()
                            ->end()
                            ->scalarNode('description')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->beforeNormalization()
                                    ->always()
                                    ->then(function($v) {
                                        return trim($v);
                                    })
                                ->end()
                            ->end()
                            ->arrayNode('actions')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('contains')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('action_aliases')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('simple_hooks')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }


}

