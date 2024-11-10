<?php
declare(strict_types=1);

namespace Fyre\Encryption\Exceptions;

use RuntimeException;

/**
 * EncryptionException
 */
class EncryptionException extends RuntimeException
{
    public static function forConfigExists(string $key): static
    {
        return new static('Encryption handler config already exists: '.$key);
    }

    public static function forDecryptionFailed(): static
    {
        return new static('Decryption failed');
    }

    public static function forEncryptionFailed(): static
    {
        return new static('Encryption failed');
    }

    public static function forInvalidClass(string $className = ''): static
    {
        return new static('Encryption handler class not found: '.$className);
    }
}
