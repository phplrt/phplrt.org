<?php

declare(strict_types=1);

namespace Admin\Models;

use App\Domain\Documentation\Menu;
use App\Domain\Documentation\MenuId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AdminMenu extends Model
{
    protected $table = 'menu';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $with = [
        'links',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $guarded = [];

    public static function booted(): void
    {
        self::creating(function ($model) {
            if ($model->id === null) {
                $model->id = MenuId::fromNamespace(Menu::class)
                    ->toString();
            }
        });
    }

    public function getLabelColor(array $colors = null): string
    {
        $colors ??= ['primary', 'secondary', 'info', 'success', 'warning', 'error'];

        $id = $this->priority ?: \crc32($this->title ?? '<unknown>');

        return $colors[$id % \count($colors)];
    }

    public function getLabelSecondaryColor(): string
    {
        return $this->getLabelColor(['purple', 'pink', 'blue', 'green', 'yellow', 'red']);
    }

    public function links(): HasMany
    {
        return $this->hasMany(
            related: AdminLink::class,
            foreignKey: 'menu_id',
            localKey: 'id',
        );
    }
}
