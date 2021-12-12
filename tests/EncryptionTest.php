<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Encryption\Encryption,
    Fyre\Encryption\Exceptions\EncryptionException,
    Fyre\Encryption\Handlers\OpenSSLEncrypter,
    Fyre\Encryption\Handlers\SodiumEncrypter,
    PHPUnit\Framework\TestCase,
    Tests\Mock\MockEncrypter;

final class EncryptionTest extends TestCase
{

    public function testLoad(): void
    {
        $this->assertInstanceOf(
            SodiumEncrypter::class,
            Encryption::load([
                'className' => SodiumEncrypter::class
            ])
        );
    }

    public function testLoadInvalidHandler(): void
    {
        $this->expectException(EncryptionException::class);

        Encryption::load([
            'className' => 'Invalid'
        ]);
    }

    public function testUse(): void
    {
        $handler1 = Encryption::use();
        $handler2 = Encryption::use();

        $this->assertSame($handler1, $handler2);

        $this->assertInstanceOf(
            SodiumEncrypter::class,
            $handler1
        );
    }

    public function testUseHandler(): void
    {
        $this->assertInstanceOf(
            OpenSSLEncrypter::class,
            Encryption::use('openssl')
        );
    }

    public function testAddHandler(): void
    {
        Encryption::setConfig('mock', [
            'className' => MockEncrypter::class
        ]);

        $this->assertInstanceOf(
            MockEncrypter::class,
            Encryption::use('mock')
        );
    }

    protected function setUp(): void
    {
        Encryption::clear();
    }

}
