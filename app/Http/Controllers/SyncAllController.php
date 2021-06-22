<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\AddAppController;
use App\Http\Controllers\Kintone\AddRecordsController;
use App\Http\Controllers\Kintone\UploadFilesController;
use App\Models\App as AppModel;
use App\Models\Record as RecordModel;
use App\Models\SyncRecordsList as SyncRecordsListModel;
use Request;
use App\Utils\Utils;
use App\Http\Controllers\Kintone\GetAppRelatedInfoController;

class SyncAllController extends Controller
{
    /**
     * 同步应用及数据
     * @param array appids
     * @param string domain
     * @param string todomain
     * @param string tousername
     * @param string topassword
     * @param string isneedrecords
     * @param string hasrelatedapp
     * @param string relatedApp
     * @return json status
     */
    public function syncAll()
    {
        $input = Request::all();
        $fromApps  = $input['appids'];
        $domain = $input['domain'];
        $spaceId  = $input['spaceId'] ? $input['spaceId'] : '';
        $threadId  = $input['threadId'] ? $input['threadId'] : '';
        $appidMapping = [];
        if (isset($input['relatedApp'])) {
            foreach ($input['relatedApp'] as $relateApp) {
                $appidMapping[$relateApp["toappid"]] = $relateApp["fromappid"];
            }
        }

        //这里是to 的config
        $toDomain = Utils::explodeDomain($input['todomain']);
        $toConfig = [
            'domain' => $toDomain['domain'],
            'subdomain' => $toDomain['subdomain'],
            'login' => $input["tousername"],
            'password' => $input["topassword"],
        ];
        $uploadFilesController = new UploadFilesController($toConfig);

        //setp1 拉取设置保存本地
        $backupAllController = new BackupAllController();

        $backupAllController->backupAll();

        $newAppids = [];
        foreach ($fromApps as $appid) {
            //setp2 获取本地设置
            $fromAppInfo = ["appid" => $appid, "domain" => $domain];
            $appSettings = $this->getAppSetting($fromAppInfo);
            $appSettings->spaceId = $spaceId;
            $appSettings->threadId = $threadId;

            //setp3 还原到目标
            $fileList = $appSettings->uploadFiles;
            $newFileKeyList = $uploadFilesController->uploadFiles($fileList);
            $addAppController = new AddAppController($toConfig);
            $newappid = $addAppController->addApp($appSettings, $newFileKeyList, array_flip($appidMapping));
            $addAppController->deployApps([["app" => $newappid]]);

            //如果有关联记录的数据，需要让基础应用先创建好。
            if (isset($input['hasrelatedapp']) && $input['hasrelatedapp']) {
                if ($appid !== end($fromApps)) sleep(5);
            } else {
                sleep(5);
            }
            $appidMapping[$newappid] = $appid;
            $newAppids[] = (int) $newappid;
        }
        if ($input['isneedrecords'] === false) return ['status' => $fromApps, 'msg' => "同步成功"];

        //休眠3秒。将不成功的清洗掉，写入log
        sleep(3);
        $deployedApps = $addAppController->getDeployedApps($newAppids, 0);
        $addRecordsController = new AddRecordsController($toConfig);

        foreach ($deployedApps as $newapp) {
            $fromAppInfo = ["appid" => $appidMapping[$newapp], "domain" => $domain];
            $appRecords = $this->getRecordSetting($fromAppInfo);
            $records = $appRecords->records;
            $fileList = $appRecords->uploadFiles;
            $newFileKeyList = $uploadFilesController->uploadFiles($fileList);
            $todomain =  $input['todomain'];
            $syncInfo = ["fromdomain" => $domain, "fromappid" => (int) $appidMapping[$newapp], "todomain" => $todomain, "toappid" => $newapp];
            $fieldRelateion = $this->getRelateAppInfo($fromAppInfo, $appidMapping,  $todomain);
            $recordsIdMappingList = $addRecordsController->addRecords($newapp, $records, $newFileKeyList, $fieldRelateion);
            $SyncRecordsListModel = new SyncRecordsListModel();
            $SyncRecordsListModel::upsertSyncRecordsList($syncInfo, ["RecordsIdsMapping" => $recordsIdMappingList]);
        }
        return ['successAppids' => $deployedApps, 'msg' => "同步成功"];
    }

    private function getRelateAppInfo($fromAppInfo, $appidMapping, $todomain)
    {
        $getAppRelatedInfo = new GetAppRelatedInfoController();
        $fieldRelateion = $getAppRelatedInfo->getNeedUpdateFields($fromAppInfo);
        if (empty($fieldRelateion)) return null;
        $fieldRecordMapping = [];

        foreach ($fieldRelateion as $key => $fields) {
            $fieldRecordMappingData = [];
            $needUpdateFields =  $fields['needUpdateFields'];

            if (empty($needUpdateFields['field'])) continue;
            $fromappid = $needUpdateFields['relatedApp'];
            $flipAppidMapping = array_flip($appidMapping);
            if (isset($flipAppidMapping[$fromappid])) {
                $toappid = $flipAppidMapping[$fromappid];
            } else {
                //报错
                dd($fromAppInfo);
                exit;
            }
            $syncInfo = ["fromdomain" => $fromAppInfo["domain"], "fromappid" => (int) $fromappid, "todomain" => $todomain, "toappid" => (string) $toappid];
            $syncRecordsListModel = new SyncRecordsListModel();
            $recordIdsMapping = $syncRecordsListModel::getSyncRecordsList($syncInfo);
            $fieldRecordMappingData['fields'] = $needUpdateFields['field'];
            $fieldRecordMappingData['relatedApp'] = $fromappid;
            $fieldRecordMappingData['recordIdsMapping'] = $recordIdsMapping->RecordsIdsMapping;
            $fieldRecordMapping[] = $fieldRecordMappingData;
        }
        return $fieldRecordMapping;
    }

    private function getAppSetting($appInfo)
    {
        return AppModel::where('appInfo', $appInfo)->first();
    }

    private function getRecordSetting($appInfo)
    {
        return RecordModel::where('appInfo', $appInfo)->first();
    }
}
