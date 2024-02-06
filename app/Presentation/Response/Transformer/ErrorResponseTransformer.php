<?php

declare(strict_types=1);

namespace App\Presentation\Response\Transformer;

use App\Presentation\Response\DTO\ErrorResponseDTO;
use App\Presentation\Response\Exception\PresentationExceptionInterface;
use App\Presentation\Response\Exception\PublicCodeProviderInterface;
use App\Presentation\Response\Exception\PublicDataProviderInterface;
use App\Presentation\Response\Exception\PublicMessageProviderInterface;
use App\Presentation\Response\Transformer\ErrorResponse\ThrowableExceptionTransformer;
use Local\Hydrator\ExtractorInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @template-extends ResponseTransformer<\Throwable, ErrorResponseDTO>
 */
final readonly class ErrorResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private ThrowableExceptionTransformer $errors,
        private ExtractorInterface $extractor,
        private ClockInterface $clock,
        private bool $debug = false,
    ) {}

    public function transform(mixed $entry): ErrorResponseDTO
    {
        assert($entry instanceof \Throwable);

        $extra = [];

        if ($this->debug) {
            $now = $this->clock->now();

            $extra['debug'] = [
                'exception' => $this->errors->transform($entry),
                'memory' => \number_format(\memory_get_usage() / 1000) . 'Kb',
                'now' => $now->format(\DateTimeInterface::RFC3339_EXTENDED),
            ];
        }

        return new ErrorResponseDTO(
            data: $this->getData($entry), error: $this->getMessage($entry), code: $this->getCode($entry), extra: $extra,
        );
    }

    private function getData(\Throwable $e): mixed
    {
        if ($e instanceof PublicDataProviderInterface) {
            $data = $e->getData();

            try {
                return match (true) {
                    $data === null => null,
                    \is_scalar($data) => $data,
                    default => $this->extractor->extract($data),
                };
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function getMessage(\Throwable $e): string
    {
        return match (true) {
            $e instanceof PublicMessageProviderInterface,
            $e instanceof HttpExceptionInterface,
            $e instanceof PresentationExceptionInterface => \trim($e->getMessage()),
            default => ErrorResponseDTO::DEFAULT_ERROR_MESSAGE,
        } ?: ErrorResponseDTO::DEFAULT_ERROR_MESSAGE;
    }

    private function getCode(\Throwable $e): int
    {
        return match (true) {
            $e instanceof HttpExceptionInterface => $e->getStatusCode(),
            $e instanceof PublicCodeProviderInterface,
            $e instanceof PresentationExceptionInterface => $e->getCode(),
            default => ErrorResponseDTO::DEFAULT_ERROR_CODE + $e->getCode(),
        };
    }
}
