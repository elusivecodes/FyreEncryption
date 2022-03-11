<?php
declare(strict_types=1);

namespace Fyre\Encryption;

use
    Fyre\Encryption\Exceptions\EncryptionException,
    Fyre\Encryption\Handlers\OpenSSLEncrypter,
    Fyre\Encryption\Handlers\SodiumEncrypter;

use function
    array_key_exists,
    array_search,
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
     * Get the key for an encrypter instance.
     * @param Encrypter $encrypter The Encrypter.
     * @return string|null The encrypter key.
     */
    public static function getKey(Encrypter $encrypter): string|null
    {
        return array_search($encrypter, $this->instances, true) ?: null;
    }

    /**
     * Load a handler.
     * @param array $options Options for the handler.
     * @return Encrypter The handler.
     * @throws EncryptionException if the handler is invalid.
     */
    public static function load(array $options = []): Encrypter
    {
        if (!array_key_exists('className', $options)) {
            throw EncryptionException::forInvalidClass();
        }

        if (!class_exists($options['className'], true)) {
            throw EncryptionException::forInvalidClass($options['className']);
        }

        return new $options['className']($options);
    }

    /**
     * Set handler config.
     * @param string $key The config key.
     * @param array $options The config options.
     */
    public static function setConfig(string $key, array $options): void
    {
        static::$config[$key] = $options;
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
