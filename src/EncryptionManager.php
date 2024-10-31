<?php
declare(strict_types=1);

namespace Fyre\Encryption;

use Fyre\Encryption\Exceptions\EncryptionException;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use Fyre\Encryption\Handlers\SodiumEncrypter;

use function array_key_exists;
use function array_replace;
use function class_exists;
use function is_subclass_of;

/**
 * EncryptionManager
 */
class EncryptionManager
{
    public const DEFAULT = 'default';

    protected static array $defaults = [
        'default' => [
            'className' => SodiumEncrypter::class,
        ],
        'openssl' => [
            'className' => OpenSSLEncrypter::class,
        ],
    ];

    protected array $config = [];

    protected array $instances = [];

    /**
     * New EncryptionManager constructor.
     *
     * @param array $config The EncryptionManager config.
     */
    public function __construct(array $config = [])
    {
        $config = array_replace(static::$defaults, $config);

        foreach ($config as $key => $options) {
            $this->setConfig($key, $options);
        }
    }

    /**
     * Build a handler.
     *
     * @param array $options Options for the handler.
     * @return Encrypter The handler.
     *
     * @throws EncryptionException if the handler is not valid.
     */
    public function build(array $options = []): Encrypter
    {
        if (!array_key_exists('className', $options)) {
            throw EncryptionException::forInvalidClass();
        }

        if (!class_exists($options['className'], true) || !is_subclass_of($options['className'], Encrypter::class)) {
            throw EncryptionException::forInvalidClass($options['className']);
        }

        return new $options['className']($options);
    }

    /**
     * Clear all instances and configs.
     */
    public function clear(): void
    {
        $this->config = [];
        $this->instances = [];
    }

    /**
     * Get the handler config.
     *
     * @param string|null $key The config key.
     */
    public function getConfig(string|null $key = null): array|null
    {
        if ($key === null) {
            return $this->config;
        }

        return $this->config[$key] ?? null;
    }

    /**
     * Determine whether a config exists.
     *
     * @param string $key The config key.
     * @return bool TRUE if the config exists, otherwise FALSE.
     */
    public function hasConfig(string $key = self::DEFAULT): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Determine whether a handler is loaded.
     *
     * @param string $key The config key.
     * @return bool TRUE if the handler is loaded, otherwise FALSE.
     */
    public function isLoaded(string $key = self::DEFAULT): bool
    {
        return array_key_exists($key, $this->instances);
    }

    /**
     * Set handler config.
     *
     * @param string $key The config key.
     * @param array $options The config options.
     * @return static The Encryption Manager.
     *
     * @throws EncryptionException if the config is not valid.
     */
    public function setConfig(string $key, array $options): static
    {
        if (array_key_exists($key, $this->config)) {
            throw EncryptionException::forConfigExists($key);
        }

        $this->config[$key] = $options;

        return $this;
    }

    /**
     * Unload a handler.
     *
     * @param string $key The config key.
     * @return static The Encryption Manager.
     */
    public function unload(string $key = self::DEFAULT): static
    {
        unset($this->instances[$key]);
        unset($this->config[$key]);

        return $this;
    }

    /**
     * Load a shared handler instance.
     *
     * @param string $key The config key.
     * @return Encrypter The handler.
     */
    public function use(string $key = self::DEFAULT): Encrypter
    {
        return $this->instances[$key] ??= $this->build($this->config[$key] ?? []);
    }
}
