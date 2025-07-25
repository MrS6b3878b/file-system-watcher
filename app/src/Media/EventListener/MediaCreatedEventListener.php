<?php

namespace App\Media\EventListener;

use App\Media\Event\MediaCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: MediaCreatedEvent::class)]
final readonly class MediaCreatedEventListener
{
    public function __construct(
        private HttpClientInterface $txtClient,
        private HttpClientInterface $jsonClient
    ) {
    }

    public function __invoke(MediaCreatedEvent $event): void
    {
        // Example logic when file is created
        $path = $event->path;
        $fullPath = $event->fullPath;
        $mimeType = $event->mimeType;

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        match ($extension) {
//            'jpg', 'jpeg' => $this->optimizeImage($fullPath),
            'json'        => $this->postJson($fullPath),
            'txt'         => $this->appendBaconIpsum($fullPath),
//            'zip'         => $this->extractZip($fullPath),
            default       => null
        };
    }

    private function postJson(string $file): void
    {
        $content = file_get_contents($file);
        $response = $this->jsonClient->request('POST', 'test', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $content,
        ]);
    }

    private function appendBaconIpsum(string $file): void
    {
        $response = $this->txtClient->request('GET', '?type=meat-and-filler&paras=1');
        $data = $response->toArray();

        $text = $data[0] ?? '';

        $appendText = '';

        if (filesize($file) > 0) {
            $appendText .= "\n\n"; // add extra breaks only if file is not empty
        }

        $appendText .= $text;

        file_put_contents($file, $appendText, FILE_APPEND);
    }
}
