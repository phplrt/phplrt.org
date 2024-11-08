<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueResolver;

use App\Presentation\Request\Attribute\MapQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class QueryDTOResolver extends DTOResolver
{
    #[\Override]
    protected function create(Request $request, ArgumentMetadata $argument): ?object
    {
        //
        // 1. Lookup for #[Query] attribute
        //

        $attribute = $this->findBodyAttribute($argument);

        if (!$attribute instanceof MapQuery) {
            return null;
        }

        try {
            return $this->hydrate(
                data: $request->query->all(),
                argument: $argument,
                attribute: $attribute,
            );
        } catch (HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(
                message: 'An error occurred while processing request query parameters',
                previous: $e,
            );
        }
    }

    protected function findBodyAttribute(ArgumentMetadata $argument): ?MapQuery
    {
        foreach ($argument->getAttributes(MapQuery::class, ArgumentMetadata::IS_INSTANCEOF) as $attribute) {
            /** @var MapQuery */
            return $attribute;
        }

        return null;
    }
}
