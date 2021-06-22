<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\AddRecordsController;
use App\Http\Controllers\Kintone\UploadFilesController;
use App\Http\Controllers\Kintone\DeleteRecordsController;
use App\Models\Record as RecordModel;
use App\Models\SyncRecordsList as SyncRecordsListModel;
use Request;
use App\Utils\Utils;

class RestoreRecordsController extends Controller
{
    /**
     * 从mongodb恢复本身应用的数据
     * @param array apps
     * @param string domain
     * @param string username
     * @param string password
     * @return json status
     */
    public function restoreRecords()
    {
        $input = Request::all();
        $apps  = $input['apps'];
        $domain = $input['domain'];

        //这里是to 的config
        $toDomain = Utils::explodeDomain($domain);
        $toConfig = [
            'domain' => $toDomain['domain'],
            'subdomain' => $toDomain['subdomain'],
            'login' => $input["username"],
            'password' => $input["password"],
        ];

        $deleteAllAppRecords = new DeleteRecordsController($toConfig);
        $deleteAllAppRecords->deleteAllAppRecords($apps);

        sleep(5);
        $uploadFilesController = new UploadFilesController($toConfig);
        $addRecordsController = new AddRecordsController($toConfig);

        foreach ($apps as $key => $appid) {
            $fromAppInfo = ["appid" => $appid, "domain" => $domain];
            $appRecords = $this->getRecordSetting($fromAppInfo);
            if (empty($appRecords)) {
                unset($apps[$key]);
                continue;
            }
            $records = $appRecords->records;
            $fileList = $appRecords->uploadFiles;
            $newFileKeyList = $uploadFilesController->uploadFiles($fileList);
            // $syncInfo = ["fromdomain" => $domain, "fromappid" => $appid, "todomain" => $domain, "toappid" => $appid];
            $recordsIdMappingList = $addRecordsController->addRecords($appid, $records, $newFileKeyList);
            // $SyncRecordsListModel = new SyncRecordsListModel();
            // $SyncRecordsListModel::upsertSyncRecordsList($syncInfo, ["RecordsIdsMapping" => $recordsIdMappingList]);
        }
        return ["apps" => $apps, 'msg' => "同步成功"];
    }

    private function getRecordSetting($appInfo)
    {
        return RecordModel::where('appInfo', $appInfo)->first();
    }
}
