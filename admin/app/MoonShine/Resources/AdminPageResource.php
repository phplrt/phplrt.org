<?php

declare(strict_types=1);

namespace Admin\MoonShine\Resources;

use Admin\Models\AdminPage;
use Admin\MoonShine\Fields\Markdown;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;

final class AdminPageResource extends ModelResource
{
    public string $model = AdminPage::class;

    public string $column = 'title';

    public array $with = [
        'menu',
    ];

    protected string $sortColumn = 'url';
    protected string $sortDirection = 'asc';

    public function title(): string
    {
        return __('Documentation');
    }

    public function formFields(): array
    {
        return [
            ID::make(),
            Block::make([
                BelongsTo::make(__('Menu'), 'menu', resource: new AdminMenuResource()),
                Number::make(__('Order'), 'sorting_order')
                    ->min(0)
                    ->default(0),
            ]),
            Text::make(__('Title'), 'title')
                ->sortable()
                ->required(),
            Markdown::make('Содержимое', 'content_source'),
            Text::make(__('Url'), 'url')
                ->sortable()
                ->required(),
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()
                ->hideOnIndex(),
            Preview::make(__('Order'), 'sorting_order')
                ->badge('gray')
                ->sortable(),
            Text::make(__('Title'), 'title')
                ->sortable()
                ->required(),
            Text::make(__('Url'), 'url')
                ->sortable()
                ->required(),
            Preview::make(__('Menu'), 'menu.title', function () {
                return $this->item?->menu?->title ?? '<unknown>';
            })
                ->badge(function () {
                    return $this->item?->menu?->getLabelSecondaryColor() ?? 'gray';
                }),
        ];
    }
}
