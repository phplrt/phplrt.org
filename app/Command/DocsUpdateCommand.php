<?php

declare(strict_types=1);

namespace App\Command;

use App\Sync\DocsUpdater;
use App\Sync\MenuUpdater;
use App\Sync\SearchIndexUpdater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocsUpdateCommand extends Command
{
    public function __construct(
        private readonly string $directory,
        private readonly MenuUpdater $menu,
        private readonly DocsUpdater $docs,
        private readonly SearchIndexUpdater $search
    ) {
        $this->directory = $directory;
        $this->menu = $menu;
        $this->docs = $docs;
        $this->search = $search;

        parent::__construct('docs:update');
    }

    public function getName(): string
    {
        return 'docs:update';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->menu->truncate();
        $this->docs->truncate();
        $this->search->truncate();

        $this->menu->update($this->directory, $output);
        $this->docs->update($this->directory, $output);
        $this->search->update($output);

        return Command::SUCCESS;
    }
}
