<?php

namespace Tests\Mock;

use
    Fyre\Encryption\Handlers\BaseHandler;

class MockHandler extends BaseHandler
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
