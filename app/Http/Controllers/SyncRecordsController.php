<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\AddRecordsController;
use App\Http\Controllers\Kintone\UploadFilesController;
use App\Models\Record as RecordModel;
use App\Models\SyncRecordsList as SyncRecordsListModel;
use Request;
use App\Utils\Utils;

class SyncRecordsController extends Controller
{
    /**
     * 同步应用数据
     * @param array apps
     * @param string domain
     * @param string todomain
     * @param string tousername
     * @param string topassword
     * @return json status
     */
    public function syncRecords()
    {
        $input = Request::all();
        $apps  = $input['apps'];
        $fromdomain = $input['domain'];

        //这里是to 的config
        $toDomain = Utils::explodeDomain($input['todomain']);
        $toConfig = [
            'domain' => $toDomain['domain'],
            'subdomain' => $toDomain['subdomain'],
            'login' => $input["tousername"],
            'password' => $input["topassword"],
        ];

        $uploadFilesController = new UploadFilesController($toConfig);
        $addRecordsController = new AddRecordsController($toConfig);

        foreach ($apps as $key => $appIdMapping) {
            $fromAppInfo = ["appid" => $appIdMapping["fromappid"], "domain" => $fromdomain];                           
            $appRecords = $this->getRecordSetting($fromAppInfo);
            if (empty($appRecords)) {
                unset($apps[$key]);
                continue;
            }
            $records = $appRecords->records;
            $fileList = $appRecords->uploadFiles;
            $newFileKeyList = $uploadFilesController->uploadFiles($fileList);
            // $syncInfo = ["fromdomain" => $fromdomain, "fromappid" => $appIdMapping["fromappid"], "todomain" => $input['todomain'], "toappid" => $appIdMapping["toappid"]];
            $recordsIdMappingList = $addRecordsController->addRecords($appIdMapping["toappid"], $records, $newFileKeyList);

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
