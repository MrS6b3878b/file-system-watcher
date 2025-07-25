<?php

namespace App\Media\Event;

final readonly class MediaModifiedEvent
{
    public function __construct(
        public string $path,
        public string $fullPath,
        public string $mimeType,
    ) {}
}
