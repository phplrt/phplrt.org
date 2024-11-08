<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueResolver;

use App\Presentation\Request\Attribute\MapRequestPayloadAttribute;
use Local\Hydrator\Exception\HydratorExceptionInterface;
use Local\Hydrator\Exception\MappingExceptionInterface;
use Local\Hydrator\HydratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class DTOResolver implements ValueResolverInterface
{
    public function __construct(
        protected readonly HydratorInterface $hydrator,
    ) {}

    /**
     * @return iterable<object|null>
     *
     * @throws \Throwable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = $this->create($request, $argument);

        if ($dto === null) {
            return [];
        }

        return [$dto];
    }

    /**
     * Factory method that should return a DTO object based on the request's content-type.
     *
     * @throws \Throwable
     */
    abstract protected function create(Request $request, ArgumentMetadata $argument): ?object;

    /**
     * @throws HydratorExceptionInterface
     */
    protected function hydrate(object|array $data, ArgumentMetadata $argument, MapRequestPayloadAttribute $attribute): object
    {
        /** @var class-string $type */
        $type = $attribute->as ?? $argument->getType() ?? 'object';

        try {
            return (object)$this->hydrator->hydrate($type, $data);
        } catch (MappingExceptionInterface $e) {
            throw new UnprocessableEntityHttpException($e->getMessage(), $e);
        }
    }
}
