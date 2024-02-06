<?php

declare(strict_types=1);

namespace Admin\MoonShine\Resources;

use Admin\Models\AdminPage;
use Admin\MoonShine\Fields\Markdown;
use MoonShine\Fields\ID;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;

final class AdminPageResource extends ModelResource
{
    public string $model = AdminPage::class;

    public string $column = 'title';

    public array $with = [
        'link.menu',
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
            BelongsTo::make(
                label: __('Menu'),
                relationName: 'link',
                resource: new AdminLinkResource(),
            )
                ->sortable()
                ->nullable(),
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
            Text::make(__('Title'), 'title')
                ->sortable()
                ->required(),
            Text::make(__('Url'), 'url')
                ->sortable()
                ->required(),
            Preview::make(__('Menu'), 'link.title', function () {
                return $this->item?->link?->title ?? '<unknown>';
            })
                ->badge(function () {
                    return $this->item?->link?->menu?->getLabelSecondaryColor() ?? 'gray';
                }),
            Preview::make(__('Category'), 'link.menu.title', function () {
                return $this->item?->link?->menu?->title ?? '<unknown>';
            })
                ->badge(function () {
                    return $this->item?->link?->menu?->getLabelColor() ?? 'gray';
                }),
        ];
    }
}
