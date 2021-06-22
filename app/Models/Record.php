<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Record extends Model
{
    protected $collection = 'records';

    // public static function upsert($appInfo, $recordSettings)
    // {
    //     $this->collection->where('appInfo', $appInfo)
    //         ->update($recordSettings, ['upsert' => true]);
    // }
}
