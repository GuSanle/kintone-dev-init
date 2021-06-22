<?php

namespace App\Http\Controllers;

use App\Models\App as AppModel;
use Request;

class AppInfoController extends Controller
{
    /**
     * 返回app的json信息
     * @param array appids
     * @param string domain
     * @return json appinfo
     */
    public function getAppInfo()
    {
        $input = Request::all();
        $appId = $input["appid"];
        $domain = isset($input["domain"]) ? $input["domain"] : "devnbbvob.cybozu.com";
        $appInfo =  ["appid" => intval($appId), "domain" => $domain];
        $appSettings = $this->getAppSetting($appInfo);
        return $appSettings;
    }

    private function getAppSetting($appInfo)
    {
        return AppModel::where('appInfo', $appInfo)->first();
    }
}
