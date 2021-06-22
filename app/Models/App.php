<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class App extends Model
{
    protected $collection = 'appforms';

    // public static function upsert($appInfo, $appSettings)
    // {
    //     $this->collection->where('appInfo', $appInfo)
    //         ->update($appSettings, ['upsert' => true]);
    // }

    // public static function getReleatedApp($appInfo)
    // {
    //     return self::where('appInfo', $appInfo)->where("fields.Lookup.lookup", 'exists', true)->get();
    // }

    public static function getRecordNumberFiled($appInfo, $fields = ["fields"])
    {
        $fields =  self::where('appInfo', $appInfo)->get(["fields"])->first();
        return $fields;
    }
}
