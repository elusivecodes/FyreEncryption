<?php

namespace Tests\Mock;

use
    Fyre\Encrypter;

class MockEncrypter extends Encrypter
{

    public function decrypt(string $data, string $key)
    {
        return '';
    }

    public function encrypt($data, string $key): string
    {
        return '';
    }

    public function generateKey(int|null $length = null): string
    {
        return '';
    }

}
