<?php

declare(strict_types=1);

namespace App\Presentation\Response\DTO;

use Symfony\Component\HttpFoundation\Response;

/**
 * @template TData of mixed
 *
 * @template-extends ResponseDTO<TData>
 */
final readonly class ErrorResponseDTO extends ResponseDTO
{
    /**
     * @var int<0, max>
     */
    public const DEFAULT_ERROR_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * @var non-empty-string
     */
    public const DEFAULT_ERROR_MESSAGE = 'An error occurred while processing request';

    /**
     * @param TData|null $data
     * @param non-empty-string $errmsg
     * @param int<1, max> $code
     * @param array<non-empty-string, object|array> $extra
     */
    public function __construct(
        mixed $data = null,
        public string $error = self::DEFAULT_ERROR_MESSAGE,
        public int $code = self::DEFAULT_ERROR_CODE,
        array $extra = [],
    ) {
        parent::__construct($data, $extra);
    }
}
