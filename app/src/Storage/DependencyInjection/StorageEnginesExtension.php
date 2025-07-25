<?php

namespace App\Storage\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class StorageEnginesExtension extends Extension
{
    public function getAlias(): string
    {
        return 'storage_engines';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new StorageEnginesConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('storage_engines', $config);
    }
}

