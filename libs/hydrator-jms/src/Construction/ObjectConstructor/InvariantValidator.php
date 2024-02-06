<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS\Construction\ObjectConstructor;

use Local\Hydrator\Exception\MappingExceptionInterface;
use Local\Hydrator\JMS\Exception\MappingException;
use Local\Hydrator\JMS\Exception\MappingException\Context;
use Local\Hydrator\JMS\Exception\MultipleMappingException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;

/**
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Hydrator\JMS\Construction
 */
final class InvariantValidator
{
    public function validate(ClassMetadata $metadata, mixed $data, DeserializationContext $context): ?MappingExceptionInterface
    {
        // Process only array and object data
        if (!\is_array($data) && !\is_object($data)) {
            return null;
        }

        $errors = $this->collectErrors($metadata, $data, $context);

        if ($errors === []) {
            return null;
        }

        if (\count($errors) === 1) {
            return \reset($errors);
        }

        return new MultipleMappingException($errors);
    }

    /**
     * @throws MappingExceptionInterface
     */
    public function validateOrFail(ClassMetadata $metadata, mixed $data, DeserializationContext $context): void
    {
        $error = $this->validate($metadata, $data, $context);

        if ($error !== null) {
            throw $error;
        }
    }

    /**
     * @return list<MappingException>
     */
    private function collectErrors(ClassMetadata $class, array|object $data, DeserializationContext $ctx): array
    {
        $errors = [];

        foreach ($class->propertyMetadata as $property) {
            if ($this->isValid($property, $data)) {
                continue;
            }

            $context = $this->createErrorContext($property, $ctx);
            $name = $property->serializedName ?? $property->name ?: '<unknown>';

            $errors[] = MappingException::fromContextOfField($context, $name);
        }

        return $errors;
    }

    private function createErrorContext(PropertyMetadata $meta, DeserializationContext $ctx): Context
    {
        /** @var list<non-empty-string> $actualPath */
        $actualPath = $ctx->getCurrentPath();

        $expectedTypeExists = \is_array($meta->type)
            && isset($meta->type['name'])
            && \is_string($meta->type['name'])
            && $meta->type['name'] !== '';

        $expectedType = $expectedTypeExists ? $meta->type['name'] : 'mixed';

        return new Context($expectedType, null, $actualPath);
    }

    private function isValid(PropertyMetadata $meta, array|object $data): bool
    {
        return $this->hasBeenPassed($meta, $data)
            || !$this->isRequired($meta);
    }

    /**
     * @param array|object $data
     */
    private function hasBeenPassed(PropertyMetadata $meta, mixed $data): bool
    {
        /** @var string|null|false $name */
        $name = $meta->serializedName;

        if ($name === null || $name === false || $name === '') {
            $name = $meta->name;
        }

        if (\is_array($data)) {
            return isset($data[$name]) && \array_key_exists($name, $data);
        }

        return \property_exists($data, $name);
    }

    private function isRequired(PropertyMetadata $meta): bool
    {
        return !$meta->skipWhenEmpty && !$this->hasDefaultValue($meta);
    }

    private function hasDefaultValue(PropertyMetadata $meta): bool
    {
        // JMS 3.0+ compatibility
        if (\property_exists($meta, 'hasDefault')) {
            return $meta->hasDefault !== false;
        }

        if (\property_exists($meta, 'reflection')) {
            /**
             * @var \ReflectionProperty $reflection
             * @psalm-suppress UndefinedPropertyFetch : Property existence check above
             */
            $reflection = $meta->reflection;

            // For PHP 8.0+
            if (\PHP_VERSION_ID >= 80000) {
                /** @psalm-suppress all : Only PHP 8.0+ support */
                return (bool)$reflection->hasDefaultValue();
            }

            // Non-typed properties contain NULLs as default
            if ($reflection->getType() === null) {
                return true;
            }

            // PHP 7.x compatibility code
            // Check that property contains in 'defaults' array
            $class = $reflection->getDeclaringClass();
            return \array_key_exists(
                $reflection->getName(),
                $class->getDefaultProperties(),
            );
        }

        return false;
    }
}
