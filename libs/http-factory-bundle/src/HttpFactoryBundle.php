<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Local\HttpFactory\DependencyInjection\HttpFactoryExtension;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class HttpFactoryBundle extends AbstractBundle
{
    public function getContainerExtension(): HttpFactoryExtension
    {
        return new HttpFactoryExtension();
    }
}
