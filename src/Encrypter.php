<?php
declare(strict_types=1);

namespace Fyre;

use function
    array_merge_recursive,
    hash_hkdf,
    hash_hmac,
    mb_substr;

/**
 * Encrypter
 */
abstract class Encrypter
{

    protected static array $defaults = [
        'digest' => 'SHA512'
    ];

    protected array $config;

    /**
     * New Encrypter constructor.
     * @param array $config Options for the handler.
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge_recursive(static::$defaults, self::$defaults, $config);
    }

    /**
     * Decrypt data.
     * @param string $data The encrypted data.
     * @param string $key The encryption key.
     * @return mixed The decrypted data.
     */
    abstract public function decrypt(string $data, string $key);

    /**
     * Encrypt data.
     * @param mixed $data The data to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     */
    abstract public function encrypt($data, string $key): string;

    /**
     * Generate an encryption key.
     * @param int|null $length The key length.
     * @return string The encryption key.
     */
    abstract public function generateKey(int|null $length = null): string;

    /**
     * Get the config.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Generate a secret key.
     * @param string $key The encryption key.
     * @param int $length The key length.
     * @return string The secret key.
     */
    protected function generateSecret(string $key, int $length = 0): string
    {
        return hash_hkdf($this->config['digest'], $key, $length);
    }

    /**
     * Get the HMAC.
     * @param string $data The data to hash.
     * @param string $secret The secret key.
     * @return string The hash value.
     */
    protected function getHmac(string $data, string $secret): string
    {
        return hash_hmac($this->config['digest'], $data, $secret, true);
    }

    /**
     * Get the HMAC length.
     * @return string The HMAC length.
     */
    protected function getHmacLength(): int
    {
        return static::substr($this->config['digest'], 3) / 8;
    }

    /**
     * Multi-byte substr.
     * @param string $string The input string.
     * @param int $start The starting offset.
     * @param int|null $length The maximum length to return.
     * @return string The sliced string.
     */
    protected static function substr(string $string, int $start, int|null $length = null): string
    {
        return mb_substr($string, $start, $length, '8bit');
    }

}
