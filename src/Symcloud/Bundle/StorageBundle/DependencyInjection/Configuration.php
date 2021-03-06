<?php

/*
 * This file is part of the Symcloud Distributed-Storage.
 *
 * (c) Symcloud and Johannes Wachter
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symcloud\Bundle\StorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sulu_security');
        $rootNode
            ->children()
                ->enumNode('adapter')
                    ->values(array('filesystem', 'riak'))
                    ->defaultValue('filesystem')
                ->end()
                ->enumNode('search')
                    ->values(array('lucene', 'elastic'))
                    ->defaultValue('lucene')
                ->end()
                ->arrayNode('servers')
                    ->children()
                        ->arrayNode('primary')
                            ->children()
                                ->scalarNode('host')->end()
                                ->scalarNode('port')->defaultValue(80)->end()
                            ->end()
                        ->end()
                        ->arrayNode('backups')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('host')->end()
                                    ->scalarNode('port')->defaultValue(80)->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
