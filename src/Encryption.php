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
    class_exists,
    is_array;

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
     * Clear all instances and configs.
     */
    public static function clear(): void
    {
        static::$config = [];
        static::$instances = [];
    }

    /**
     * Get the handler config.
     * @param string|null $key The config key.
     * @return array|null
     */
    public static function getConfig(string|null $key = null): array|null
    {
        if (!$key) {
            return static::$config;
        }

        return static::$config[$key] ?? null;
    }

    /**
     * Get the key for an encrypter instance.
     * @param Encrypter $encrypter The Encrypter.
     * @return string|null The encrypter key.
     */
    public static function getKey(Encrypter $encrypter): string|null
    {
        return array_search($encrypter, static::$instances, true) ?: null;
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
     * @param string|array $key The config key.
     * @param array|null $options The config options.
     * @throws EncryptionException if the config is invalid.
     */
    public static function setConfig(string|array $key, array|null $options = null): void
    {
        if (is_array($key)) {
            foreach ($key AS $k => $value) {
                static::setConfig($k, $value);
            }

            return;
        }

        if (!is_array($options)) {
            throw EncryptionException::forInvalidConfig($key);
        }

        if (array_key_exists($key, static::$config)) {
            throw EncryptionException::forConfigExists($key);
        }

        static::$config[$key] = $options;
    }

    /**
     * Unload a handler.
     * @param string $key The config key.
     */
    public static function unload(string $key = 'default'): void
    {
        unset(static::$instances[$key]);
        unset(static::$config[$key]);
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
