<?php

declare(strict_types=1);

namespace Admin\MoonShine\Pages;

use MoonShine\Pages\Page;

class Dashboard extends Page
{
    public function breadcrumbs(): array
    {
        return [
            '#' => $this->title()
        ];
    }

    public function title(): string
    {
        return $this->title ?: 'Dashboard';
    }

    public function components(): array
    {
        return [];
    }
}
