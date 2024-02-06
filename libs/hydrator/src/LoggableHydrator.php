<?php

declare(strict_types=1);

namespace Local\Hydrator;

use Psr\Log\LoggerInterface;

final readonly class LoggableHydrator implements HydratorInterface
{
    public function __construct(
        private HydratorInterface $hydrator,
        private LoggerInterface $logger,
    ) {}

    public function hydrate(string $type, mixed $data): mixed
    {
        try {
            $result = $this->hydrator->hydrate($type, $data);

            $this->logger->debug('Hydration {type}', [
                'type' => $type,
                'input' => $data,
                'output' => $result,
            ]);
        } catch (\Throwable $e) {
            $this->logger->debug('Failed hydration {type}', [
                'type' => $type,
                'input' => $data,
                'error' => $e,
            ]);

            throw $e;
        }

        return $result;
    }
}
