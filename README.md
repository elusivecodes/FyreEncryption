# FyreEncryption

**FyreEncryption** is a free, open-source encryption library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)
- [Encrypters](#encrypters)
    - [OpenSSL](#openssl)
    - [Sodium](#sodium)



## Installation

**Using Composer**

```
composer require fyre/encryption
```

In PHP:

```php
use Fyre\Encryption\EncryptionManager;
```


## Basic Usage

- `$config` is an array containing key/value of configuration options.

```php
$encryptionManager = new EncryptionManager($config);
```


## Methods

**Build**

Build an [*Encrypter*](#encrypters).

- `$options` is an array containing configuration options.

```php
$encrypter = $encryptionManager->build($options);
```

**Clear**

Clear all instances and configs.

```php
$encryptionManager->clear();
```

**Get Config**

Get an [*Encrypter*](#encrypters) config.

- `$key` is a string representing the [*Encrypter*](#encrypters) key.

```php
$config = $encryptionManager->getConfig($key);
```

Alternatively, if the `$key` argument is omitted an array containing all configurations will be returned.

```php
$config = $encryptionManager->getConfig();
```

**Has Config**

Check if an [*Encrypter*](#encrypters) config exists.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to `Encryption::DEFAULT`.

```php
$hasConfig = $encryptionManager->hasConfig($key);
```

**Is Loaded**

Check if an [*Encrypter*](#encrypters) instance is loaded.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to `Encryption::DEFAULT`.

```php
$isLoaded = $encryptionManager->isLoaded($key);
```

**Set Config**

Set the [*Encrypter*](#encrypters) config.

- `$key` is a string representing the [*Encrypter*](#encrypters) key.
- `$options` is an array containing configuration options.

```php
$encryptionManager->setConfig($key, $options);
```

**Unload**

Unload an [*Encrypter*](#encrypters).

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to `Encryption::DEFAULT`.

```php
$encryptionManager->unload($key);
```

**Use**

Load a shared [*Encrypter*](#encrypters) instance.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to `Encryption::DEFAULT`.

```php
$encrypter = $encryptionManager->use($key);
```


## Encrypters

You can load a specific encrypter by specifying the `className` option of the `$options` variable above.

Custom encrypters can be created by extending `\Fyre\Encryption\Encrypter`, ensuring all below methods are implemented.

**Decrypt**

Decrypt data.

- `$data` is the encrypted data.
- `$key` is a string representing the encryption key.

```php
$decrypted = $encrypter->decrypt($data, $key);
```

**Encrypt**

Encrypt data.

- `$data` is the data to encrypt.
- `$key` is a string representing the encryption key.

```php
$encrypted = $encrypter->encrypt($data, $key);
```

**Generate Key**

Generate an encryption key.

```php
$key = $encrypter->generateKey();
```


### OpenSSL

The OpenSSL encrypter can be loaded using default configuration using the "*openssl*" key.

```php
$encrypter = $encryptionManager->use('openssl');
```

You can also load the OpenSSL encrypter using custom configuration.

- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\OpenSSLEncrypter::class`.
    - `cipher` is a string representing the cipher, and will default to "*AES-256-CTR*".
    - `digest` is a string representing the digest, and will default to "*SHA512*".

```php
$encrypter = $encryptionManager->build($options);
```


### Sodium

The Sodium encrypter is the default handler.

```php
$encrypter = $encryptionManager->use();
```

You can also load the Sodium encrypter using custom configuration.

- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\SodiumEncrypter::class`.
    - `blockSize` is a number representing the block size, and will default to *16*.
    - `digest` is a string representing the digest, and will default to "*SHA512*".

```php
$encrypter = $encryptionManager->build($options);
```