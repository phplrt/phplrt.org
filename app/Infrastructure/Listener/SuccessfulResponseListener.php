<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Presentation\Response\DTO\SuccessResponseDTO;
use Local\HttpFactory\ResponseEncoderFactoryInterface;
use Local\HttpFactory\ResponseEncoderInterface;
use Local\Hydrator\Exception\HydratorExceptionInterface;
use Local\Hydrator\Exception\MappingExceptionInterface;
use Local\Hydrator\ExtractorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final readonly class SuccessfulResponseListener
{
    public function __construct(
        private ExtractorInterface $extractor,
        private ResponseEncoderFactoryInterface $factory,
        private ResponseEncoderInterface $default,
    ) {}

    public function __invoke(ViewEvent $event): void
    {
        /** @var mixed $result */
        $result = $event->getControllerResult();

        if ($this->isSerializable($result)) {
            /** @var mixed $result */
            $this->modify($result, $event);
        }
    }

    private function isSerializable(mixed $result): bool
    {
        return !$result instanceof Response;
    }

    /**
     * @throws HydratorExceptionInterface
     * @throws MappingExceptionInterface
     */
    private function modify(mixed $result, ViewEvent $event): void
    {
        $encoder = $this->factory->createEncoder($event->getRequest()) ?? $this->default;

        if (!$result instanceof SuccessResponseDTO) {
            $result = new SuccessResponseDTO($result);
        }

        $event->setResponse($encoder->encode(
            data: $this->extractor->extract($result),
            code: Response::HTTP_OK,
        ));

        $event->stopPropagation();
    }
}
