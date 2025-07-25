<?php

namespace App\Storage;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use App\Storage\Exception\FileNotFoundException;
use App\Storage\Exception\DirectoryCreationException;

final readonly class FilesystemStorageEngineService implements StorageEngineInterface
{
    public function __construct(
        private string $basePath,
        private Filesystem $filesystem
    ) {
    }

    public function resolvePath(string $relativePath): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);
    }

    public function read(string $relativePath): string
    {
        $fullPath = $this->resolvePath($relativePath);

        if (!$this->filesystem->exists($fullPath)) {
            throw FileNotFoundException::forPath($fullPath);
        }

        return file_get_contents($fullPath);
    }

    public function write(string $relativePath, string $content): void
    {
        $fullPath = $this->resolvePath($relativePath);
        $dir = dirname($fullPath);

        try {
            if (!$this->filesystem->exists($dir)) {
                $this->filesystem->mkdir($dir, 0775);
            }

            file_put_contents($fullPath, $content);
        } catch (IOExceptionInterface) {
            throw DirectoryCreationException::forPath($dir);
        }
    }

    public function exists(string $relativePath): bool
    {
        return $this->filesystem->exists($this->resolvePath($relativePath));
    }

    public function list(string $relativePath): array
    {
        $fullPath = $this->resolvePath($relativePath);

        if (!is_dir($fullPath)) {
            return [];
        }

        $items = scandir($fullPath);
        return array_values(array_filter($items, static fn($item) => $item !== '.' && $item !== '..'));
    }

    public function mkdir(string $relativePath): void
    {
        $fullPath = $this->resolvePath($relativePath);

        if (!$this->filesystem->exists($fullPath)) {
            try {
                $this->filesystem->mkdir($fullPath, 0775);
            } catch (IOExceptionInterface) {
                throw DirectoryCreationException::forPath($fullPath);
            }
        }
    }

    public function isDir(string $relativePath): bool
    {
        $fullPath = $this->resolvePath($relativePath);

        if (!$this->filesystem->exists($fullPath)) {
            return false;
        }

        return is_dir($fullPath);
    }
}
