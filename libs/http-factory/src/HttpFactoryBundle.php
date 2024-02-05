<?php

declare(strict_types=1);

namespace Bsg\HttpFactory;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Bsg\HttpFactory\DependencyInjection\HttpFactoryExtension;

final class HttpFactoryBundle extends AbstractBundle
{
    public function getContainerExtension(): HttpFactoryExtension
    {
        return new HttpFactoryExtension();
    }
}
