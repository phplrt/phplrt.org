<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-consistent-constructor
 */
abstract readonly class UniversalUniqueId implements IdInterface
{
    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @param non-empty-string|\Stringable $value
     */
    public function __construct(string|\Stringable $value)
    {
        $this->value = (string) $value;
    }

    /**
     * @param non-empty-string $namespace
     */
    public static function fromNamespace(string $namespace): static
    {
        return new static(Uuid::uuid5(Uuid::uuid4(), $namespace));
    }

    public function toUuid(): UuidInterface
    {
        return Uuid::fromString($this->value);
    }

    /**
     * @return non-empty-string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $this === $object
            || ($object instanceof static && $this->value === (string) $object);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
