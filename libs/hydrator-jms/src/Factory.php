<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS;

use Local\Hydrator\ExtractorInterface;
use Local\Hydrator\HydratorInterface;
use Local\Hydrator\JMS\Construction\AdvancedConstructor;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Cache\CacheItemPoolInterface;
use Metadata\Cache\PsrCacheAdapter as MetadataPsrCacheAdapter;
use Local\Hydrator\JMS\Cache\PsrCacheAdapter as LocalPsrCacheAdapter;

final class Factory
{
    private Serializer $serializer;

    public function __construct(SerializerBuilder $builder)
    {
        $builder = $builder
            ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
            ->setObjectConstructor(new AdvancedConstructor());

        //
        // Available since JMS 3.20+
        //
        if (\method_exists($builder, 'enableEnumSupport')) {
            $builder->enableEnumSupport(true);
        }

        $this->serializer = $builder->build();
    }

    /**
     * @param array<class-string, non-empty-string> $configs Sets a map of
     *        namespace prefixes to directories.
     *
     * @psalm-suppress InternalClass
     * @psalm-suppress TooManyArguments
     */
    public static function create(
        array $configs = [],
        bool $debug = false,
        CacheItemPoolInterface $cache = null,
    ): self {
        $builder = SerializerBuilder::create();
        $builder->setDebug($debug);
        $builder->setMetadataDirs($configs);

        if ($cache !== null) {
            // Add support of jms/metadata v2.2
            $driver = \class_exists(MetadataPsrCacheAdapter::class)
                ? new MetadataPsrCacheAdapter('jms_hydrator', $cache)
                : new LocalPsrCacheAdapter('jms_hydrator', $cache);

            $builder->setMetadataCache($driver);
        }

        return new self($builder);
    }

    /**
     * @api
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    /**
     * @api
     */
    public function createExtractor(): ExtractorInterface
    {
        return new Extractor($this->serializer);
    }

    /**
     * @api
     */
    public function createHydrator(): HydratorInterface
    {
        return new Hydrator($this->serializer);
    }
}
