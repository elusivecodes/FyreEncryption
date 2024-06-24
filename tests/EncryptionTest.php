<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Encryption\Encryption;
use Fyre\Encryption\Exceptions\EncryptionException;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use Fyre\Encryption\Handlers\SodiumEncrypter;
use PHPUnit\Framework\TestCase;

final class EncryptionTest extends TestCase
{
    protected function setUp(): void
    {
        Encryption::clear();

        Encryption::setConfig([
            'default' => [
                'className' => SodiumEncrypter::class
            ],
            'openssl' => [
                'className' => OpenSSLEncrypter::class
            ]
        ]);
    }

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
                'className' => OpenSSLEncrypter::class
            ],
            Encryption::getConfig('openssl')
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

        $this->assertNull(
            Encryption::getKey($handler)
        );
    }

    public function testIsLoaded(): void
    {
        Encryption::use();

        $this->assertTrue(
            Encryption::isLoaded()
        );
    }

    public function testIsLoadedInvalid(): void
    {
        $this->assertFalse(
            Encryption::isLoaded('test')
        );
    }

    public function testIsLoadedKey(): void
    {
        Encryption::use('openssl');

        $this->assertTrue(
            Encryption::isLoaded('openssl')
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
        Encryption::setConfig('test', [
            'className' => SodiumEncrypter::class
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

    public function testUnload(): void
    {
        Encryption::use();

        $this->assertTrue(
            Encryption::unload()
        );

        $this->assertFalse(
            Encryption::isLoaded()
        );
        $this->assertFalse(
            Encryption::hasConfig()
        );
    }

    public function testUnloadInvalid(): void
    {
        $this->assertFalse(
            Encryption::unload('test')
        );
    }

    public function testUnloadKey(): void
    {
        Encryption::use('openssl');

        $this->assertTrue(
            Encryption::unload('openssl')
        );

        $this->assertFalse(
            Encryption::isLoaded('openssl')
        );
        $this->assertFalse(
            Encryption::hasConfig('openssl')
        );
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
}
