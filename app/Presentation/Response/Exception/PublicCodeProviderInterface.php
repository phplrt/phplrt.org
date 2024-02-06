<?php

declare(strict_types=1);

namespace App\Presentation\Response\Exception;

interface PublicCodeProviderInterface
{
    /**
     * @return int<0, max>
     */
    #[\ReturnTypeWillChange]
    public function getCode();
}
