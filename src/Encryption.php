<?php
declare(strict_types=1);

namespace Fyre\Encryption;

use Fyre\Encryption\Exceptions\EncryptionException;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use Fyre\Encryption\Handlers\SodiumEncrypter;

use function array_key_exists;
use function array_search;
use function class_exists;
use function is_array;

/**
 * Encryption
 */
abstract class Encryption
{
    public const DEFAULT = 'default';

    protected static array $config = [
        'default' => [
            'className' => SodiumEncrypter::class,
        ],
        'openssl' => [
            'className' => OpenSSLEncrypter::class,
        ],
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
     *
     * @param string|null $key The config key.
     */
    public static function getConfig(string|null $key = null): array|null
    {
        if ($key === null) {
            return static::$config;
        }

        return static::$config[$key] ?? null;
    }

    /**
     * Get the key for an encrypter instance.
     *
     * @param Encrypter $encrypter The Encrypter.
     * @return string|null The encrypter key.
     */
    public static function getKey(Encrypter $encrypter): string|null
    {
        return array_search($encrypter, static::$instances, true) ?: null;
    }

    /**
     * Determine if a config exists.
     *
     * @param string $key The config key.
     * @return bool TRUE if the config exists, otherwise FALSE.
     */
    public static function hasConfig(string $key = self::DEFAULT): bool
    {
        return array_key_exists($key, static::$config);
    }

    /**
     * Determine if a handler is loaded.
     *
     * @param string $key The config key.
     * @return bool TRUE if the handler is loaded, otherwise FALSE.
     */
    public static function isLoaded(string $key = self::DEFAULT): bool
    {
        return array_key_exists($key, static::$instances);
    }

    /**
     * Load a handler.
     *
     * @param array $options Options for the handler.
     * @return Encrypter The handler.
     *
     * @throws EncryptionException if the handler is not valid.
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
     *
     * @param array|string $key The config key.
     * @param array|null $options The config options.
     *
     * @throws EncryptionException if the config is not valid.
     */
    public static function setConfig(array|string $key, array|null $options = null): void
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                static::setConfig($k, $v);
            }

            return;
        }

        if ($options === null) {
            throw EncryptionException::forInvalidConfig($key);
        }

        if (array_key_exists($key, static::$config)) {
            throw EncryptionException::forConfigExists($key);
        }

        static::$config[$key] = $options;
    }

    /**
     * Unload a handler.
     *
     * @param string $key The config key.
     * @return bool TRUE if the handler was removed, otherwise FALSE.
     */
    public static function unload(string $key = self::DEFAULT): bool
    {
        if (!array_key_exists($key, static::$config)) {
            return false;
        }

        unset(static::$instances[$key]);
        unset(static::$config[$key]);

        return true;
    }

    /**
     * Load a shared handler instance.
     *
     * @param string $key The config key.
     * @return Encrypter The handler.
     */
    public static function use(string $key = self::DEFAULT): Encrypter
    {
        return static::$instances[$key] ??= static::load(static::$config[$key] ?? []);
    }
}
