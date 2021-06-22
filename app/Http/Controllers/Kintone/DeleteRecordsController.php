<?php

namespace App\Http\Controllers\Kintone;

class DeleteRecordsController extends KintoneApicontroller
{
    public function deleteAllAppRecords(array $appIds)
    {
        foreach ($appIds as  $appId) {
            $this->deleteAllRecords($appId);
        }
    }

    public function deleteAllRecords($appId)
    {
        $result = $this->kintoneRecords->deleteAll($appId);
        return $result;
    }
}
