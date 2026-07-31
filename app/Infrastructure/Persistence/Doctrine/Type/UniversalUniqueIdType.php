<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Shared\IdInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class UniversalUniqueIdType extends Type
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return static::getClass();
    }

    /**
     * @return class-string<IdInterface>
     */
    abstract protected static function getClass(): string;

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // Note: SQLite has no native "UUID" type. Such a declaration would fall
        //       back to the NUMERIC affinity, so a fixed-length string is used
        //       instead: "CHAR(36)".
        return $platform->getStringTypeDeclarationSQL([
            'length' => 36,
            'fixed' => true,
        ]);
    }

    /**
     * @return non-empty-string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|null
    {
        if (\is_string($value)) {
            return $value;
        }

        if ($value instanceof \Stringable) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param non-empty-string|null $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?IdInterface
    {
        if ($value === null) {
            return null;
        }

        $class = static::getClass();

        return new $class($value);
    }
}
