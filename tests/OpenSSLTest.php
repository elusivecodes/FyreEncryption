<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    Fyre\Encryption\Exceptions\EncryptionException,
    PHPUnit\Framework\TestCase;

final class OpenSSLTest extends TestCase
{

    public function testOpenSSLEncryption(): void
    {
        $text = 'This is a sample string';
        $key = 'abc123';

        $encrypted = $this->handler->encrypt($text, $key);
        $decrypted = $this->handler->decrypt($encrypted, $key);

        $this->assertNotEquals($text, $encrypted);
        $this->assertEquals($text, $decrypted);
    }

    public function testOpenSSLEncryptionArray(): void
    {
        $data = [1, 2, 3];
        $key = 'abc123';

        $encrypted = $this->handler->encrypt($data, $key);
        $decrypted = $this->handler->decrypt($encrypted, $key);

        $this->assertNotEquals($data, $encrypted);
        $this->assertEquals($data, $decrypted);
    }

    public function testOpenSSLInvalidKey(): void
    {
        $this->expectException(EncryptionException::class);

        $encrypted = $this->handler->encrypt('This is a sample string', 'abc123');
        $this->handler->decrypt($encrypted, 'invalid');
    }

    public function testOpenSSLGenerateKey(): void
    {
        $key = $this->handler->generateKey();

        $this->assertEquals(
            24,
            strlen($key)
        );
    }

    public function testOpenSSLGenerateKeyRandom(): void
    {
        $key1 = $this->handler->generateKey();
        $key2 = $this->handler->generateKey();

        $this->assertNotEquals($key1, $key2);
    }

    protected function setUp(): void
    {
        Encryption::clear();

        $this->handler = Encryption::use('openssl');
    }

}
