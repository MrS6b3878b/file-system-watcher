# file-system-watcher

## Overview

**file-system-watcher** is a Symfony 7.3 and PHP 8.4 application that monitors file system changes and performs specific actions on files when they are created, modified, or deleted. Designed with modularity and extensibility in mind, it allows adding new watchers for different file types or events with ease.

## Technical Stack

- Symfony 7.3 framework
- PHP 8.4
- Symfony HttpClient with scoped clients for different APIs
- Symfony EventDispatcher for modular event handling
- Custom storage abstraction to interact with the file system

## Installation & Usage

1. Clone the repository:
    ```bash
    git clone git@github.com:MrS6b3878b/file-system-watcher.git
    ```
2. Navigate into the `app` directory:
    ```bash
    cd app
    ```
3. Run the watcher command:
    ```bash
    ./bin/console watchers:run
    ```

This will start monitoring the configured folders and trigger appropriate actions on file events.

## Architecture & Design

- **WatcherInterface:** Defines the core contract for watcher services.
- **FileWatcherService:** Implements watcher logic including file event detection, MIME type handling, and dispatching events.
- **Event-Driven:** Uses Symfony’s EventDispatcher to fire file event notifications (created, modified, deleted). Listeners handle specific actions, enabling modularity.
- **Scoped HTTP Clients:** Uses Symfony’s scoped HTTP clients for different file types to interact with external APIs cleanly.
- **Metadata Caching:** Keeps track of file modification times to efficiently detect changes between runs.
- **Extensibility:** Designed to easily add new watchers or file handlers by leveraging events and service injection.

## Challenges & Conceptual Thoughts

- Finding the right abstractions to keep the solution modular and maintainable while covering diverse file handling scenarios was the main challenge.
- Event-driven design separates file event detection from processing logic, allowing easier future extension.
- Scoped HTTP clients isolate external API dependencies based on file MIME types.
- File metadata caching ensures efficient monitoring without unnecessary processing.
- The architecture can be extended by adding new event listeners or watchers conforming to the common interface.

---

- Replace event dispatcher with Symfony messenger
- Extend watchers.yaml in order to be able to ingest the files form a storage engine and use the metadata cache or offest cache of another storage engine;
- Simplify logic from FileWatcherService;
- And many more, but unfortunately, I do not have enough time left to explain.