<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Encryption\Encryption;
use Fyre\Encryption\Handlers\SodiumEncrypter;
use PHPUnit\Framework\TestCase;

use function strlen;

use const SODIUM_CRYPTO_SECRETBOX_KEYBYTES;

final class SodiumTest extends TestCase
{
    use EncrypterTestTrait;

    protected function setUp(): void
    {
        $this->encrypter = Encryption::load([
            'className' => SodiumEncrypter::class
        ]);
    }

    public function testGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertSame(
            SODIUM_CRYPTO_SECRETBOX_KEYBYTES,
            strlen($key)
        );
    }
}
