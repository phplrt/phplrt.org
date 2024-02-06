<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class ResponseEncoder implements ResponseMatcherInterface
{
    public function encode(object|array $data, int $code = self::DEFAULT_HTTP_CODE): Response
    {
        $response = new Response($this->toString($data));
        $response->setStatusCode($code);

        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Vary', 'Accept-Encoding');

        $this->extend($response);

        return $response;
    }

    /**
     * Returns {@see true} in case of HTTP request provides "application/json"
     * or "application/vnd.api+json" accept header.
     *
     * @param list<non-empty-string> $acceptable
     */
    protected function accepts(Request $request, array $acceptable = []): bool
    {
        foreach ($request->getAcceptableContentTypes() as $contentType) {
            if (\in_array($contentType, $acceptable, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<non-empty-string> $acceptable
     */
    protected function shouldSetContentType(Response $response, array $acceptable = []): bool
    {
        $actual = $response->headers->get('Content-Type');

        return !\in_array($actual, $acceptable, true);
    }

    /**
     * Transforms variant payload to body string.
     */
    abstract protected function toString(object|array $data): string;

    abstract protected function extend(Response $response): void;
}
