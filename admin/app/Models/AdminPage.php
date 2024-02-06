<?php

declare(strict_types=1);

namespace Admin\Models;

use App\Domain\Documentation\Page;
use App\Domain\Documentation\PageId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AdminPage extends Model
{
    protected $table = 'documentation';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public static function booted(): void
    {
        self::creating(function ($model) {
            if ($model->id === null) {
                $model->id = PageId::fromNamespace(Page::class)
                    ->toString();
            }
        });
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(
            related: AdminLink::class,
            foreignKey: 'id',
            ownerKey: 'page_id',
        );
    }
}
