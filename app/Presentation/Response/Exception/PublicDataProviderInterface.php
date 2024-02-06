<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception;

/**
 * @template TData of mixed
 */
interface PublicDataProviderInterface
{
    /**
     * Returns additional error data.
     *
     * @return TData
     */
    public function getData(): mixed;

    /**
     * @param TData $data
     */
    public function withAdditionalData(mixed $data): self;
}
