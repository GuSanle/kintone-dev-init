<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DemoStatus extends Model
{
    protected $collection = 'demoStatus';

    protected $fillable = ['domain'];

    public static function get($domain)
    {
        return self::where('domain', $domain)->first();
    }

    public static function set($domain)
    {
        return self::create(["domain" => $domain]);
    }
}
