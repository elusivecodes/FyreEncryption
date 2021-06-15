<?php

namespace Fyre\Encryption\Handlers;

use
    Fyre\Encryption\Exceptions\EncryptionException;

use function
    hash_equals,
    openssl_cipher_iv_length,
    openssl_decrypt,
    openssl_encrypt,
    openssl_random_pseudo_bytes,
    serialize,
    unserialize;

use const
    OPENSSL_RAW_DATA;

/**
 * OpenSSLHandler
 */
class OpenSSLHandler extends BaseHandler
{

    protected static array $defaults = [
        'cipher' => 'AES-256-CTR'
    ];

    /**
     * Decrypt data.
     * @param string $data
     * @param string $key
     * @return mixed
     */
    public function decrypt(string $data, string $key)
    {
        $secret = $this->generateSecret($key);

        $hmacLength = $this->getHmacLength();
        $hmacKey = static::substr($data, 0, $hmacLength);
        $data = static::substr($data, $hmacLength);

        $hmacCalc = $this->getHmac($data, $secret);

        if (!hash_equals($hmacKey, $hmacCalc)) {
            throw EncryptionException::forDecryptionFailed();
        }

        $ivSize = $this->getCipherLength();
        $iv = static::substr($data, 0, $ivSize);
        $data = static::substr($data, $ivSize);

        $data = openssl_decrypt($data, $this->config['cipher'], $secret, OPENSSL_RAW_DATA, $iv);

        return unserialize($data);
    }

    /**
     * Encrypt data.
     * @param mixed $data
     * @param string $key
     * @return string
     */
    public function encrypt($data, string $key): string
    {
        $secret = $this->generateSecret($key);
        $ivSize = $this->getCipherLength();
        $iv = $this->generateKey($ivSize);

        $data = serialize($data);

        $data = openssl_encrypt($data, $this->config['cipher'], $secret, OPENSSL_RAW_DATA, $iv);

        if ($data === false) {
            throw EncryptionException::forEncryptionFailed();
        }

        $result = $iv.$data;

        $hmacKey = $this->getHmac($result, $secret);

        return $hmacKey.$result;
    }

    /**
     * Generate an encryption key.
     * @param int|null $length
     * @return string
     */
    public function generateKey(int|null $length = null): string
    {
        $key = openssl_random_pseudo_bytes($length ?? 24, $secure);

        if (!$secure) {
            return $this->generateKey($length);
        }

        return $key;
    }

    /**
     * Get the cipher length.
     * @return int
     */
    protected function getCipherLength(): int
    {
        return openssl_cipher_iv_length($this->config['cipher']);
    }

}
