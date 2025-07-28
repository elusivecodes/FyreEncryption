<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Config\Config;
use Fyre\Container\Container;
use Fyre\Encryption\Encrypter;
use Fyre\Encryption\EncryptionManager;
use Fyre\Encryption\Exceptions\EncryptionException;
use Fyre\Encryption\Handlers\OpenSSLEncrypter;
use Fyre\Encryption\Handlers\SodiumEncrypter;
use Fyre\Utility\Traits\MacroTrait;
use PHPUnit\Framework\TestCase;

use function class_uses;

final class EncryptionManagerTest extends TestCase
{
    protected EncryptionManager $encryption;

    public function testGetConfig(): void
    {
        $this->assertSame(
            [
                'default' => [
                    'className' => SodiumEncrypter::class,
                ],
                'openssl' => [
                    'className' => OpenSSLEncrypter::class,
                ],
            ],
            $this->encryption->getConfig()
        );
    }

    public function testGetConfigKey(): void
    {
        $this->assertSame(
            [
                'className' => OpenSSLEncrypter::class,
            ],
            $this->encryption->getConfig('openssl')
        );
    }

    public function testIsLoaded(): void
    {
        $this->encryption->use();

        $this->assertTrue(
            $this->encryption->isLoaded()
        );
    }

    public function testIsLoadedInvalid(): void
    {
        $this->assertFalse(
            $this->encryption->isLoaded('test')
        );
    }

    public function testIsLoadedKey(): void
    {
        $this->encryption->use('openssl');

        $this->assertTrue(
            $this->encryption->isLoaded('openssl')
        );
    }

    public function testMacroable(): void
    {
        $this->assertContains(
            MacroTrait::class,
            class_uses(Encrypter::class)
        );
    }

    public function testSetConfig(): void
    {
        $this->assertSame(
            $this->encryption,
            $this->encryption->setConfig('test', [
                'className' => SodiumEncrypter::class,
            ])
        );

        $this->assertSame(
            [
                'className' => SodiumEncrypter::class,
            ],
            $this->encryption->getConfig('test')
        );
    }

    public function testSetConfigExists(): void
    {
        $this->expectException(EncryptionException::class);

        $this->encryption->setConfig('default', [
            'className' => SodiumEncrypter::class,
        ]);
    }

    public function testUnload(): void
    {
        $this->encryption->use();

        $this->assertSame(
            $this->encryption,
            $this->encryption->unload()
        );

        $this->assertFalse(
            $this->encryption->isLoaded()
        );
        $this->assertFalse(
            $this->encryption->hasConfig()
        );
    }

    public function testUnloadInvalid(): void
    {
        $this->assertSame(
            $this->encryption,
            $this->encryption->unload('test')
        );
    }

    public function testUnloadKey(): void
    {
        $this->encryption->use('openssl');

        $this->assertSame(
            $this->encryption,
            $this->encryption->unload('openssl')
        );

        $this->assertFalse(
            $this->encryption->isLoaded('openssl')
        );
        $this->assertFalse(
            $this->encryption->hasConfig('openssl')
        );
    }

    public function testUse(): void
    {
        $handler1 = $this->encryption->use();
        $handler2 = $this->encryption->use();

        $this->assertSame($handler1, $handler2);

        $this->assertInstanceOf(
            SodiumEncrypter::class,
            $handler1
        );
    }

    protected function setUp(): void
    {
        $container = new Container();
        $container->singleton(Config::class);
        $container->use(Config::class)->set('Encryption', [
            'default' => [
                'className' => SodiumEncrypter::class,
            ],
            'openssl' => [
                'className' => OpenSSLEncrypter::class,
            ],
        ]);
        $this->encryption = $container->use(EncryptionManager::class);
    }
}
