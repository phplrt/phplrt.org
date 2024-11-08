<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Infrastructure\Listener\Exception\StatusCodeConverter;
use App\Presentation\Response\Exception\ErrorInfo;
use App\Presentation\Response\Exception\PresentationException;
use App\Presentation\Response\Transformer\ErrorResponseTransformer;
use Local\HttpFactory\ResponseEncoderFactoryInterface;
use Local\Hydrator\ExtractorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class FailedResponseListener
{
    public function __construct(
        private ExtractorInterface $extractor,
        private StatusCodeConverter $converter,
        private ErrorResponseTransformer $errors,
        private ResponseEncoderFactoryInterface $factory,
    ) {}

    private function getAdditionalHeaders(\Throwable $e): array
    {
        if ($e instanceof HttpExceptionInterface) {
            /** @var array<non-empty-string, string> */
            return $e->getHeaders();
        }

        return [];
    }

    public function __invoke(ExceptionEvent $event): void
    {
        if ($event->isPropagationStopped()) {
            return;
        }

        //
        // Detect "accept" header
        //
        $factory = $this->factory->createEncoder($event->getRequest());

        if ($factory === null) {
            return;
        }

        $exception = $this->convertException($event->getThrowable());

        //
        // Create HTTP Response
        //
        $response = $factory->encode(
            data: $this->extractor->extract(
                data: $this->errors->transform($exception),
            ),
            code: $this->converter->getStatusCode($exception)
        );

        //
        // Extend HTTP Response Headers
        //
        $response->headers->add($this->getAdditionalHeaders($exception));

        $event->setResponse($response);
        $event->stopPropagation();
    }

    private function convertException(\Throwable $exception): \Throwable
    {
        if ($exception instanceof HttpException) {
            return match ($exception->getStatusCode()) {
                // In case of any 503 error occurred
                Response::HTTP_SERVICE_UNAVAILABLE => PresentationException::fromPublicInfo(
                    info: ErrorInfo::ERR_MAINTENANCE,
                    previous: $exception,
                ),
                // In case of any 401, 406 or 511 error occurred
                Response::HTTP_NOT_ACCEPTABLE,
                Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
                Response::HTTP_UNAUTHORIZED => PresentationException::fromPublicInfo(
                    info: ErrorInfo::ERR_UNAUTHORIZED,
                    previous: $exception,
                ),
                default => $exception,
            };
        }

        return match (true) {
            // In case of security auth error (firewall)
            $exception instanceof DisabledException,
            $exception instanceof AccessDeniedException,
            $exception instanceof UserNotFoundException,
            $exception instanceof BadCredentialsException,
            $exception instanceof AuthenticationException => PresentationException::fromPublicInfo(
                info: ErrorInfo::ERR_UNAUTHORIZED,
                previous: $exception,
            ),
            default => $exception,
        };
    }
}
