<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception;

class PresentationException extends \Exception implements
    PresentationExceptionInterface,
    PublicInfoProviderInterface,
    PublicDataProviderInterface
{
    public mixed $data = null;

    public function getData(): mixed
    {
        return $this->data;
    }

    public function withAdditionalData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public static function fromPublicInfo(PublicInfoProviderInterface $info, \Throwable $previous = null): self
    {
        $result = new self(
            message: $info->getMessage(),
            code: $info->getCode(),
            previous: $previous,
        );

        if ($info instanceof PublicDataProviderInterface) {
            $result->withAdditionalData($info);
        }

        return $result;
    }
}
