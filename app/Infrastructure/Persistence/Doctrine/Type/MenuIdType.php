<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Documentation\MenuId;

final class MenuIdType extends UniversalUniqueIdType
{
    protected static function getClass(): string
    {
        return MenuId::class;
    }
}
