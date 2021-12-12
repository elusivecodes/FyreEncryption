<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    PHPUnit\Framework\TestCase;

final class OpenSSLTest extends TestCase
{

    use
        EncrypterTest;

    public function testGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertEquals(
            24,
            strlen($key)
        );
    }

    protected function setUp(): void
    {
        Encryption::clear();

        $this->encrypter = Encryption::use('openssl');
    }

}
