<?php

declare(strict_types=1);

namespace Admin\Providers;

use Admin\MoonShine\Resources\AdminPageResource;
use Admin\MoonShine\Resources\AdminLinkResource;
use Admin\MoonShine\Resources\AdminMenuResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    protected function resources(): array
    {
        return [];
    }

    protected function pages(): array
    {
        return [];
    }

    protected function menu(): array
    {
        return [
            MenuItem::make(
                label: __('Categories'),
                filler: new AdminMenuResource(),
                icon: 'heroicons.list-bullet',
            ),
            MenuItem::make(
                label: __('Menu'),
                filler: new AdminLinkResource(),
                icon: 'heroicons.bars-3-bottom-right',
            ),
            MenuItem::make(
                label: __('Documentation'),
                filler: new AdminPageResource(),
                icon: 'heroicons.document',
            ),

            MenuGroup::make(__('moonshine::ui.resource.system'), [
               MenuItem::make(
                   label: __('moonshine::ui.resource.admins_title'),
                   filler: new MoonShineUserResource()
               ),
               MenuItem::make(
                   label: __('moonshine::ui.resource.role_title'),
                   filler: new MoonShineUserRoleResource()
               ),
            ]),

            MenuItem::make(__('Website'), 'https://phplrt.org')
                ->badge(fn() => 'Open'),
        ];
    }

    /**
     * @return array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [];
    }
}
