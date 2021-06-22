<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CybozuUserApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cybozuUserApi';
    }
}
