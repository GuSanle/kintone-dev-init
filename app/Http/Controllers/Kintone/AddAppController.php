<?php

namespace App\Http\Controllers\Kintone;

class AddAppController extends KintoneApiController
{
    const extraTypes = ["STATUS", "STATUS_ASSIGNEE", "CATEGORY"];
    const defaultTypes = ["RECORD_NUMBER", "CREATOR", "UPDATED_TIME", "MODIFIER", "CREATED_TIME"];
    const zhDefaultTypes = ["RECORD_NUMBER" => "记录编号", "CREATOR" => "创建人", "UPDATED_TIME" => "更新时间", "MODIFIER" => "更新人", "CREATED_TIME" => "创建时间"];

    public function addApp($appSettings, $newFileKeyList, $relateAppList = [])
    {
        $app = $appSettings->app;
        $generalSettings = $appSettings->generalSettings;
        $fields = $appSettings->fields;
        $customizeSettings = $appSettings->customizeSettings;
        $status = $appSettings->appStatus;
        $views =  $appSettings->views;

        $result = $this->kintonePreview->post($app['name'], $appSettings->spaceId, $appSettings->threadId);
        $appid = $result['app'];

        $newSetting = $this->newGeneralSetting($generalSettings, $newFileKeyList);
        $newFormFields = $this->newFormFields($fields,  $app['appId'], $appid, $relateAppList);
        $newCustomizeSettings = $this->newCustomizeSettings($customizeSettings, $newFileKeyList);
        $this->kintonePreview->putSettings($appid, $newSetting['name'], $newSetting['description'], $newSetting['icon'], $newSetting['theme']);
        $defaultFileds = $newFormFields[1];
        $newFormFields = empty($newFormFields[0]) ?  new \ArrayObject() : $newFormFields[0];
        $this->kintonePreview->postFields($appid, $newFormFields);
        $this->kintonePreview->putFields($appid, $defaultFileds);
        $this->kintonePreview->putLayout($appid, $appSettings->layout);
        $this->kintonePreview->putCustomize($appid, $newCustomizeSettings["desktop"], $newCustomizeSettings["mobile"]);
        $this->kintonePreview->putRecordAcl($appid, $appSettings->recordAcl["rights"]);
        $this->kintonePreview->putFieldAcl($appid, $appSettings->fieldAcl["rights"]);
        $this->kintonePreview->putAcl($appid, $appSettings->appAcl["rights"]);
        $this->kintonePreview->putStatus($appid, $status['states'], $status['actions'], $status['enable']);
        $newViews = $this->newViews($views);
        $this->kintonePreview->putViews($appid,  $newViews);
        return $appid;
    }

    public function deployApps($apps)
    {
        $this->kintonePreview->deployApps($apps);
    }

    //最多查询3次
    public function getDeployedApps($apps, $times)
    {
        $deployStatus =  $this->kintonePreview->getDeployStatuses($apps);
        $processing = false;
        $newappids = [];
        foreach ($deployStatus as $status) {
            if ($status['status'] === "PROCESSING") {
                $processing = true;
                break;
            } else if ($status['status'] === "SUCCESS") {
                $newappids[] = $status['app'];
            }
        }
        if ($processing && $times < 3) {
            $times++;
            sleep(3);
            return $this->getDeployedApps($apps, $times);
        }
        return $newappids;
    }

    private function newGeneralSetting($generalSettings, $newFileKeyList)
    {
        if ($generalSettings["icon"]["type"] === "FILE") {
            $oldFileKey = $generalSettings["icon"]["file"]["fileKey"];
            $generalSettings["icon"]["file"]['fileKey'] = $newFileKeyList[$oldFileKey];
        }
        return $generalSettings;
    }

    private function newFormFields($fields, $fromAppid, $newAppid, $relateAppList)
    {
        $defaultFileds = [];
        foreach ($fields as $key => &$field) {
            $type = $field['type'];
            if (in_array($type, self::extraTypes)) {
                unset($fields[$key]);
            } else if (in_array($type, self::defaultTypes)) {
                $defaultFileds[self::zhDefaultTypes[$type]] = $field;
                unset($fields[$key]);
            }
            //关联记录列表
            if ($type === "REFERENCE_TABLE") {
                $relatedApp = $field["referenceTable"]["relatedApp"];
                $field["referenceTable"]["relatedApp"] = $this->newRelatedAppid($relatedApp, $fromAppid, $newAppid,  $relateAppList);
                continue;
            }
            //lookup
            if (isset($field["lookup"])) {
                $relatedApp = $field["lookup"]["relatedApp"];
                $field["lookup"]["relatedApp"] = $this->newRelatedAppid($relatedApp, $fromAppid, $newAppid,  $relateAppList);
                continue;
            }
            if ($type === "SUBTABLE") {
                foreach ($field["fields"] as &$subField) {
                    if (isset($subField["lookup"])) {
                        $relatedApp = $subField["lookup"]["relatedApp"];
                        $subField["lookup"]["relatedApp"] = $this->newRelatedAppid($relatedApp, $fromAppid, $newAppid,  $relateAppList);
                    }
                }
            }
        }
        return [$fields, $defaultFileds];
    }

    private function newRelatedAppid($relatedApp, $fromAppid, $newAppid,  $relateAppList = [])
    {
        if (isset($relatedApp["app"]) && $relatedApp["app"] !== '') {
            $appid = (int) $relatedApp["app"];
            //判断是否为关联自身应用
            if ($fromAppid == $appid) {
                $relatedApp["app"] = $newAppid;
            } else {
                $relatedApp["app"] = isset($relateAppList[$appid]) ? $relateAppList[$appid] : $appid;
            }
        }
        return $relatedApp;
    }

    private function newCustomizeSettings($customize, $newFileKeyList)
    {
        $settings = [
            &$customize['desktop']['js'],
            &$customize['desktop']['css'],
            &$customize['mobile']['js'],
            &$customize['mobile']['css']
        ];

        foreach ($settings as $k => $setting) {
            if (empty($setting)) {
                continue;
            } else {
                foreach ($setting as $f => $file) {
                    if ($file["type"] === "FILE") {
                        $oldFileKey = $file["file"]["fileKey"];
                        $settings[$k][$f]["file"] = [
                            "fileKey" => $newFileKeyList[$oldFileKey]
                        ];
                    }
                }
            }
        }
        return $customize;
    }

    //设置了流程管理时会自动追加列表
    private function newViews($views)
    {
        if (empty($views)) {
            return new \ArrayObject();
        } else {
            foreach ($views as $key => $view) {
                if (isset($view['builtinType']) && $view['builtinType'] === "ASSIGNEE") {
                    unset($views[$key]);
                    $statutsZHname  = "（我的工作）";
                    $views[$statutsZHname] = $view;
                }
            }
        }
        return $views;
    }
}
