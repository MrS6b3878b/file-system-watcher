<?php

namespace App;

use App\Storage\DependencyInjection\StorageEnginesExtension;
use App\Watcher\DependencyInjection\WatchersExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerExtension(new StorageEnginesExtension());
        $container->registerExtension(new WatchersExtension());
    }
}
