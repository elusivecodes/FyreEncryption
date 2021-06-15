<?php

namespace Fyre\Encryption\Handlers;

use function
    array_merge,
    hash_hkdf,
    hash_hmac,
    mb_substr;

/**
 * BaseHandler
 */
abstract class BaseHandler
{

    protected static array $defaults = [
        'digest' => 'SHA512'
    ];

    protected $config;

    /**
     * New BaseHandler constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(self::$defaults, static::$defaults, $config);
    }

    /**
     * Decrypt data.
     * @param string $data
     * @param string $key
     * @return mixed
     */
    abstract public function decrypt(string $data, string $key);

    /**
     * Encrypt data.
     * @param mixed $data
     * @param string $key
     * @return string
     */
    abstract public function encrypt($data, string $key): string;

    /**
     * Generate an encryption key.
     * @param int|null $length
     * @return string
     */
    abstract public function generateKey(int|null $length = null): string;

    /**
     * Generate a secret key.
     * @param string $key
     * @param int|null $length
     * @return string
     */
    protected function generateSecret(string $key, int|null $length = null): string
    {
        return hash_hkdf($this->config['digest'], $key, $length);
    }

    /**
     * Get the HMAC.
     * @param string $data
     * @param string $secret
     * @return string
     */
    protected function getHmac(string $data, string $secret): string
    {
        return hash_hmac($this->config['digest'], $data, $secret, true);
    }

    /**
     * Get the HMAC length.
     * @return string
     */
    protected function getHmacLength(): string
    {
        return static::substr($this->config['digest'], 3) / 8;
    }

    /**
     * Multi-byte substr.
     * @param string $string
     * @param int $start
     * @param int|null $length
     * @return string
     */
    protected static function substr(string $string, int $start, int|null $length = null): string
    {
        return mb_substr($string, $start, $length, '8bit');
    }

}
