<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\GetAppController;
use App\Http\Controllers\Kintone\GetRecordsController;
use App\Http\Controllers\Kintone\DownloadFilesController;
use App\Models\App as AppModel;
use App\Models\Record as RecordModel;
use Request;
use Arr;
use App\Utils\Utils;

class BackupAllController extends Controller
{
    /**
     * 备份多个应用的form，record
     * @param array appids
     * @param string domain
     * @param string username
     * @param string password
     * @return json status
     */
    public $getAppController;
    public $config;
    public $domain;

    public function __construct()
    {
        $input = Request::all();
        $this->domain = Utils::explodeDomain($input["domain"]);

        $this->config = [
            'domain' => $this->domain['domain'],
            'subdomain' => $this->domain['subdomain'],
            'login' => $input["username"],
            'password' => $input["password"],
        ];
        $this->getAppController = new GetAppController($this->config);
    }

    public function backupDevdemo()
    {
        $all = $this->getAppController->getAllApps();
        $appids = [];
        foreach ($all as $appInfo) {
            $appids[] = $appInfo['appId'];
        }
        $this->backup($appids);
        return ['status' => 'ok', 'msg' => "备份成功"];
    }

    public function backupAll()
    {
        $input = Request::all();
        $appIds = $input["appids"];
        $this->backup($appIds);
        // $input = Request::all();
        // $appIds = $input["appids"];
        // $domain = Utils::explodeDomain($input["domain"]);

        // $config = [
        //     'domain' => $domain['domain'],
        //     'subdomain' => $domain['subdomain'],
        //     'login' => $input["username"],
        //     'password' => $input["password"],
        // ];

        // $getAppController = new GetAppController($this->config);
        // $getRecordsController = new GetRecordsController($this->config);
        // $downloadFilesController = new DownloadFilesController($this->config);

        // $fileList = [];
        // foreach ($appIds as $appId) {
        //     $appInfo =  ["appid" => intval($appId), "domain" => $input["domain"]];
        //     $appSettings = $this->getAppController->getApp($appInfo);
        //     $appInfo["name"] = $appSettings["name"];
        //     $appRecords = $getRecordsController->getRecords($appInfo);
        //     $this->saveSettings($appInfo, $appSettings, $appRecords);
        //     if (!empty($appSettings["uploadFiles"])) $fileList = array_merge($fileList, $appSettings["uploadFiles"]);
        //     if (!empty($appRecords["uploadFiles"]))  $fileList = array_merge($fileList, $appRecords["uploadFiles"]);
        // }
        // $downloadFilesController->downloadFiles($fileList);
        return ['status' => 'ok', 'msg' => "获取成功"];
    }

    private function backup($appIds)
    {

        // $input = Request::all();
        // $domain = Utils::explodeDomain($input["domain"]);
        // $config = [
        //     'domain' => $domain['domain'],
        //     'subdomain' => $domain['subdomain'],
        //     'login' => $input["username"],
        //     'password' => $input["password"],
        // ];

        // $getAppController = new GetAppController($this->config);
        $getRecordsController = new GetRecordsController($this->config);
        $downloadFilesController = new DownloadFilesController($this->config);

        $fileList = [];
        foreach ($appIds as $appId) {
            $appInfo =  ["appid" => intval($appId), "domain" => $this->domain];
            $appSettings = $this->getAppController->getApp($appInfo);
            $appInfo["name"] = $appSettings["name"];
            $appRecords = $getRecordsController->getRecords($appInfo);
            $this->saveSettings($appInfo, $appSettings, $appRecords);
            if (!empty($appSettings["uploadFiles"])) $fileList = array_merge($fileList, $appSettings["uploadFiles"]);
            if (!empty($appRecords["uploadFiles"]))  $fileList = array_merge($fileList, $appRecords["uploadFiles"]);
        }
        $downloadFilesController->downloadFiles($fileList);
        // return ['status' => 'ok', 'msg' => "获取成功"];
    }


    private function saveSettings($appInfo, $appSettings, $appRecords)
    {
        AppModel::where('appInfo', $appInfo)->update($appSettings, ['upsert' => true]);
        RecordModel::where('appInfo', $appInfo)->update($appRecords, ['upsert' => true]);
    }
}
