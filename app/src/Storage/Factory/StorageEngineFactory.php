<?php

namespace App\Storage;

use Symfony\Component\Filesystem\Filesystem;
use App\Storage\Exception\StorageEngineNotConfiguredException;
use App\Storage\Exception\UnsupportedStorageEngineTypeException;

final readonly class StorageEngineFactory
{
    /**
     * @param array<string, array<string,mixed>> $storageEngineConfigs
     */
    public function __construct(
        private array $storageEngineConfigs
    ) {
    }

    /**
     * @throws StorageEngineNotConfiguredException
     * @throws UnsupportedStorageEngineTypeException
     */
    public function create(string $engineName): StorageEngineInterface
    {
        if (!isset($this->storageEngineConfigs[$engineName])) {
            throw StorageEngineNotConfiguredException::forName($engineName);
        }

        $config = $this->storageEngineConfigs[$engineName];

        return match ($config['type']) {
            'filesystem' => new FilesystemStorageEngineService(
                $config['base_path'],
                new Filesystem()
            ),
            // 's3' => new S3StorageEngineService(...), // Placeholder for S3 storage engine

            default => throw UnsupportedStorageEngineTypeException::forType($config['type']),
        };
    }
}
