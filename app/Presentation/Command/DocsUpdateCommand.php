<?php

declare(strict_types=1);

namespace App\Presentation\Command;

use App\Domain\Documentation\Search\IndexGenerator;
use App\Domain\Documentation\Synchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocsUpdateCommand extends Command
{
    public function __construct(
        private readonly Synchronizer $sync,
        private readonly IndexGenerator $index,
    ) {
        parent::__construct('docs:update');
    }

    public function getName(): string
    {
        return 'docs:update';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->index->truncate();
        {
            // Update docs
            $this->sync->truncate();
            $this->sync->sync();
        }
        $this->index->sync();

        return Command::SUCCESS;
    }
}
