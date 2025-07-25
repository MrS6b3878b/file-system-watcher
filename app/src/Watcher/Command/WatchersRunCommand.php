<?php

namespace App\Command;

use App\Watcher\WatcherKernel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'watchers:run',
    description: 'Run all configured watchers',
)]
class WatchersRunCommand extends Command
{
    public function __construct(
        private readonly WatcherKernel $kernel
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->kernel->runAll();
        $output->writeln('<info>All watchers executed.</info>');
        return Command::SUCCESS;
    }
}
