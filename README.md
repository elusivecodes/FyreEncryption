# FyreEncryption

**FyreEncryption** is a free, encryption library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Handlers](#handlers)



## Installation

**Using Composer**

```
composer install fyre/encryption
```

In PHP:

```php
use Fyre\Encryption;
```


## Methods

**Clear**

Clear instances.

```php
Encryption::clear();
```

**Load**

Load a handler.

- `$config` is an array containing the configuration for the handler.

```php
$handler = Encryption::load($config);
```

**Set Config**

Set the encryption config.

- `$config` is an array containing configuration data.

```php
Encryption::setConfig($config);
```

**Use**

Load a shared handler instance.

- `$key` is a string representing the config key, and will default to *"default"*.

```php
$handler = Encryption::use($key);
```


## Handlers

You can load a specific handler by specifying the `handler` option of the `$config` variable above.

The available handlers are:
- *default* - `\Fyre\Encryption\Handlers\SodiumEncrypter`
- *openssl* - `\Fyre\Encryption\Handlers\OpenSSLEncrypter`

Custom handlers can be created by extending `\Fyre\Encrypter`, ensuring all below methods are implemented.

**Decrypt**

Decrypt data.

- `$data` is the encrypted data.
- `$key` is a string representing the encryption key.

```php
$decrypted = Encryption::use()->decrypt($data, $key);
```

**Encrypt**

Encrypt data.

- `$data` is the data to encrypt.
- `$key` is a string representing the encryption key.

```php
$encrypted = Encryption::use()->encrypt($data, $key);
```

**Generate Key**

Generate an encryption key.

```php
$key = Encryption::use()->generateKey();
```