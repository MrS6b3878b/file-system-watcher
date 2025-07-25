<?php

namespace App\Media\Event;

final readonly class MediaCreatedEvent
{
    public function __construct(
        public string $path,
        public string $fullPath,
        public string $mimeType,
    ) {}
}
