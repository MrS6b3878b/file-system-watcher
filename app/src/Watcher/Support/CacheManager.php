<?php

namespace App\Watcher\Support;

use App\Storage\StorageEngineInterface;

final readonly class CacheManager
{
    public function __construct(
        private StorageEngineInterface $storage,
        private string $metadataPath,
        private string $offsetPath
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function loadMetadata(): array
    {
        if (!$this->storage->exists($this->metadataPath)) {
            return [];
        }

        return json_decode($this->storage->read($this->metadataPath), true, 512, JSON_THROW_ON_ERROR);
    }

    public function saveMetadata(array $data): void
    {
        $this->storage->write($this->metadataPath, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    /**
     * @throws \JsonException
     */
    public function loadOffset(): array
    {
        if (!$this->storage->exists($this->offsetPath)) {
            return [];
        }

        return json_decode($this->storage->read($this->offsetPath), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    public function saveOffset(array $data): void
    {
        $this->storage->write($this->offsetPath, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }
}
