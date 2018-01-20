<?php

namespace App\Core\Traits;

use Dingo\Api\Exception\StoreResourceFailedException;

trait Exception
{
    public function throwException($message, $errors)
    {
        throw new StoreResourceFailedException($message, $errors);
    }
}