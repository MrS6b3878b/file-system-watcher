<?php

namespace App\Watcher;

use Symfony\Component\Yaml\Yaml;

final readonly class WatcherKernel
{
    /**
     * @param string $configPath Absolute path to watchers.yaml
     */
    public function __construct(
        private string $configPath,
        private WatcherFactory $watcherFactory
    ) {
    }

    public function runAll(): void
    {
        $rawConfig = Yaml::parseFile($this->configPath);
        $watchers = $rawConfig['watchers'] ?? [];

        foreach ($watchers as $name => $watcherConfig) {
            $watcher = $this->watcherFactory->create($name, $watcherConfig);
            $watcher->run();
        }
    }
}
