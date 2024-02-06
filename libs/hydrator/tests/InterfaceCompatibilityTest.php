<?php

declare(strict_types=1);

namespace Local\Hydrator\Tests;

use PHPUnit\Framework\Attributes\Group;
use Local\Hydrator\Exception\HydratorExceptionInterface;
use Local\Hydrator\Exception\MappingExceptionInterface;
use Local\Hydrator\ExtractorInterface;
use Local\Hydrator\HydratorInterface;

/**
 * Note: Changing the behavior of these tests is allowed ONLY when updating
 *       a MAJOR version of the package.
 */
#[Group('rapid/hydrator')]
final class InterfaceCompatibilityTest extends TestCase
{
    public function testExtractorCompatibility(): void
    {
        self::expectNotToPerformAssertions();

        new class () implements ExtractorInterface {
            public function extract(mixed $data): mixed {}
        };
    }

    public function testHydratorCompatibility(): void
    {
        self::expectNotToPerformAssertions();

        new class () implements HydratorInterface {
            public function hydrate(string $type, mixed $data): mixed {}
        };
    }

    public function testHydratorExceptionCompatibility(): void
    {
        self::expectNotToPerformAssertions();

        new class () extends \Exception implements HydratorExceptionInterface {};
    }

    public function testMappingExceptionCompatibility(): void
    {
        self::expectNotToPerformAssertions();

        new class () extends \Exception implements MappingExceptionInterface {
            public function getPath(): array {}
            public function getExpectedType(): string {}
            public function getActualType(): ?string {}
        };
    }
}
