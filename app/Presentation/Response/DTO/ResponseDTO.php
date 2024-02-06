<?php

declare(strict_types=1);

namespace App\Presentation\Response\DTO;

/**
 * @template TData of mixed
 */
abstract readonly class ResponseDTO
{
    /**
     * @param TData|null $data
     * @param array<non-empty-string, object|array> $extra
     */
    public function __construct(
        public mixed $data = null,
        public array $extra = [],
    ) {}
}
