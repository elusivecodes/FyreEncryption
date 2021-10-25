<?php
declare(strict_types=1);

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

    public static function forInvalidClass(string $className = '')
    {
        return new static('Encryption handler class not found: '.$className);
    }

}
