<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Kintone\NonPublicApiController;



use Request;
use App\Utils\Utils;
use Arr;
use Storage;


class GetTemplatesController extends Controller
{

    /**
     * 定期导出应用模板和空间模板
     * @param string domain
     * @param string username
     * @param string password
     */

    public $nonPublicApiController;
    public $config;

    public function __construct()
    {
        $input = Request::all();
        $domain = Utils::explodeDomain($input["domain"]);

        $this->config = [
            'domain' => $domain['domain'],
            'subdomain' => $domain['subdomain'],
            'login' => $input["username"],
            'password' => $input["password"],
        ];
        $this->nonPublicApiController = new NonPublicApiController($this->config);
    }

    public function export()
    {
        $spaceTemplateList = $this->getSpaceTemplateList();
        $idList = Arr::pluck($spaceTemplateList["result"]["templates"],  "id");
        $nameList = Arr::pluck($spaceTemplateList["result"]["templates"],  "name");
        $this->exportSpaceTemplates($idList, $nameList);

        $appTemplateList =   $this->getAppTemplateList();
        $items = '';
        foreach ($appTemplateList['result']['items']  as $k => $v) {
            if ($k == 0) {
                $and = "";
            } else {
                $and = "&";
            };
            $item =  $and . "items[$k]=" . $v['id'];
            $items = $items . $item;
        }
        $this->exportAppTemplates($items);
        return;
    }

    public function getSpaceTemplateList()
    {
        $spaceTemplateList = $this->nonPublicApiController->getSpaceTemplateList();

        return $spaceTemplateList;
    }


    public function exportSpaceTemplates($idList, $nameList)
    {
        $fileList = $this->nonPublicApiController->exportSpaceTemplates($idList);

        foreach ($fileList as $k => $file) {
            $disk = Storage::disk('local');
            $name = $nameList[$k] . ".sptpl";
            $disk->put($name, $file);
        }
        return;
    }

    public function exportAppTemplates($items)
    {
        $file = $this->nonPublicApiController->exportAppTemplates($items);
        $disk = Storage::disk('local');
        $name =  "template.zip";
        $disk->put($name, $file);
        return;
    }


    public function getAppTemplateList()
    {
        $appTemplateList = $this->nonPublicApiController->getAppTemplateList();

        return $appTemplateList;
    }
}
