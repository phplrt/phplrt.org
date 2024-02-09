<?php

declare(strict_types=1);

namespace Admin\MoonShine\Resources;

use Admin\Models\AdminMenu;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\HasMany;
use MoonShine\Fields\Text;

final class AdminMenuResource extends ModelResource
{
    public string $model = AdminMenu::class;

    public string $column = 'title';

    public array $with = [
        'pages',
    ];

    protected string $sortColumn = 'sorting_order';
    protected string $sortDirection = 'asc';

    public function title(): string
    {
        return __('Menu');
    }

    public function search(): array
    {
        return ['id', 'title'];
    }

    public function formFields(): array
    {
        return [
            Text::make(__('Title'), 'title')
                ->sortable()
                ->required(),
            Number::make(__('Order'), 'sorting_order')
                ->sortable()
                ->default(0),
            HasMany::make(__('Pages'), 'pages', resource: new AdminPageResource())
                ->creatable()
                ->async(),
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
            Preview::make(__('Title'), 'title')
                ->badge(function () {
                    return $this->item->getLabelColor() ?? 'gray';
                })
                ->sortable(),
            HasMany::make(
                label: __('Items'),
                relationName: 'pages',
                resource: new AdminPageResource(),
            )
                ->fields([
                    Text::make(__('Title'), 'title'),
                    Text::make(__('Url'), 'url'),
                ]),
            Date::make(__('Created At'), 'created_at')
                ->hideOnIndex(),
            Date::make(__('Updated At'), 'updated_at')
                ->hideOnIndex(),
        ];
    }
}
