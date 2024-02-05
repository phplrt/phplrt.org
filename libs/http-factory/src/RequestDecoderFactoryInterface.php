<?php

declare(strict_types=1);

namespace Bsg\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

interface RequestDecoderFactoryInterface
{
    public function createDecoder(Request $request): ?RequestDecoderInterface;
}
