<?php

namespace App\Http\Controllers\Kintone;

class UpdateRecordsController extends KintoneApicontroller
{
    public function updateRecords($appId, $records)
    {
        $result = $this->kintoneRecords->put($appId, $records);
        return $result;
    }
}
