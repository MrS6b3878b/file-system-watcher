<?php

namespace App\Watcher;

use App\Storage\StorageEngineFactory;
use App\Watcher\Model\WatcherConfig;
use App\Watcher\Exception\UnsupportedWatcherTypeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class WatcherFactory
{
    public function __construct(
        private StorageEngineFactory $storageEngineFactory,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function create(string $name, array $config): WatcherInterface
    {
        if (!isset($config['type']) || $config['type'] !== 'file') {
            throw UnsupportedWatcherTypeException::forName($name);
        }

        $storageEngine = $this->storageEngineFactory->create($config['storage_engine']);

        $watcherConfig = WatcherConfig::fromArray($config);

        return match ($config['type']) {
            'file' => new FileWatcherService($watcherConfig, $storageEngine, $this->eventDispatcher),
        };
    }
}
