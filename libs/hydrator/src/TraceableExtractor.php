<?php

declare(strict_types=1);

namespace Local\Hydrator;

use Symfony\Component\Stopwatch\Stopwatch;

final readonly class TraceableExtractor implements ExtractorInterface
{
    public function __construct(
        private ExtractorInterface $extractor,
        private ?Stopwatch $stopwatch = null,
    ) {}

    public function extract(mixed $data): mixed
    {
        $type = \get_debug_type($data);
        $event = $this->stopwatch?->start("Extract $type", 'hydrator');

        try {
            $result = $this->extractor->extract($data);
        } catch (\Throwable $e) {
            $event?->stop();

            throw $e;
        }

        $event?->stop();

        return $result;
    }
}
