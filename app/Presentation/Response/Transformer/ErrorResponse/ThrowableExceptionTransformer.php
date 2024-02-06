<?php

declare(strict_types=1);

namespace App\Presentation\Response\Transformer\ErrorResponse;

use App\Presentation\Response\DTO\ErrorResponse\ThrowableExceptionDTO;
use App\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<\Throwable, list<ThrowableExceptionDTO>>
 */
final readonly class ThrowableExceptionTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): array
    {
        assert($entry instanceof \Throwable);

        $result = [];

        do {
            $result[] = new ThrowableExceptionDTO(
                message: $entry->getMessage(),
                class: $entry::class,
                file: $entry->getFile(),
                code: $entry->getCode(),
                line: $entry->getLine(),
                trace: \explode("\n", $entry->getTraceAsString()),
            );
        } while ($entry = $entry->getPrevious());

        return $result;
    }
}
