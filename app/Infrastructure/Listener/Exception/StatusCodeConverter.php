<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final readonly class StatusCodeConverter
{
    /**
     * @param iterable<\Throwable, int<400, 599>> $mappings
     * @param int<400, 599> $default
     */
    public function __construct(
        private iterable $mappings = [],
        private int $default = Response::HTTP_INTERNAL_SERVER_ERROR,
    ) {}

    /**
     * @return int<400, 599>
     */
    public function getStatusCode(\Throwable $e): int
    {
        if ($e instanceof HttpExceptionInterface) {
            /**
             * @var int<400, 599> An error is possible here: Symfony may not
             *     return an incorrect error status code.
             */
            return $e->getStatusCode();
        }

        foreach ($this->mappings as $exception => $code) {
            if ($e instanceof $exception) {
                return $code;
            }
        }

        return $this->getDefaultStatusCode();
    }

    /**
     * @return int<400, 599>
     */
    private function getDefaultStatusCode(): int
    {
        return $this->default;
    }
}
