<?php

namespace App\Watcher\Support;

final readonly class FolderMatcher
{
    public function __construct(private array $patterns) {}

    public function matches(string $folderName): bool
    {
        foreach ($this->patterns as $pattern) {
            if (str_starts_with($pattern, '^')) {
                if (preg_match('~' . $pattern . '~', $folderName)) {
                    return true;
                }
            } elseif ($folderName === $pattern) {
                return true;
            }
        }
        return false;
    }
}
