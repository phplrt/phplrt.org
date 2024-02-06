<?php

declare(strict_types=1);

namespace Local\Hydrator;

use Symfony\Component\Stopwatch\Stopwatch;

final readonly class TraceableHydrator implements HydratorInterface
{
    public function __construct(
        private HydratorInterface $hydrator,
        private ?Stopwatch $stopwatch = null,
    ) {}

    public function hydrate(string $type, mixed $data): mixed
    {
        $event = $this->stopwatch?->start("Hydrate $type", 'hydrator');

        try {
            $result = $this->hydrator->hydrate($type, $data);
        } catch (\Throwable $e) {
            $event?->stop();

            throw $e;
        }

        $event?->stop();

        return $result;
    }
}
