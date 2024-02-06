<?php

declare(strict_types=1);

namespace Admin\Models;

use App\Domain\Documentation\Menu\Link;
use App\Domain\Documentation\Menu\LinkId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AdminLink extends Model
{
    protected $table = 'menu_links';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'menu_id' => 'string',
        'page_id' => 'string',
    ];

    protected $guarded = [];

    public static function booted(): void
    {
        $typeSelection = function (AdminLink $link): void {
            if ($link->type === null) {
                $link->type = $link->page_id ? 'page' : 'external';
            }

            if ($link->url === null) {
                $link->url = '/';
            }

            if ($link->page) {
                $link->url = $link->page->url;
            }
        };

        self::creating(function (AdminLink $model) use ($typeSelection): void {
            if ($model->id === null) {
                $model->id = LinkId::fromNamespace(Link::class)
                    ->toString();
            }

            $typeSelection($model);
        });

        self::updating($typeSelection);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(
            related: AdminMenu::class,
            foreignKey: 'menu_id',
            ownerKey: 'id',
        );
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(
            related: AdminPage::class,
            foreignKey: 'page_id',
            ownerKey: 'id',
        );
    }
}
