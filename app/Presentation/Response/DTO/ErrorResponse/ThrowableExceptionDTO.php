<?php

declare(strict_types=1);

namespace App\Presentation\Response\DTO\ErrorResponse;

final class ThrowableExceptionDTO
{
    /**
     * @psalm-taint-sink file $file
     *
     * @param class-string $class
     * @param non-empty-string $file
     * @param int<1, max> $line
     * @param list<non-empty-string> $trace
     */
    public function __construct(
        public string $message,
        public string $class,
        public string $file,
        public int|string $code,
        public int $line,
        public array $trace = [],
    ) {}
}
