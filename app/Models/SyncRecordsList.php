<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SyncRecordsList extends Model
{
    protected $collection = 'syncRecordsList';

    public static function upsertSyncRecordsList($syncInfo, $recordSettings)
    {
        self::where('syncInfo', $syncInfo)
            ->update($recordSettings, ['upsert' => true]);
    }

    public static function getSyncRecordsList($syncInfo)
    {
        return self::where('syncInfo', $syncInfo)->get(['RecordsIdsMapping'])->first();
    }
}
