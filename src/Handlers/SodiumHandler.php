<?php

namespace Fyre\Encryption\Handlers;

use
    Fyre\Encryption\Exceptions\EncryptionException;

use function
    hash_equals,
    mb_strlen,
    random_bytes,
    serialize,
    sodium_crypto_secretbox,
    sodium_crypto_secretbox_open,
    sodium_memzero,
    sodium_pad,
    sodium_unpad,
    unserialize;

use const
    SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
    SODIUM_CRYPTO_SECRETBOX_MACBYTES,
    SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

/**
 * SodiumHandler
 */
class SodiumHandler extends BaseHandler
{

    protected static array $defaults = [
        'blockSize' => 16
    ];

    /**
     * Decrypt data.
     * @param string $data The encrypted data.
     * @param string $key The encryption key.
     * @return mixed The decrypted data.
     * @throws EncryptionException if decryption fails.
     */
    public function decrypt(string $data, string $key)
    {
        if (mb_strlen($data, '8bit') < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            throw EncryptionException::forDecryptionFailed();
        }

        $secret = $this->generateSecret($key, SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

        $hmacLength = $this->getHmacLength();
        $hmacKey = static::substr($data, 0, $hmacLength);
        $data = static::substr($data, $hmacLength);

        $hmacCalc = $this->getHmac($data, $secret);

        if (!hash_equals($hmacKey, $hmacCalc)) {
            throw EncryptionException::forDecryptionFailed();
        }

        $nonce = static::substr($data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = static::substr($data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $data = sodium_crypto_secretbox_open($cipher, $nonce, $secret);

        if ($data === false) {
            throw EncryptionException::forDecryptionFailed();
        }

        $data = sodium_unpad($data, $this->config['blockSize']);

        sodium_memzero($cipher);
        sodium_memzero($key);

        return unserialize($data);
    }

    /**
     * Encrypt data.
     * @param mixed $data The data to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     */
    public function encrypt($data, string $key): string
    {
        $secret = $this->generateSecret($key, SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $nonce = $this->generateKey(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $data = serialize($data);

        $data = sodium_pad($data, $this->config['blockSize']);

        $cypher = sodium_crypto_secretbox($data, $nonce, $secret);

        sodium_memzero($data);
        sodium_memzero($key);

        $result = $nonce.$cypher;

        $hmacKey = $this->getHmac($result, $secret);

        return $hmacKey.$result;
    }

    /**
     * Generate an encryption key.
     * @param int|null $length The key length.
     * @return string The encryption key.
     */
    public function generateKey(int|null $length = null): string
    {
        return random_bytes($length ?? SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    }

}
