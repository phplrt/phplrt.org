<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS\Exception;

use Local\Hydrator\Exception\HydratorExceptionInterface;

class HydratorException extends \LogicException implements HydratorExceptionInterface
{
    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
