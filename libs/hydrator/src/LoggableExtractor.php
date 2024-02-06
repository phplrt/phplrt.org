<?php

declare(strict_types=1);

namespace Local\Hydrator;

use Psr\Log\LoggerInterface;

final readonly class LoggableExtractor implements ExtractorInterface
{
    public function __construct(
        private ExtractorInterface $extractor,
        private LoggerInterface $logger,
    ) {}

    public function extract(mixed $data): mixed
    {
        try {
            $result = $this->extractor->extract($data);

            $this->logger->debug('Extraction {type}', [
                'type' => \get_debug_type($data),
                'input' => $data,
                'output' => $result,
            ]);
        } catch (\Throwable $e) {
            $this->logger->debug('Failed extraction {type}', [
                'type' => \get_debug_type($data),
                'input' => $data,
                'error' => $e,
            ]);

            throw $e;
        }

        return $result;
    }
}
