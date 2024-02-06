<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception;

interface PublicMessageProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getMessage(): string;
}
