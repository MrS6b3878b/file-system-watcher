<?php

namespace App\Watcher\Model;

final readonly class WatcherConfig
{
    public function __construct(
        public string $storageEngine,
        public array $supportedMimeTypes,
        public array $folders,
        public array $caches,
        public int $watchIntervalInMinutes,
        public array $supportedEvents
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            storageEngine: $data['storage_engine'],
            supportedMimeTypes: $data['supported_mime_types'],
            folders: $data['folders'],
            caches: $data['caches'],
            watchIntervalInMinutes: $data['watch_interval_in_minutes'],
            supportedEvents: $data['supported_events']
        );
    }
}
