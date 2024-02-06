<?php

declare(strict_types=1);

namespace Admin\Providers;

use Admin\Models\AdminExternalLink;
use Admin\Models\AdminMenuLink;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

final class DatabaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'page' => AdminMenuLink::class,
            'external' => AdminExternalLink::class,
        ]);

        DB::enableQueryLog();

        DB::listen(function (QueryExecuted $query) {
            app('log')->debug($query->sql);
        });
    }
}
