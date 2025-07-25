<?php

namespace App\Watcher\Exception;

final class UnsupportedWatcherTypeException extends \RuntimeException
{
    public static function forName(string $name): self
    {
        return new self("Watcher '{$name}' has unsupported or missing type.");
    }
}
