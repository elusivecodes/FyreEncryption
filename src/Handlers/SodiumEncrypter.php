<?php
declare(strict_types=1);

namespace Fyre\Encryption\Handlers;

use Fyre\Encryption\Encrypter;
use Fyre\Encryption\Exceptions\EncryptionException;
use Override;

use function hash_equals;
use function mb_strlen;
use function random_bytes;
use function serialize;
use function sodium_crypto_secretbox;
use function sodium_crypto_secretbox_open;
use function sodium_memzero;
use function sodium_pad;
use function sodium_unpad;
use function unserialize;

use const SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
use const SODIUM_CRYPTO_SECRETBOX_MACBYTES;
use const SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;

/**
 * SodiumEncrypter
 */
class SodiumEncrypter extends Encrypter
{
    protected static array $defaults = [
        'blockSize' => 16,
    ];

    /**
     * Decrypt data.
     *
     * @param string $data The encrypted data.
     * @param string $key The encryption key.
     * @return mixed The decrypted data.
     *
     * @throws EncryptionException if decryption fails.
     */
    #[Override]
    public function decrypt(string $data, string $key): mixed
    {
        if (mb_strlen($data, '8bit') < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            throw EncryptionException::forDecryptionFailed();
        }

        $secret = $this->generateSecret($key, SODIUM_CRYPTO_SECRETBOX_KEYBYTES);

        $hmacLength = (int) $this->getHmacLength();
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
     *
     * @param mixed $data The data to encrypt.
     * @param string $key The encryption key.
     * @return string The encrypted data.
     */
    #[Override]
    public function encrypt(mixed $data, string $key): string
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
     *
     * @param int|null $length The key length.
     * @return string The encryption key.
     */
    #[Override]
    public function generateKey(int|null $length = null): string
    {
        return random_bytes($length ?? SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    }
}
