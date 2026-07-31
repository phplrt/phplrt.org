<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Id\UniversalUniqueId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @template T of UniversalUniqueId
 */
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
     * @return class-string<T>
     */
    abstract protected static function getClass(): string;

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
     * @param T|string|\Stringable|null $value
     *
     * @phpstan-ignore-next-line
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (\is_string($value) || $value instanceof \Stringable) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param non-empty-string|null $value
     *
     * @return T|null
     *
     * @phpstan-ignore-next-line
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UniversalUniqueId
    {
        if ($value === null) {
            return null;
        }

        $class = static::getClass();

        /**
         * @var T|null
         *
         * @phpstan-ignore-next-line
         */
        return new $class($value);
    }
}
