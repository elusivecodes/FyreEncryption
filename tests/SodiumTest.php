<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    PHPUnit\Framework\TestCase;

final class SodiumTest extends TestCase
{

    use
        EncrypterTest;

    public function testSodiumGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertEquals(
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
            strlen($key)
        );
    }

    protected function setUp(): void
    {
        Encryption::clear();

        $this->encrypter = Encryption::use();
    }

}
