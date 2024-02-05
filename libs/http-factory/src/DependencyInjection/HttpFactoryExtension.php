<?php

declare(strict_types=1);

namespace Local\HttpFactory\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class HttpFactoryExtension extends Extension
{
    /**
     * @var non-empty-string
     */
    private const string PATH_SERVICES = __DIR__ . '/../../resources';

    #[\Override]
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            container: $container,
            locator: new FileLocator(self::PATH_SERVICES),
        );

        $loader->load('services.yaml');
    }
}
