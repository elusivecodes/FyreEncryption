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

    public function testGetConfig(): void
    {
        $this->assertSame(
            [
                'default' => [
                    'className' => SodiumEncrypter::class
                ],
                'openssl' => [
                    'className' => OpenSSLEncrypter::class
                ]
            ],
            Encryption::getConfig()
        );
    }

    public function testGetConfigKey(): void
    {
        $this->assertSame(
            [
                'className' => SodiumEncrypter::class
            ],
            Encryption::getConfig('default')
        );
    }

    public function testGetKey(): void
    {
        $handler = Encryption::use();

        $this->assertSame(
            'default',
            Encryption::getKey($handler)
        );
    }

    public function testGetKeyInvalid(): void
    {
        $handler = Encryption::load([
            'className' => SodiumEncrypter::class
        ]);

        $this->assertSame(
            null,
            Encryption::getKey($handler)
        );
    }

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

    public function testSetConfig(): void
    {
        Encryption::setConfig([
            'test' => [
                'className' => SodiumEncrypter::class
            ]
        ]);

        $this->assertSame(
            [
                'className' => SodiumEncrypter::class
            ],
            Encryption::getConfig('test')
        );
    }

    public function testSetConfigExists(): void
    {
        $this->expectException(EncryptionException::class);

        Encryption::setConfig('default', [
            'className' => SodiumEncrypter::class
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

    protected function setUp(): void
    {
        Encryption::clear();

        Encryption::setConfig('default', [
            'className' => SodiumEncrypter::class
        ]);

        Encryption::setConfig('openssl', [
            'className' => OpenSSLEncrypter::class
        ]);
    }

}
