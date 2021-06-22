<?php

namespace App\Http\Controllers\Kintone;

use Storage;

class UploadFilesController extends KintoneApicontroller
{
    public function uploadFiles( array $fileList)
    {
        $path = [];
        $fileNames = [];

        foreach ($fileList as $file) {
            $fileKey =  $file['fileKey'];
            $filePath =  Storage::disk('local')->path($fileKey);
            $path[] = $filePath;
            $fileNames[] = ["path" => $filePath, "fileName" => $file['name']];
        }
        $result = $this->kintoneFile->multiPost($fileNames);
        $newFileKeyList = [];
        foreach ($result as $key => $file) {
            $oldKey = basename($path[$key]);
            $newFileKeyList[$oldKey] = $file;
        }
        return $newFileKeyList;
    }
}
