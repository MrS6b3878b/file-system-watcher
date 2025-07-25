<?php

namespace App\Storage\Exception;

final class DirectoryCreationException extends StorageException
{
    public static function forPath(string $path): self
    {
        return new self("Failed to create directory: {$path}");
    }
}
