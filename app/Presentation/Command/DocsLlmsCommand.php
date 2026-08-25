<?php

declare(strict_types=1);

namespace App\Presentation\Command;

use App\Domain\Documentation\LlmsGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Rewrites "public/llms.txt" and "public/llms-full.txt" from the pages that are
 * already stored, without touching the pages themselves.
 */
#[AsCommand('docs:llms', 'Rewrites public/llms.txt and public/llms-full.txt from the stored pages')]
final class DocsLlmsCommand extends Command
{
    public function __construct(
        private readonly LlmsGenerator $llms,
    ) {
        parent::__construct();
    }

    #[\Override]
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->llms->generate() as $pathname) {
            $output->writeln(\sprintf('Wrote <info>%s</info>', $pathname));
        }

        return Command::SUCCESS;
    }
}
