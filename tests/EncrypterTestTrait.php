<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Encryption\Encrypter;
use Fyre\Encryption\Exceptions\EncryptionException;

trait EncrypterTestTrait
{
    protected Encrypter $encrypter;

    public function testEncryption(): void
    {
        $text = 'This is a sample string';
        $key = 'abc123';

        $encrypted = $this->encrypter->encrypt($text, $key);
        $decrypted = $this->encrypter->decrypt($encrypted, $key);

        $this->assertNotSame($text, $encrypted);
        $this->assertSame($text, $decrypted);
    }

    public function testEncryptionArray(): void
    {
        $data = [1, 2, 3];
        $key = 'abc123';

        $encrypted = $this->encrypter->encrypt($data, $key);
        $decrypted = $this->encrypter->decrypt($encrypted, $key);

        $this->assertNotSame($data, $encrypted);
        $this->assertSame($data, $decrypted);
    }

    public function testGenerateKeyRandom(): void
    {
        $key1 = $this->encrypter->generateKey();
        $key2 = $this->encrypter->generateKey();

        $this->assertNotSame($key1, $key2);
    }

    public function testInvalidKey(): void
    {
        $this->expectException(EncryptionException::class);

        $encrypted = $this->encrypter->encrypt('This is a sample string', 'abc123');
        $this->encrypter->decrypt($encrypted, 'invalid');
    }
}
