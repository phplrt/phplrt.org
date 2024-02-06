<?php

declare(strict_types=1);

namespace App\Presentation\Request\ValueResolver;

use App\Presentation\Request\Attribute\Query;
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

        if (!$attribute instanceof Query) {
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

    protected function findBodyAttribute(ArgumentMetadata $argument): ?Query
    {
        foreach ($argument->getAttributes(Query::class, ArgumentMetadata::IS_INSTANCEOF) as $attribute) {
            /** @var Query */
            return $attribute;
        }

        return null;
    }
}
