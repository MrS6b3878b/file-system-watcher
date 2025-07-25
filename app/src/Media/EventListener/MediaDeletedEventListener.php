<?php

namespace App\Media\EventListener;

use App\Media\Event\MediaCreatedEvent;
use App\Media\Event\MediaDeletedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: MediaDeletedEvent::class)]
final class MediaDeletedEventListener
{
    public function __construct(
        private HttpClientInterface $memeClient
    ) {
    }

    public function __invoke(MediaCreatedEvent $event): void
    {
        // Example logic when file is created
        $path = $event->path;
        $fullPath = $event->fullPath;
        $mimeType = $event->mimeType;

        // Your actual logic here...
    }
}
