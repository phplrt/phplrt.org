<?php

declare(strict_types=1);

namespace App\Presentation\Response\Listener;

use App\Presentation\Response\Listener\Exception\StatusCodeConverter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

final readonly class ExceptionLoggerListener
{
    public function __construct(
        private StatusCodeConverter $converter,
        private ?LoggerInterface $logger = null,
    ) {}

    private function isLoggableStatusCode(int $code): bool
    {
        return $code >= 400 && $code !== 404;
    }

    private function isLoggableException(\Throwable $e): bool
    {
        return !$e instanceof HttpException;
    }

    private function isLoggable(\Throwable $e, int $code): bool
    {
        return $this->isLoggableStatusCode($code)
            && $this->isLoggableException($e);
    }

    public function __invoke(ExceptionEvent $event): void
    {
        if ($event->isPropagationStopped()) {
            return;
        }

        $exception = $event->getThrowable();
        $code = $this->converter->getStatusCode($exception);

        if (!$this->isLoggable($exception, $code)) {
            return;
        }

        $this->logger?->error($exception->getMessage(), [
            'http-code' => $code,
            'exception' => $exception,
        ]);
    }
}
