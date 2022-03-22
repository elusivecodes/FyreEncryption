<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    Fyre\Encryption\Handlers\OpenSSLEncrypter,
    PHPUnit\Framework\TestCase;

final class OpenSSLTest extends TestCase
{

    use
        EncrypterTest;

    public function testGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertSame(
            24,
            strlen($key)
        );
    }

    protected function setUp(): void
    {
        $this->encrypter = Encryption::load([
            'className' => OpenSSLEncrypter::class
        ]);
    }

}
