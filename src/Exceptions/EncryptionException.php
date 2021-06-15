<?php

namespace Fyre\Encryption\Exceptions;

use
    RunTimeException;

/**
 * EncryptionException
 */
class EncryptionException extends RunTimeException
{

    public static function forDecryptionFailed()
    {
        return new static('Decryption failed');
    }

    public static function forEncryptionFailed()
    {
        return new static('Encryption failed');
    }

    public static function forInvalidClass(string $className)
    {
        return new static('Class not found: '.$className);
    }

    public static function forInvalidHandler(string $handler)
    {
        return new static('Invalid handler: '.$handler);
    }

}
