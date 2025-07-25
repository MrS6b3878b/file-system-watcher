<?php

namespace App\Storage\Exception;

final class UnsupportedStorageEngineTypeException extends \InvalidArgumentException
{
    public static function forType(string $type): self
    {
        return new self("Unsupported storage engine type '{$type}'.");
    }
}
