<?php

namespace Fyre;

use
    Fyre\Encryption\Exceptions\EncryptionException,
    Fyre\Encryption\Handlers\BaseHandler,
    Fyre\Encryption\Handlers\OpenSSLHandler,
    Fyre\Encryption\Handlers\SodiumHandler;

use function
    array_key_exists,
    class_exists;

/**
 * Encryption
 */
abstract class Encryption
{

    protected static string $defaultHandler = 'sodium';

    protected static array $handlers = [
        'openssl' => OpenSSLHandler::class,
        'sodium' => SodiumHandler::class
    ];

    protected static array $instances = [];

    /**
     * Add a handler.
     * @param string $handler
     * @param string $className
     */
    public static function addHandler(string $handler, string $className): void
    {
        static::$handlers[$handler] = $className;
    }

    /**
     * Clear instances.
     */
    public static function clear(): void
    {
        static::$instances = [];
    }

    /**
     * Load a handler.
     * @param array $config
     * @return BaseHandler
     */
    public static function load(array $config = []): BaseHandler
    {
        $handler = $config['handler'] ?? static::$defaultHandler;

        unset($config['handler']);

        if (!array_key_exists($handler, static::$handlers)) {
            throw EncryptionException::forInvalidHandler($handler);
        }

        $className = static::$handlers[$handler];

        if (!class_exists($className, true)) {
            throw EncryptionException::forInvalidClass($className);
        }

        return new $className($config);
    }

    /**
     * Set the default handler.
     * @param string $handler
     */
    public static function setDefaultHandler(string $handler): void
    {
        static::$defaultHandler = $handler;
    }

    /**
     * Load a shared handler instance.
     * @param string|null $key
     * @param array $config
     * @return BaseHandler
     */
    public static function &use(string|null $key = null, array $config = []): BaseHandler
    {
        $key ??= 'default';

        static::$instances[$key] ??= static::load($config);

        return static::$instances[$key];
    }

}
