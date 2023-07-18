# FyreEncryption

**FyreEncryption** is a free, open-source encryption library for *PHP*.


## Table Of Contents
- [Installation](#installation)
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
use Fyre\Encryption\Encryption;
```


## Methods

**Clear**

Clear all instances and configs.

```php
Encryption::clear();
```

**Get Config**

Get an [*Encrypter*](#encrypters) config.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to *Encryption::DEFAULT*.

```php
$config = Encryption::getConfig($key);
```

**Get Key**

Get the key for an [*Encrypter*](#encrypters) instance.

- `$encrypter` is an [*Encrypter*](#encrypters).

```php
$key = Encryption::getKey($encrypter);
```

**Has Config**

Check if an [*Encrypter*](#encrypters) config exists.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to *Encryption::DEFAULT*.

```php
$hasConfig = Encryption::hasConfig($key);
```

**Init Config**

Initialize a set of config options.

- `$config` is an array containing key/value pairs of config options.

```php
Encryption::initConfig($config);
```

**Is Loaded**

Check if an [*Encrypter*](#encrypters) instance is loaded.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to *Encryption::DEFAULT*.

```php
$isLoaded = Encryption::isLoaded($key);
```
**Load**

Load an [*Encrypter*](#encrypters).

- `$options` is an array containing configuration options.

```php
$encrypter = Encryption::load($options);
```

**Set Config**

Set the [*Encrypter*](#encrypters) config.

- `$key` is a string representing the [*Encrypter*](#encrypters) key.
- `$options` is an array containing configuration options.

```php
Encryption::setConfig($key, $options);
```

**Unload**

Unload an [*Encrypter*](#encrypters).

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to *Encryption::DEFAULT*.

```php
$unloaded = Encryption::unload($key);
```

**Use**

Load a shared [*Encrypter*](#encrypters) instance.

- `$key` is a string representing the [*Encrypter*](#encrypters) key, and will default to *Encryption::DEFAULT*.

```php
$encrypter = Encryption::use($key);
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
$encrypter = Encryption::use('openssl');
```

You can also load the encrypter using custom configuration.

- `$key` is a string representing the encrypter key.
- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\OpenSSLEncrypter::class`.
    - `cipher` is a string indicating the cipher, and will default to "*AES-256-CTR*".

```php
Encryption::setConfig($key, $options);

$encrypter = Encryption::use($key);
```


### Sodium

The Sodium encrypter is the default handler.

```php
$encrypter = Encryption::use();
```

You can also load the encrypter using custom configuration.

- `$key` is a string representing the encrypter key.
- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\SodiumEncrypter::class`.
    - `blockSize` is a number indicating the block size, and will default to *16*.

```php
Encryption::setConfig($key, $options);

$encrypter = Encryption::use($key);
```