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

**Add Handler**

Add a handler.

- `$handler` is the handler name.
- `$className` is the class name.

```php
Encryption::addHandler($handler, $className);
```

**Clear**

Clear instances.

```php
Encryption::clear();
```

**Load**

Load a handler.

- `$config` is the configuration for the handler.

```php
$handler = Encryption::load($config);
```

**Set Default Handler**

Set the default handler.

- `$handler` is the handler name.

```php
Encryption::setDefaultHandler($handler);
```

**Use**

Load a shared handler instance.

- `$key` is the instance key.
- `$config` is the configuration for the handler.

```php
$handler = Encryption::use($key, $config);
```


## Handlers

You can load a specific handler by specifying the `handler` property of the `$config` variable above, otherwise the default handler will be loaded.

The available handlers are *"sodium"* (default) and *"openssl"*.

All handlers extend `\Fyre\Encryption\Handlers\BaseHandler`, ensuring all below methods are implemented.

**Decrypt**

Decrypt data.

- `$data` is the encrypted data.
- `$key` is the encryption key.

```php
$decrypted = $handler->decrypt($data, $key);
```

**Encrypt**

Encrypt data.

- `$data` is the data to encrypt.
- `$key` is the encryption key.

```php
$decrypted = $handler->decrypt($data, $key);
```

**Generate Key**

Generate an encryption key.

```php
$key = $handler->generateKey();
```