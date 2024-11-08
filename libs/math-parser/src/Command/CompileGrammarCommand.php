<?php

declare(strict_types=1);

namespace Local\MathParser\Command;

use Phplrt\Compiler\Compiler;
use Phplrt\Source\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CompileGrammarCommand extends Command
{
    private const GRAMMAR_PATH = __DIR__ . '/../../resources';

    public function getName(): string
    {
        return 'phplrt:compile';
    }

    private function input(string $name): string
    {
        return \realpath(self::GRAMMAR_PATH) . '/' . $name . '.pp2';
    }

    private function output(string $name): string
    {
        return \realpath(self::GRAMMAR_PATH) . \DIRECTORY_SEPARATOR . $name . '.php';
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        [$grammar, $file] = [$this->input('math'), $this->output('math')];

        $io = new SymfonyStyle($input, $output);

        $compiled = (new Compiler())
            ->load(File::fromPathname($grammar))
            ->build()
            ->withClassReference('Local\\MathParser\\Ast');

        $io->info('Compilation ' . $grammar);

        \file_put_contents($file, $compiled->generate());

        $io->success('Compiled ' . $file);

        return self::SUCCESS;
    }
}
