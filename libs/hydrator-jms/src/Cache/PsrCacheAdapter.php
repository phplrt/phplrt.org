<?php

declare(strict_types=1);

namespace Local\Hydrator\JMS\Cache;

use Metadata\Cache\CacheInterface;
use Metadata\ClassMetadata;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Metadata cache polyfill for JMS v1.0 ... v3.20+ compatibility.
 *
 * NOTE: Not compatible with JMS v2.2 or greater. For JMS 2.2+ please use
 *       builtin class instead.
 *
 * @internal This is an internal library class, please do not use it in your code.
 * @psalm-internal Local\Hydrator\JMS
 *
 * @psalm-suppress all : JMS 2.2 or older compatibility class.
 */
class PsrCacheAdapter implements CacheInterface
{
    private string $prefix;
    private CacheItemPoolInterface $pool;
    private ?CacheItemInterface $lastItem = null;

    public function __construct(string$prefix, CacheItemPoolInterface $pool)
    {
        $this->prefix = $prefix;
        $this->pool = $pool;
    }

    public function loadClassMetadataFromCache(\ReflectionClass $class)
    {
        $this->lastItem = $this->pool->getItem($this->key($class->name));

        return $this->lastItem->get();
    }

    public function putClassMetadataInCache(ClassMetadata $metadata)
    {
        $key = $this->key($metadata->reflection->name);

        if ($this->lastItem === null || $this->lastItem->getKey() !== $key) {
            $this->lastItem = $this->pool->getItem($key);
        }

        $this->pool->save($this->lastItem->set($metadata));
    }

    public function evictClassMetadataFromCache(\ReflectionClass $class)
    {
        $this->pool->deleteItem($this->key($class->name));
    }

    private function key(string $key): string
    {
        return \strtr($this->prefix . $key, '\\', '.');
    }
}
