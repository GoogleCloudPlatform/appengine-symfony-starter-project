<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppEngine\Cache\Adapter;

use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Traits\MemcachedTrait;

class MemcachedAdapter extends AbstractAdapter
{
    use MemcachedTrait;

    protected $maxIdLength = 250;

    public function __construct(\Memcached $client, $namespace = '', $defaultLifetime = 0)
    {
        $this->init($client, $namespace, $defaultLifetime);
    }

    public static function isSupported()
    {
        // we have to override this for App engine because the Memcache Proxy does not
        // support a version number.
        // @see Symfony\Component\Cache\Traits\MemcacheTrait::isSupported
        return extension_loaded('memcached');
    }

    private function init(\Memcached $client, $namespace, $defaultLifetime)
    {
        if (!static::isSupported()) {
            throw new CacheException('Memcached >= 2.2.0 is required');
        }
        // We have to override this for App engine because the OPT_SERIALIZER option
        // is not set, which throws an exception in the built-in handler.
        // @see Symfony\Component\Cache\Traits\MemcacheTrait::init
        $this->maxIdLength -= strlen($client->getOption(\Memcached::OPT_PREFIX_KEY));

        parent::__construct($namespace, $defaultLifetime);
        $this->client = $client;
    }
}
