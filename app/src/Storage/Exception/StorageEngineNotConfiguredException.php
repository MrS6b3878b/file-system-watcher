<?php

namespace App\Storage\Exception;

final class StorageEngineNotConfiguredException extends \InvalidArgumentException
{
    public static function forName(string $name): self
    {
        return new self("Storage engine '{$name}' is not configured.");
    }
}
