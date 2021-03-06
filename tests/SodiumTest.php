<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    Fyre\Encryption\Handlers\SodiumEncrypter,
    PHPUnit\Framework\TestCase;

final class SodiumTest extends TestCase
{

    use
        EncrypterTest;

    public function testGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertSame(
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
            strlen($key)
        );
    }

    protected function setUp(): void
    {
        $this->encrypter = Encryption::load([
            'className' => SodiumEncrypter::class
        ]);
    }

}
