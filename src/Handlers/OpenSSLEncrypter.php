<?php
declare(strict_types=1);

namespace Fyre\Encryption\Handlers;

use Fyre\Encryption\Encrypter;
use Fyre\Encryption\Exceptions\EncryptionException;

use const OPENSSL_RAW_DATA;

use function hash_equals;
use function openssl_cipher_iv_length;
use function openssl_decrypt;
use function openssl_encrypt;
use function openssl_random_pseudo_bytes;
use function serialize;
use function unserialize;

/**
 * OpenSSLEncrypter
 */
class OpenSSLEncrypter extends Encrypter
{

    protected static array $defaults = [
        'cipher' => 'AES-256-CTR'
    ];

    /**
     * Decrypt data.
     * @param string $data The encrypted data.
     * @param string $key The encryption key.
     * @return mixed The decrypted data.
     * @throws EncryptionException if decryption fails.
     */
    public function decrypt(string $data, string $key): mixed
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
     * @param mixed $data The data to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     * @throws EncryptionException if encryption fails.
     */
    public function encrypt(mixed $data, string $key): string
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
     * @param int|null $length The key length.
     * @return string The encryption key.
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
     * @return int The cipher length.
     */
    protected function getCipherLength(): int
    {
        return openssl_cipher_iv_length($this->config['cipher']);
    }

}
