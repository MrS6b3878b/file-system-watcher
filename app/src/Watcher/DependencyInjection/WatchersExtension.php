<?php

namespace App\Watcher\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class WatchersExtension extends Extension
{
    public function getAlias(): string
    {
        return 'watchers';
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new WatchersConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('watchers', $config);
    }
}

