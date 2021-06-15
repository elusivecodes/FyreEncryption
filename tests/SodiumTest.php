<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption,
    Fyre\Encryption\Exceptions\EncryptionException,
    PHPUnit\Framework\TestCase;

final class SodiumTest extends TestCase
{

    public function testSodiumEncryption(): void
    {
        $text = 'This is a sample string';
        $key = $this->handler->generateKey();

        $encrypted = $this->handler->encrypt($text, $key);
        $decrypted = $this->handler->decrypt($encrypted, $key);

        $this->assertNotEquals($text, $encrypted);
        $this->assertEquals($text, $decrypted);
    }

    public function testSodiumEncryptionArray(): void
    {
        $data = [1, 2, 3];
        $key = $this->handler->generateKey();

        $encrypted = $this->handler->encrypt($data, $key);
        $decrypted = $this->handler->decrypt($encrypted, $key);

        $this->assertNotEquals($data, $encrypted);
        $this->assertEquals($data, $decrypted);
    }

    public function testSodiumInvalidKey(): void
    {
        $this->expectException(EncryptionException::class);

        $key1 = $this->handler->generateKey();
        $key2 = $this->handler->generateKey();

        $encrypted = $this->handler->encrypt('This is a sample string', $key1);
        $this->handler->decrypt($encrypted, $key2);
    }

    public function testSodiumGenerateKey(): void
    {
        $key = $this->handler->generateKey();

        $this->assertEquals(
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
            strlen($key)
        );
    }

    public function testSodiumGenerateKeyRandom(): void
    {
        $key1 = $this->handler->generateKey();
        $key2 = $this->handler->generateKey();

        $this->assertNotEquals($key1, $key2);
    }

    protected function setUp(): void
    {
        Encryption::clear();

        $this->handler = Encryption::load([
            'handler' => 'sodium'
        ]);
    }

}
