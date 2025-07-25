<?php

namespace App\Storage\Exception;

final class FileNotFoundException extends StorageException
{
    public static function forPath(string $path): self
    {
        return new self("File not found: {$path}");
    }
}
