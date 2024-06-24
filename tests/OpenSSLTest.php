<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Encryption\Encryption;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use PHPUnit\Framework\TestCase;

use function strlen;

final class OpenSSLTest extends TestCase
{
    use EncrypterTestTrait;

    protected function setUp(): void
    {
        $this->encrypter = Encryption::load([
            'className' => OpenSSLEncrypter::class
        ]);
    }

    public function testGenerateKey(): void
    {
        $key = $this->encrypter->generateKey();

        $this->assertSame(
            24,
            strlen($key)
        );
    }
}
