<?php
declare(strict_types=1);

namespace Fyre\Encryption;

use
    Fyre\Encryption\Exceptions\EncryptionException,
    Fyre\Encryption\Handlers\OpenSSLEncrypter,
    Fyre\Encryption\Handlers\SodiumEncrypter;

use function
    array_key_exists,
    class_exists;

/**
 * Encryption
 */
abstract class Encryption
{

    protected static array $config = [
        'default' => [
            'className' => SodiumEncrypter::class
        ],
        'openssl' => [
            'className' => OpenSSLEncrypter::class
        ]
    ];

    protected static array $instances = [];

    /**
     * Clear instances.
     */
    public static function clear(): void
    {
        static::$instances = [];
    }

    /**
     * Load a handler.
     * @param array $config Options for the handler.
     * @return Encrypter The handler.
     * @throws EncryptionException if the handler is invalid.
     */
    public static function load(array $config = []): Encrypter
    {
        if (!array_key_exists('className', $config)) {
            throw EncryptionException::forInvalidClass();
        }

        if (!class_exists($config['className'], true)) {
            throw EncryptionException::forInvalidClass($config['className']);
        }

        return new $config['className']($config);
    }

    /**
     * Set handler config.
     * @param string $key The config key.
     * @param array $config The config options.
     */
    public static function setConfig(string $key, array $config): void
    {
        static::$config[$key] = $config;
    }

    /**
     * Load a shared handler instance.
     * @param string $key The config key.
     * @return Encrypter The handler.
     */
    public static function use(string $key = 'default'): Encrypter
    {
        return static::$instances[$key] ??= static::load(static::$config[$key] ?? []);
    }

}
