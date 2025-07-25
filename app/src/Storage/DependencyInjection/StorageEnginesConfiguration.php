<?php

namespace App\Storage\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class StorageEnginesConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('storage_engines');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
            ->scalarNode('type')->isRequired()->end()
            ->scalarNode('base_path')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

