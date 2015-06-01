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

use Symcloud\Component\Database\Replication\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SymcloudStorageExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $definitions = array();

        $definitions['symcloud_storage.servers.primary'] = $this->createServer($config['servers']['primary']);

        $container->addDefinitions($definitions);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
        $loader->load(sprintf('storage/%s.xml', $config['adapter']));
        $loader->load(sprintf('search/%s.xml', $config['search']));
        $loader->load('database.xml');
        $loader->load('file-storage.xml');
        $loader->load('metadata-storage.xml');
        $loader->load('session.xml');

        $replicator = $container->getDefinition('symcloud_storage.database.replicator');
        foreach ($config['servers']['backups'] as $key => $serverConfig) {
            $replicator->addMethodCall('addServer', array($serverConfig['host'], $serverConfig['port']));
        }
    }

    private function createServer($config)
    {
        return new Definition(Server::class, array($config['host'], $config['port']));
    }
}
