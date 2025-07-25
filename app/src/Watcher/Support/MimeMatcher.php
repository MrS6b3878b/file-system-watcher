<?php

namespace App\Watcher\Support;

final readonly class MimeMatcher
{
    public function __construct(private array $patterns) {}

    public function matches(string $mimeType): bool
    {
        foreach ($this->patterns as $pattern) {
            if (@preg_match('/^.+\/.+$/', $pattern) && str_starts_with($pattern, '^')) {
                if (preg_match('~' . $pattern . '~', $mimeType)) {
                    return true;
                }
            } elseif ($pattern === $mimeType) {
                return true;
            }
        }
        return false;
    }
}
