<?php

declare(strict_types=1);

namespace App\Presentation\Request\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ValidatorAwareDTOResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private DTOResolver $resolver,
    ) {}

    /**
     * @throws \Throwable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        foreach ($this->resolver->resolve($request, $argument) as $result) {
            $errors = $this->validator->validate($result);

            foreach ($errors as $error) {
                throw new UnprocessableEntityHttpException((string)$error->getMessage());
            }

            yield $result;
        }

        return [];
    }
}
