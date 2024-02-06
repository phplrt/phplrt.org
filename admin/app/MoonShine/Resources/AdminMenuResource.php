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
        'links',
    ];

    protected string $sortColumn = 'priority';
    protected string $sortDirection = 'asc';

    public function title(): string
    {
        return __('Categories');
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
            Number::make(__('Order'), 'priority')
                ->sortable()
                ->default(0),
            HasMany::make(__('Menu'), 'links', resource: new AdminLinkResource())
                ->fields([
                    Text::make(__('Title'), 'title')
                ])
                ->creatable()
                ->async(),
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()
                ->hideOnIndex(),
            Preview::make(__('Order'), 'priority')
                ->badge(function () {
                    return $this->item->getLabelColor() ?? 'gray';
                })
                ->sortable(),
            Text::make(__('Title'), 'title')
                ->sortable(),
            HasMany::make(
                label: __('Menu'),
                relationName: 'links',
                resource: new AdminLinkResource(),
            )
                ->fields([
                    Text::make(__('Items'), 'title')
                ]),
            Date::make(__('Created At'), 'created_at')
                ->hideOnIndex(),
            Date::make(__('Updated At'), 'updated_at')
                ->hideOnIndex(),
        ];
    }
}
