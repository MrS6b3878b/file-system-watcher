<?php

namespace App\Watcher\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class WatchersConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('watchers');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->useAttributeAsKey('name')
            ->arrayPrototype()
            ->children()
            ->scalarNode('type')->isRequired()->end()
            ->scalarNode('storage_engine')->isRequired()->end()
            ->arrayNode('supported_mime_types')
            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('folders')
            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('caches')
            ->children()
            ->scalarNode('metadata')->isRequired()->end()
            ->scalarNode('offset')->isRequired()->end()
            ->end()
            ->end()
            ->integerNode('watch_interval_in_minutes')->defaultValue(2)->end()
            ->arrayNode('supported_events')
            ->scalarPrototype()->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
