<?php

namespace App\Http\Controllers\Kintone;

class GetRecordsController extends KintoneApiController
{

    public function getRecords(array $appInfo)
    {
        $app = $appInfo["appid"];
        $query = "order by \$id asc";
        $records = $this->kintoneCursor->all($app, $query);
        $newRecords = $this->newRecords($records);
        $appRecords = [
            "appInfo" => ["appid" => $appInfo["appid"], "domain" => $appInfo["domain"]],
            "name" => $appInfo["name"],
            "records" => $newRecords[0],
            "uploadFiles" => $newRecords[1]
        ];
        return $appRecords;
    }

    private function newRecords($records)
    {
        $fileList = [];
        foreach ($records as $key => $record) {
            $records[$key]['id'] = $record['$id'];
            unset($records[$key]['$revision']);
            unset($records[$key]['$id']);
            foreach ($record as $field) {
                $fieldType = $field['type'];
                $fieldValue = $field['value'];

                if ($fieldType  === "FILE") {
                    if ($fieldValue === '') continue;
                    foreach ($fieldValue as $file) {
                        $fileList[] = [
                            "fileKey" => $file["fileKey"],
                            "name" => $file["name"]
                        ];
                    }
                } else if ($fieldType  === "SUBTABLE") {
                    if ($fieldValue === '') continue;
                    foreach ($fieldValue as $item) {
                        if ($item['value'] === '') continue;
                        foreach ($item['value'] as $itemValue) {
                            if ($itemValue['value'] === '') continue;
                            if ($itemValue["type"]  === "FILE") {
                                foreach ($itemValue['value'] as $itemFile) {
                                    $fileList[] = [
                                        "fileKey" => $itemFile["fileKey"],
                                        "name" => $itemFile["name"]
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        return [$records, $fileList];
    }
}
