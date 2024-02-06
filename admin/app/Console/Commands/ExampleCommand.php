<?php

declare(strict_types=1);

namespace Admin\Console\Commands;

use Admin\Models\AdminMenu;
use Illuminate\Console\Command;

final class ExampleCommand extends Command
{
    protected $signature = 'test';

    public function handle(): void
    {
        foreach (AdminMenu::all() as $item) {
            dump($item->links()->getQuery()->ddRawSql());
        }
    }
}
