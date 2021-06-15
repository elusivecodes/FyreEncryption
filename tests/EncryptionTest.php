<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption,
    Fyre\Encryption\Exceptions\EncryptionException,
    Fyre\Encryption\Handlers\OpenSSLHandler,
    Fyre\Encryption\Handlers\SodiumHandler,
    PHPUnit\Framework\TestCase,
    Tests\Mock\MockHandler;

final class EncryptionTest extends TestCase
{

    public function testEncryptionLoad(): void
    {
        $this->assertInstanceOf(
            SodiumHandler::class,
            Encryption::load()
        );
    }

    public function testEncryptionLoadHandler(): void
    {
        $this->assertInstanceOf(
            OpenSSLHandler::class,
            Encryption::load([
                'handler' => 'openssl'
            ])
        );
    }

    public function testEncryptionLoadInvalidHandler(): void
    {
        $this->expectException(EncryptionException::class);

        Encryption::load([
            'handler' => 'invalid'
        ]);
    }

    public function testEncryptionUse(): void
    {
        $handler1 = Encryption::use();
        $handler2 = Encryption::use();

        $this->assertSame($handler1, $handler2);

        $this->assertInstanceOf(
            SodiumHandler::class,
            $handler1
        );
    }

    public function testEncryptionUseKey(): void
    {
        $handler1 = Encryption::use('sodium');
        $handler2 = Encryption::use('test');

        $this->assertNotSame($handler1, $handler2);

        $this->assertInstanceOf(
            SodiumHandler::class,
            $handler1
        );
    }

    public function testEncryptionUseHandler(): void
    {
        $this->assertInstanceOf(
            OpenSSLHandler::class,
            Encryption::use(null, [
                'handler' => 'openssl'
            ])
        );
    }

    public function testEncryptionUseInvalidHandler(): void
    {
        $this->expectException(EncryptionException::class);

        Encryption::use(null, [
            'handler' => 'invalid'
        ]);
    }

    public function testEncryptionAddHandler(): void
    {
        Encryption::addHandler('mock', MockHandler::class);

        $this->assertInstanceOf(
            MockHandler::class,
            Encryption::use(null, [
                'handler' => 'mock'
            ])
        );
    }

    public function testEncryptionSetDefaultHandler(): void
    {
        Encryption::addHandler('mock', MockHandler::class);
        Encryption::setDefaultHandler('mock');

        $this->assertInstanceOf(
            MockHandler::class,
            Encryption::use()
        );
    }

    protected function setUp(): void
    {
        Encryption::clear();
    }

}
