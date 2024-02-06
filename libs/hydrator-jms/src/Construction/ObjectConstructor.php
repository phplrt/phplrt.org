<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS\Construction;

use Local\Hydrator\Exception\MappingExceptionInterface;
use Local\Hydrator\JMS\Construction\ObjectConstructor\InvariantValidator;
use Local\Hydrator\JMS\Exception\HydratorException;
use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\Instantiator\Instantiator;
use Doctrine\Instantiator\InstantiatorInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Hydrator\JMS\Construction
 */
final class ObjectConstructor
{
    public function __construct(
        private readonly InstantiatorInterface $instantiator = new Instantiator(),
        private readonly InvariantValidator $validator = new InvariantValidator(),
    ) {}

    /**
     * @psalm-suppress LessSpecificReturnType
     * @psalm-suppress InvalidReturnType
     *
     * @throws MappingExceptionInterface
     */
    public function construct(ClassMetadata $metadata, mixed $data, DeserializationContext $context): ?object
    {
        try {
            /**
             * @psalm-suppress ArgumentTypeCoercion : "$metadata->name" is valid
             *                 class-string reference.
             */
            $instance = $this->instantiator->instantiate($metadata->name);
        } catch (ExceptionInterface $e) {
            /** @psalm-suppress RedundantCast : Error code may be non-int */
            throw new HydratorException($e->getMessage(), (int)$e->getCode(), $e);
        }

        $this->validator->validateOrFail($metadata, $data, $context);

        /** @var object */
        return $instance;
    }
}
