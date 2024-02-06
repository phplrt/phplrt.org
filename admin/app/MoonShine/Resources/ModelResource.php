<?php

declare(strict_types=1);

namespace Admin\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Resources\ModelResource as BaseModelResource;

abstract class ModelResource extends BaseModelResource
{
    public function rules(Model $item): array
    {
        return [];
    }
}
