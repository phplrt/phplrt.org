<?php

declare(strict_types=1);

namespace Admin\MoonShine\Resources;

use Admin\Models\AdminLink;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Divider;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Date;
use MoonShine\Fields\ID;
use MoonShine\Fields\Number;
use MoonShine\Fields\Preview;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\StackFields;
use MoonShine\Fields\Text;

final class AdminLinkResource extends ModelResource
{
    public string $model = AdminLink::class;

    public string $column = 'title';

    protected array $parentRelations = [
        'menu',
    ];

    protected array $with = [
        'menu',
        'page',
    ];

    protected string $sortColumn = 'priority';
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
            Block::make([
                Tabs::make([
                    Tab::make(__('Type: Page'), [
                        BelongsTo::make(
                            label: __('Documentation'),
                            relationName: 'page',
                            resource: new AdminPageResource(),
                        )
                            ->nullable(),
                    ])
                        ->icon('heroicons.document-text'),
                    Tab::make(__('Type: Link'), [
                        Text::make(__('URL'), 'url')
                            ->nullable(),
                    ])
                        ->icon('heroicons.link'),
                ]),
            ]),
            Divider::make(),
            BelongsTo::make(
                label: __('Category'),
                relationName: 'menu',
                resource: new AdminMenuResource(),
            )
                ->sortable(),
            Text::make(__('Title'), 'title')
                ->required(),
            Number::make(__('Order'), 'priority')
                ->default(0),
        ];
    }

    public function fields(): array
    {
        return [
            ID::make()
                ->hideOnIndex(),
            Preview::make(__('Order'), 'priority')
                ->badge(function () {
                    return $this->item->menu->getLabelSecondaryColor() ?? 'gray';
                })
                ->sortable(),
            Preview::make(__('Category'), 'menu.title', function () {
                return $this->item->menu?->title ?? '<unknown>';
            })
                ->badge(function () {
                    return $this->item->menu?->getLabelColor() ?? 'gray';
                }),
            StackFields::make(__('Title'))->fields([
                Text::make(__('Title'), 'title')
                    ->sortable(),
                Preview::make(__('Type'), 'type')
                    ->badge(function (string $type): string {
                        return match ($type) {
                            'page' => 'purple',
                            'external' => 'yellow',
                            default => 'secondary',
                        };
                    }),
            ]),
            Date::make(__('Created At'), 'created_at')
                ->hideOnIndex(),
            Date::make(__('Updated At'), 'updated_at')
                ->hideOnIndex(),
        ];
    }
}
