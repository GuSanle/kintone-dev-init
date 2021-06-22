<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class KintoneApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kintoneApi';
    }
}
