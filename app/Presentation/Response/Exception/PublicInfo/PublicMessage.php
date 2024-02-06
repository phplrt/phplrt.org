<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception\PublicInfo;

use App\Presentation\Response\DTO\ErrorResponseDTO;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final readonly class PublicMessage
{
    /**
     * @var non-empty-string
     */
    public const DEFAULT_PUBLIC_ERROR_MESSAGE = ErrorResponseDTO::DEFAULT_ERROR_MESSAGE;

    /**
     * @param non-empty-string $message
     */
    public function __construct(
        public string $message = self::DEFAULT_PUBLIC_ERROR_MESSAGE,
    ) {}
}
