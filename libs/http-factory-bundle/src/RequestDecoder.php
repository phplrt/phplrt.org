<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

abstract readonly class RequestDecoder implements RequestMatcherInterface
{
    public function decode(string $payload): object|array
    {
        if ($payload === '') {
            return [];
        }

        return $this->fromString($payload);
    }

    /**
     * Returns {@see true} in case of HTTP request provides something like
     * "application/json" content-type header.
     *
     * @param list<non-empty-string> $contentTypes
     */
    protected function provides(Request $request, array $contentTypes = []): bool
    {
        foreach ($request->headers->all('content-type') as $contentType) {
            if (\in_array($contentType, $contentTypes, true)) {
                return true;
            }
        }

        return false;
    }

    abstract protected function fromString(string $data): array|object;
}
