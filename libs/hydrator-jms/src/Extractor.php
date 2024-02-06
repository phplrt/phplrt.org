<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS;

use Local\Hydrator\ExtractorInterface;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializationContext;

final readonly class Extractor implements ExtractorInterface
{
    public function __construct(
        private ArrayTransformerInterface $transformer,
    ) {}

    /**
     * @api
     */
    public function getArrayTransformer(): ArrayTransformerInterface
    {
        return $this->transformer;
    }

    public function extract(mixed $data): mixed
    {
        $context = (SerializationContext::create())
            ->setSerializeNull(true);

        if (\is_scalar($data) || $data === null) {
            return $data;
        }

        return $this->transformer->toArray($data, $context);
    }
}
