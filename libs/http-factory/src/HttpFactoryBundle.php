<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Local\HttpFactory\DependencyInjection\HttpFactoryExtension;

final class HttpFactoryBundle extends AbstractBundle
{
    public function getContainerExtension(): HttpFactoryExtension
    {
        return new HttpFactoryExtension();
    }
}
