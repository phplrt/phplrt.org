<?php

declare(strict_types=1);

namespace App\Presentation\Response\DTO;

/**
 * @template TData of mixed
 *
 * @template-extends ResponseDTO<TData>
 */
final readonly class SuccessResponseDTO extends ResponseDTO
{
    /**
     * @param TData|null $data
     * @param array<non-empty-string, object|array> $extra
     */
    public function __construct(
        mixed $data = null,
        array $extra = [],
    ) {
        parent::__construct($data, $extra);
    }
}
