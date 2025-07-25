<?php

namespace App\Media\EventListener;

use App\Media\Event\MediaCreatedEvent;
use App\Media\Event\MediaModifiedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: MediaModifiedEvent::class)]
final class MediaModifiedEventListener
{
    public function __invoke(MediaModifiedEvent $event): void
    {
        // Example logic when file is created
        $path = $event->path;
        $fullPath = $event->fullPath;
        $mimeType = $event->mimeType;

        // Your actual logic here...
    }
}
