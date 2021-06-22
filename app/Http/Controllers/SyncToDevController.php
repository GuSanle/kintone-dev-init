<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\UserController;
use App\Http\Controllers\Kintone\GetAppController;
use App\Http\Controllers\Kintone\SpaceController;
use App\Http\Controllers\Kintone\NonPublicApiController;
use App\Http\Controllers\Kintone\UploadFilesController;
use App\Http\Controllers\Kintone\AddRecordsController;
use App\Http\Controllers\DemoStatusController;

use App\Models\Record as RecordModel;
use Request;
use App\Utils\Utils;
use Storage;
use Arr;

class SyncToDevController extends Controller
{

    //同步用户，组织，首页自定义js，打开space，建立space，应用，数据，

    /**
     * 初始化用户开发环境
     * @param string domain
     * @param string username
     * @param string password
     * @return json status
     */

    public $nonPublicApiController;
    public $config;
    public $input;

    public function __construct()
    {
        $this->input = Request::all();
        $domain = Utils::explodeDomain($this->input["domain"]);

        $this->config = [
            'domain' => $domain['domain'],
            'subdomain' => $domain['subdomain'],
            'login' => $this->input["username"],
            'password' => $this->input["password"],
        ];
        $this->nonPublicApiController = new NonPublicApiController($this->config);
    }

    public function syncToDev()
    {
        $demoStatus = new DemoStatusController();
        $domain = $this->input["domain"];
        $status = $demoStatus->get($domain);
        if ($status) return;
        $this->importUsers();
        $this->importOrganizations();
        $this->openSpaceSetting();
        $fileList = $this->uploadFiles();
        // $appids =  $this->installFromTemplateFile($fileList);
        // $templateAppList = $this->getTemplateAppList($appids);
        // $this->updateCustomizeSetting($fileList);
        $templates = $this->addSpaceTemplate($fileList);
        $spaceIds = [];
        foreach ($templates as $tid => $tname) {
            $spaceIds[] = $this->addSpace($tid, $tname);
        }

        $spaceAppList = $this->getSpaceAppList($spaceIds);
        $appSort = config('kintonedevsetting.appSort');
        $newSpaceAppList = array_replace(array_flip($appSort),  $spaceAppList);

        // $appList = $templateAppList + $spaceAppList;
        $this->addRecords($newSpaceAppList);

        $demoStatus->set($domain);

        return;
    }

    public function getSpaceAppList($spaceIds)
    {
        $spaceController = new SpaceController($this->config);
        $appList = [];
        foreach ($spaceIds as $id) {
            $spaceInfo = $spaceController->get($id);
            $appInfo = Arr::pluck($spaceInfo['attachedApps'], "appId", 'name');
            $appList = $appInfo + $appList;
        }
        return $appList;
    }

    public function getTemplateAppList($appIds)
    {
        $appController = new GetAppController($this->config);
        $appList = [];
        foreach ($appIds as $id) {
            $app = $appController->getById($id);
            $appInfo[$app["appId"]] = $app["name"];
            $appList = $appInfo + $appList;
        }
        return $appList;
    }

    public function importUsers()
    {
        $userController = new UserController($this->config);
        $users = config('kintonedevsetting.users');
        $services = config('kintonedevsetting.userServices');
        $userController->postUsers($users);
        $userController->postUsersServices($services);
        return;
    }

    public function importOrganizations()
    {
        $userController = new UserController($this->config);
        $organizations = config('kintonedevsetting.organizations');
        $userOrganizations = config('kintonedevsetting.userOrganizations');
        $userController->postOrganizations($organizations);
        $path = Storage::disk('local')->path($userOrganizations);
        $org = $userController->postUserOrganizations($path);
        return $org;
    }

    public function openSpaceSetting()
    {
        $setting = config('kintonedevsetting.systemSetting');
        $status = $this->nonPublicApiController->updateSystemSetting($setting);
        return $status;
    }

    public function updateCustomizeSetting($fileList)
    {
        $jsScope = config('kintonedevsetting.jsScope');
        $jsFiles = [
            [
                "jsType" => "DESKTOP",
                "fileKeys" => ["https://js.cybozu.cn/jquery/3.4.1/jquery.min.js", $fileList["kintone-js-sdk.min.js"], $fileList["kintone-ui-component.min.js"], $fileList["mt-kinportal_desktop.js"]]
            ],
            ["jsType" => "MOBILE", "fileKeys" => []],
            ["jsType" => "DESKTOP_CSS", "fileKeys" => [$fileList["kintone-ui-component.min.css"]]],
            ["jsType" => "MOBILE_CSS", "fileKeys" => []]
        ];
        $status = $this->nonPublicApiController->updateCustomizeSetting($jsScope, $jsFiles);
        return $status;
    }

    public function uploadFiles()
    {
        $fileList = config('kintonedevsetting.uploadFiles');
        $uploadFilesController = new UploadFilesController($this->config);
        $result = $uploadFilesController->uploadFiles($fileList);
        return $result;
    }

    public function addSpaceTemplate($fileList)
    {
        $spaceSptpl = config('kintonedevsetting.spaceSptpl');
        $templates = [];
        foreach ($spaceSptpl as $key => $name) {
            $result = $this->nonPublicApiController->postSpaceTemplate($fileList[$key]);
            $tid = $result["result"]["templateId"];
            $templates[$tid] = $name;
        }

        return $templates;
    }

    public function addSpace($id, $name)
    {
        $spaceController = new SpaceController($this->config);
        $members = config('kintonedevsetting.spaceMembers');
        $spaceInfo = $spaceController->addSpace($id, $name, $members);
        return $spaceInfo['id'];
    }

    public function installFromTemplateFile($fileList)
    {
        $appTemplate = config('kintonedevsetting.appTemplate');
        $allAppIds = [];
        foreach ($appTemplate as $template) {
            $result = $this->nonPublicApiController->installFromTemplateFile(null, $fileList[$template]);
            $appIds = $result["result"]["appIds"];
            $allAppIds = $appIds + $allAppIds;
        }

        return $allAppIds;
    }


    public function addRecords($appList)
    {

        $uploadFilesController = new UploadFilesController($this->config);
        $addRecordsController = new AddRecordsController($this->config);

        if (count($appList) == 0) return;
        foreach ($appList as  $name => $appid) {
            $appRecords = $this->getRecordSetting($name);
            if (empty($appRecords)) {
                continue;
            }
            $records = $appRecords->records;
            $fileList = $appRecords->uploadFiles;
            $newFileKeyList = $uploadFilesController->uploadFiles($fileList);
            $addRecordsController->addRecords($appid, $records, $newFileKeyList);
        }
        return $appList;
    }

    private function getRecordSetting($name)
    {
        return RecordModel::where('name', $name)->first();
    }
}
