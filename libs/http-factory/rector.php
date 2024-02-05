<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\Class_\ClassConstantToSelfClassRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $config): void {
    $config->paths([__DIR__ . '/src']);

    $config->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
    ]);

    $config->skip([
        // Replaces "__CLASS__" to "self::class"
        ClassConstantToSelfClassRector::class,
    ]);
};
