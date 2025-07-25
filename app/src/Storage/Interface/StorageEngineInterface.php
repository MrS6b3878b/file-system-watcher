<?php

namespace App\Storage;

interface StorageEngineInterface
{
    /**
     * Resolve a relative path within this storage engine.
     * E.g., given "watcher1/metadata.json" it returns absolute or full path/URI.
     */
    public function resolvePath(string $relativePath): string;

    /**
     * Read contents from a given path.
     */
    public function read(string $relativePath): string;

    /**
     * Write contents to a given path.
     */
    public function write(string $relativePath, string $content): void;

    /**
     * Check if a file/directory exists.
     */
    public function exists(string $relativePath): bool;

    /**
     * List the contents of a directory.
     * Return array of relative paths (files/folders).
     */
    public function list(string $relativePath): array;

    public function mkdir(string $relativePath): void;

    public function isDir(string $relativePath): bool;
}
