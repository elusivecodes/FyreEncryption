# FyreEncryption

**FyreEncryption** is a free, encryption library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Encrypters](#encrypters)



## Installation

**Using Composer**

```
composer install fyre/encryption
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

**Load**

Load an encrypter.

- `$config` is an array containing the configuration for the encrypter.

```php
$encrypter = Encryption::load($config);
```

**Set Config**

Set the encrypter config.

- `$key` is a string representing the encrypter key.
- `$config` is an array containing configuration data.

```php
Encryption::setConfig($key, $config);
```

**Use**

Load a shared encrypter instance.

- `$key` is a string representing the encrypter key, and will default to *"default"*.

```php
$encrypter = Encryption::use($key);
```


## Encrypters

You can load a specific encrypter by specifying the `className` option of the `$config` variable above.

The default encrypters are:
- *default* - `\Fyre\Encryption\Handlers\SodiumEncrypter`
- *openssl* - `\Fyre\Encryption\Handlers\OpenSSLEncrypter`

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