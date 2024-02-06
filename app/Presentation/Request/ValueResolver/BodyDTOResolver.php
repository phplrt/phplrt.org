<?php

declare(strict_types=1);

namespace App\Presentation\Request\ValueResolver;

use App\Presentation\Request\Attribute\Body;
use Local\HttpFactory\RequestDecoderFactoryInterface;
use Local\HttpFactory\RequestDecoderInterface;
use Local\Hydrator\HydratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BodyDTOResolver extends DTOResolver
{
    public function __construct(
        private readonly RequestDecoderFactoryInterface $decoders,
        private readonly RequestDecoderInterface $default,
        HydratorInterface $hydrator,
    ) {
        parent::__construct($hydrator);
    }

    #[\Override]
    protected function create(Request $request, ArgumentMetadata $argument): ?object
    {
        //
        // 1. Lookup for #[Body] attribute
        //

        $attribute = $this->findBodyAttribute($argument);

        if (!$attribute instanceof Body) {
            return null;
        }

        $decoder = $this->decoders->createDecoder($request) ?? $this->default;

        try {
            return $this->hydrate(
                data: $decoder->decode($request->getContent()),
                argument: $argument,
                attribute: $attribute,
            );
        } catch (HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException(
                message: 'An error occurred while processing request content',
                previous: $e,
            );
        }
    }

    protected function findBodyAttribute(ArgumentMetadata $argument): ?Body
    {
        foreach ($argument->getAttributes(Body::class, ArgumentMetadata::IS_INSTANCEOF) as $attribute) {
            /** @var Body */
            return $attribute;
        }

        return null;
    }
}
