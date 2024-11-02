<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Container\Container;
use Fyre\Encryption\EncryptionManager;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use PHPUnit\Framework\TestCase;

use function strlen;

final class OpenSSLTest extends TestCase
{
    use EncrypterTestTrait;

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
        $this->encrypter = Container::getInstance()
            ->use(EncryptionManager::class)
            ->build([
                'className' => OpenSSLEncrypter::class,
            ]);
    }
}
