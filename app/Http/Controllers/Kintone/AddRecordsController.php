<?php

namespace App\Http\Controllers\Kintone;

use App\Models\Record as RecordModel;
// use App\ApiException;
use Arr;

class AddRecordsController extends KintoneApiController
{
    const extraTypes = ["__ID__","RECORD_NUMBER", "STATUS", "CATEGORY", "STATUS_ASSIGNEE", "CALC","MODIFIER","CREATOR"];
    protected $fieldRelateion;

    protected function setfieldRelateion($fieldRelateion)
    {
        $this->fieldRelateion = $fieldRelateion;
    }
    protected function getfieldRelateion()
    {
        return $this->fieldRelateion;
    }
    public function addRecords($appid, $records, $newFileKeyList, $fieldRelateion = null)
    {
        $fieldsList = [];
        if ($fieldRelateion) {
            foreach ($fieldRelateion as $f) {
                $fields = $f['fields'];
                $fieldsList = array_merge($fields, $fieldsList);
            }
            $this->setfieldRelateion($fieldRelateion);
        }
        $oldRecordsIdList = Arr::pluck($records, 'id.value');
        $newRecords = $this->newRecords($records, $newFileKeyList, $fieldsList);

        if ($newRecords === null) {
            return [];
        }
        $records_chunk = array_chunk($newRecords, 100);
        $oldRecordsIdList_chunk = array_chunk($oldRecordsIdList, 100);
        $recordsIdMappingList = [];
        foreach ($records_chunk as $key => $r) {
            // $oldRecordsIdList = $oldRecordsIdList_chunk[$key];
            // dd($r);
            $result = $this->kintoneRecords->post($appid, $r);
            $newRecordsIdList = $result['ids'];
            if (count($oldRecordsIdList_chunk[$key]) != count($newRecordsIdList)) {
                // throw new ApiException('获取失败');

            }
            $recordsIdMapping = array_combine($oldRecordsIdList_chunk[$key], $newRecordsIdList);
            $recordsIdMappingList +=  $recordsIdMapping;
        }
        // dd($recordsIdMappingList);
        return $recordsIdMappingList;
    }

    private function newRecords($records, $newFileKeyList, $fieldsList = [])
    {
        if (empty($records)) {
            return;
        }
        foreach ($records as &$record) {
            foreach ($record as $key => &$field) {
                $fieldType = $field['type'];
                if (in_array($fieldType, self::extraTypes)) {
                    unset($record[$key]);
                    continue;
                }
                $fieldValue = &$field['value'];
                if (in_array($key, $fieldsList)) {
                    $fieldValue = $this->getNewRecordNumber($key,  $fieldValue);
                }

                if ($fieldType  === "FILE") {
                    if ($fieldValue === '') continue;
                    foreach ($fieldValue as &$file) {
                        $oldFileKey = $file["fileKey"];
                        $file["fileKey"] = $newFileKeyList[$oldFileKey];
                    }
                } else if ($fieldType  === "SUBTABLE") {
                    if ($fieldValue === '') continue;
                    foreach ($fieldValue as &$item) {
                        if ($item['value'] === '') continue;
                        foreach ($item['value'] as $k => &$itemValue) {
                            if ($itemValue['value'] === '') continue;
                            if (in_array($itemValue["type"] , self::extraTypes)) {
                                unset($item['value'][$k]);
                                continue;
                            }
                            if ($itemValue["type"]  === "FILE") {
                                foreach ($itemValue['value'] as &$itemFile) {
                                    $oldFileKey = $itemFile["fileKey"];
                                    $itemFile["fileKey"] = $newFileKeyList[$oldFileKey];
                                }
                            }
                            if (in_array($k, $fieldsList)) {
                                $itemValue['value'] = $this->getNewRecordNumber($k, $itemValue['value']);
                            }
                        }
                    }
                }
            }
        }
        return $records;
    }

    public function getNewRecordNumber($field,  $oldRecordNumber)
    {
        $fieldRelateion = $this->getfieldRelateion();
        foreach ($fieldRelateion as $data) {
            if (in_array($field, $data['fields'])) {
                $newRecordNumber = isset($data['recordIdsMapping'][$oldRecordNumber]) ? $data['recordIdsMapping'][$oldRecordNumber] : null;
                return $newRecordNumber;
            }
        }
        return null;
    }
}
