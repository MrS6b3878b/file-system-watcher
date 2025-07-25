<?php

namespace App\Watcher;

use App\Media\Event\MediaCreatedEvent;
use App\Media\Event\MediaDeletedEvent;
use App\Media\Event\MediaModifiedEvent;
use App\Storage\StorageEngineInterface;
use App\Watcher\Model\WatcherConfig;
use App\Watcher\Support\MimeMatcher;
use App\Watcher\Support\FolderMatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\MimeTypes;

final class FileWatcherService implements WatcherInterface
{
    private array $currentMetadata = [];
    private array $previousMetadata = [];

    public function __construct(
        private readonly WatcherConfig $config,
        private readonly StorageEngineInterface $storage,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function run(): void
    {
        $folderMatcher = new FolderMatcher($this->config->folders);
        $mimeMatcher = new MimeMatcher($this->config->supportedMimeTypes);

        $this->loadMetadataCache();

        $allItems = $this->storage->list('');

        foreach ($allItems as $item) {
            $itemPath = $item;

            if (!$folderMatcher->matches($itemPath)) {
                continue;
            }

            $this->handleFile($itemPath, $mimeMatcher);
        }

        $this->detectDeletedFiles();

        $this->saveMetadataCache();
    }

    private function handleFile(string $path, MimeMatcher $mimeMatcher): void
    {
        $fullPath = $this->storage->resolvePath($path);

        if ($this->storage->isDir($path)) {
            // It's a folder — recurse into it
            $nested = $this->storage->list($path);
            foreach ($nested as $entry) {
                $this->handleFile($path . '/' . $entry, $mimeMatcher);
            }
            return;
        }

        if (!file_exists($fullPath)) {
            return;
        }

        $mtime = filemtime($fullPath);
        $this->currentMetadata[$path] = $mtime;

        $mimeType = $this->detectMimeType($fullPath);

        if (!$mimeMatcher->matches($mimeType)) {
            return;
        }

        $prev = $this->previousMetadata[$path] ?? null;

        if ($prev === null && in_array('created', $this->config->supportedEvents, true)) {
            $event = new MediaCreatedEvent($path, $fullPath, $mimeType);
            $this->eventDispatcher->dispatch($event, MediaCreatedEvent::class);
        } elseif ($prev !== null && $prev !== $mtime && in_array('modified', $this->config->supportedEvents, true)) {
            $event = new MediaModifiedEvent($path, $fullPath, $mimeType);
            $this->eventDispatcher->dispatch($event, MediaModifiedEvent::class);
        }
    }

    private function detectMimeType(string $fullPath): ?string
    {
        $file = new File($fullPath);
        $mimeType = $file->getMimeType();

        if ($mimeType === 'application/x-empty') {
            // Try to guess from extension
            $extension = $file->getExtension();
            if ($extension) {
                $mimeTypes = new MimeTypes();
                $guessedTypes = $mimeTypes->getMimeTypes($extension);
                if (!empty($guessedTypes)) {
                    $mimeType = $guessedTypes[0];
                }
            }
        }

        return $mimeType;
    }

    /**
     * @throws \JsonException
     */
    private function loadMetadataCache(): void
    {
        if ($this->storage->exists($this->config->caches['metadata'])) {
            $json = $this->storage->read($this->config->caches['metadata']);
            $this->previousMetadata = json_decode($json, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } else {
            $this->previousMetadata = [];
        }
    }

    /**
     * @throws \JsonException
     */
    private function saveMetadataCache(): void
    {
        $json = json_encode($this->currentMetadata, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        $this->storage->write($this->config->caches['metadata'], $json);
    }

    private function detectDeletedFiles(): void
    {
        foreach ($this->previousMetadata as $path => $mtime) {
            if (!isset($this->currentMetadata[$path]) && in_array('deleted', $this->config->supportedEvents, true)) {
                $event = new MediaDeletedEvent($path, $path, 'text/plain');
                $this->eventDispatcher->dispatch($event, MediaDeletedEvent::class);
            }
        }
    }
}
