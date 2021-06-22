<?php

namespace App\Http\Controllers\Kintone;

use App\Utils\Utils;

class GetAppController extends KintoneApiController
{
    public function getById($id)
    {
        $app = $this->kintoneApp->get($id);
        return $app;
    }

    public function getAllApps($opt_offset = 0, $opt_limit = 100, $opt_allApps = [])
    {
        $app = $this->kintoneApps->get([], [], null, [], $opt_limit, $opt_offset);
        $allApps = array_merge($app["apps"], $opt_allApps);
        if (count($app["apps"]) == $opt_limit) {
            return $this->getAllApps($opt_offset, $opt_limit, $allApps);
        } else {
            return $allApps;
        }
    }

    public function getApp(array $appInfo)
    {
        $appId = $appInfo["appid"];
        // $kintoneApp = KintoneApi::app($config);
        $app = $this->kintoneApp->get($appId);
        $generalSettings = $this->kintoneApp->getSettings($appId);
        //默认获取中文的应用设置
        $lang = "zh";
        $fields = $this->kintoneApp->getFields($appId, $lang);
        $layout = $this->kintoneApp->getLayout($appId);
        $views = $this->kintoneApp->getViews($appId);
        $acl = $this->kintoneApp->getAcl($appId);
        $recordAcl = $this->kintoneApp->getRecordAcl($appId);
        $fieldAcl = $this->kintoneApp->getFieldAcl($appId);
        $customize = $this->kintoneApp->getCustomize($appId);
        $status = $this->kintoneApp->getStatus($appId, $lang);
        $newGeneralSettings = $this->newGeneralSetting($generalSettings);
        $newCustomize = $this->newCustomizeSettings($customize);
        $uploadFiles = array_merge($newGeneralSettings[1], $newCustomize[1]);

        $appSettings = [
            "name" =>  $generalSettings["name"],
            "appInfo" =>  $appInfo,
            "app" => Utils::removeRevision($app),
            "generalSettings" => $newGeneralSettings[0],
            "customizeSettings" => $newCustomize[0],
            "fields" =>  $fields['properties'],
            "layout" =>  $layout['layout'],
            "views" =>  $views['views'],
            "appStatus" =>  Utils::removeRevision($status),
            "appAcl" =>  Utils::removeRevision($acl),
            "fieldAcl" =>  Utils::removeRevision($fieldAcl),
            "recordAcl" =>  Utils::removeRevision($recordAcl),
            "uploadFiles" => $uploadFiles
        ];
        return  $appSettings;
    }

    private function newGeneralSetting($generalSettings)
    {
        $fileList = [];
        if ($generalSettings["icon"]["type"] === "FILE") {
            $fileList[] = [
                "fileKey" => $generalSettings["icon"]["file"]["fileKey"],
                "name" => $generalSettings["icon"]["file"]["name"]
            ];
        }
        $generalSettings = Utils::removeRevision($generalSettings);

        return [$generalSettings, $fileList];
    }

    private function newCustomizeSettings($customize)
    {
        $fileList = [];
        $settings = [
            $customize['desktop']['js'],
            $customize['desktop']['css'],
            $customize['mobile']['js'],
            $customize['mobile']['css']
        ];

        foreach ($settings as $setting) {
            if (empty($setting)) {
                continue;
            } else {
                foreach ($setting as $file) {
                    if ($file["type"] === "FILE") {
                        $fileList[] = [
                            "fileKey" => $file["file"]["fileKey"],
                            "name" => $file["file"]["name"]
                        ];
                    }
                }
            }
        }
        $customize = Utils::removeRevision($customize);
        return [$customize, $fileList];
    }
}
