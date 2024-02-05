<?php

declare(strict_types=1);

namespace Bsg\HttpFactory;

interface RequestDecoderInterface
{
    public function decode(string $payload): object|array;
}
