<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encrypter,
    Fyre\Encryption\Exceptions\EncryptionException;

trait EncrypterTest
{

    protected Encrypter $encrypter;

    public function testEncrypterEncryption(): void
    {
        $text = 'This is a sample string';
        $key = 'abc123';

        $encrypted = $this->encrypter->encrypt($text, $key);
        $decrypted = $this->encrypter->decrypt($encrypted, $key);

        $this->assertNotEquals($text, $encrypted);
        $this->assertEquals($text, $decrypted);
    }

    public function testEncrypterEncryptionArray(): void
    {
        $data = [1, 2, 3];
        $key = 'abc123';

        $encrypted = $this->encrypter->encrypt($data, $key);
        $decrypted = $this->encrypter->decrypt($encrypted, $key);

        $this->assertNotEquals($data, $encrypted);
        $this->assertEquals($data, $decrypted);
    }

    public function testEncrypterInvalidKey(): void
    {
        $this->expectException(EncryptionException::class);

        $encrypted = $this->encrypter->encrypt('This is a sample string', 'abc123');
        $this->encrypter->decrypt($encrypted, 'invalid');
    }

    public function testEncrypterGenerateKeyRandom(): void
    {
        $key1 = $this->encrypter->generateKey();
        $key2 = $this->encrypter->generateKey();

        $this->assertNotEquals($key1, $key2);
    }

}
