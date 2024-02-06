<?php

declare(strict_types=1);

namespace App\Presentation\Response\Transformer;

/**
 * @template TInput
 * @template TOutput
 */
interface ResponseTransformerInterface
{
    /**
     * @param TInput $entry
     *
     * @return TOutput
     */
    public function transform(mixed $entry): mixed;
}
