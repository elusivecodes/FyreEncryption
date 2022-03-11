# FyreEncryption

**FyreEncryption** is a free, encryption library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Encrypters](#encrypters)
    - [Sodium](#sodium)
    - [OpenSSL](#openssl)



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

Clear instances.

```php
Encryption::clear();
```

**Get Key**

Get the key for an encrypter instance.

- `$encrypter` is a *Encrypter*.

```php
$key = Encryption::getKey($encrypter);
```

**Load**

Load an encrypter.

- `$options` is an array containing configuration options.

```php
$encrypter = Encryption::load($options);
```

**Set Config**

Set the encrypter config.

- `$key` is a string representing the encrypter key.
- `$options` is an array containing configuration options.

```php
Encryption::setConfig($key, $options);
```

**Use**

Load a shared encrypter instance.

- `$key` is a string representing the encrypter key, and will default to *"default"*.

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


### Sodium

The Sodium encrypter is the default handler.

```php
$encrypter = Encryption::use();
```

You can also load the encrypter using custom configuration.

- `$key` is a string representing the encrypter key.
- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\SodiumEncrypter`.
    - `blockSize` is a number indicating the block size, and will default to *16*.

```php
Encryption::setConfig($key, $options);
$encrypter = Encryption::use($key);
```


### OpenSSL

The OpenSSL encrypter can be loaded using default configuration using the "*openssl*" key.

```php
$encrypter = Encryption::use('openssl');
```

You can also load the encrypter using custom configuration.

- `$key` is a string representing the encrypter key.
- `$options` is an array containing configuration options.
    - `className` must be set to `\Fyre\Encryption\Handlers\OpenSSLEncrypter`.
    - `cipher` is a string indicating the cipher, and will default to "*AES-256-CTR*".

```php
Encryption::setConfig($key, $options);
$encrypter = Encryption::use($key);
```