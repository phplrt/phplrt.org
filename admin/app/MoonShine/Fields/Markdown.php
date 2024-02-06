<?php

declare(strict_types=1);

namespace Admin\MoonShine\Fields;

use Closure;
use MoonShine\Contracts\Fields\DefaultValueTypes\DefaultCanBeString;
use MoonShine\Contracts\Fields\HasDefaultValue;
use MoonShine\Fields\Field;
use MoonShine\Traits\Fields\WithDefaultValue;

class Markdown extends Field implements HasDefaultValue, DefaultCanBeString
{
    use WithDefaultValue;

    protected string $view = 'simple-mde';

    protected array $attributes = [
        'rows',
        'cols',
        'disabled',
        'readonly',
        'required',
    ];

    public function getAssets(): array
    {
        return [
            'assets/simplemde.min.js',
            'assets/simplemde.min.css',
        ];
    }
}
