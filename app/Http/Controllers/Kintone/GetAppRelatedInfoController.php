<?php

namespace App\Http\Controllers\Kintone;

use App\Http\Controllers\Controller;
use App\Models\App as AppModel;

class GetAppRelatedInfoController extends controller
{
    public function getNeedUpdateFields($fromAppInfo)
    {
        $domain = $fromAppInfo['domain'];
        $appInfo = ["appid" =>  $fromAppInfo['appid'], "domain" => $domain];
        $result  = AppModel::where('appInfo', $appInfo)->get(["fields"])->first();
        $fields = $result->fields;

        $fieldRelateion = [];

        foreach ($fields as $field) {
            if ($field['type'] === "SUBTABLE") {
                foreach ($field["fields"] as $subField) {
                    if (isset($subField["lookup"])) {
                        $needUpdateFields = $this->getFieldRelateion($subField, $domain);
                        $fieldRelateion[] = ["domain" => $domain, "needUpdateFields" => ["relatedApp" => $subField['lookup']['relatedApp']['app'], "field" => $needUpdateFields]];
                    }
                }
            }
            if (isset($field["lookup"])) {
                $needUpdateFields = $this->getFieldRelateion($field, $domain);
                $fieldRelateion[] = ["domain" => $domain, "needUpdateFields" => ["relatedApp" => $field['lookup']['relatedApp']['app'], "field" => $needUpdateFields]];
            } else {
                continue;
            }
        }

        return $fieldRelateion;
    }

    // public function getFieldsNewValue($syncInfo, $fieldRelateion)
    // {
    //     foreach($fieldRelateion as $Relateion){

    //     }
    // }
    public function getFieldRelateion($field, $domain)
    {
        $relatedApp = $field['lookup']['relatedApp']['app'];
        $relateion = [];
        $relateion[] = ["relatedFields" => $field['lookup']['relatedKeyField'], "field" => $field['code']];
        if (!empty($field['lookup']['fieldMappings'])) {
            foreach ($field['lookup']['fieldMappings'] as $f) {
                $relateion[] = ["relatedFields" => $f['relatedField'], "field" => $f['field']];
            }
        }
        $relatedAppInfo =  ["appid" => (int) $relatedApp, "domain" => $domain];
        return $this->checkIsTheRecordNumber($relatedAppInfo, $relateion);
    }

    public function checkIsTheRecordNumber($relatedAppInfo, $checkFields)
    {
        $result = AppModel::getRecordNumberFiled($relatedAppInfo);
        $fields = $result->fields;
        $RECORD_NUMBER = '';
        foreach ($fields as $field) {
            if ($field["type"] == "RECORD_NUMBER") {
                $RECORD_NUMBER = $field['code'];
                break;
            }
        }
        $needUpdateFields = [];
        foreach ($checkFields as $key => $f) {
            if ($f["relatedFields"] === $RECORD_NUMBER) {
                $needUpdateFields[] = $f["field"];
            }
        }
        return $needUpdateFields;
    }
}
