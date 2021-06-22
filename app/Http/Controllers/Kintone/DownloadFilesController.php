<?php

namespace App\Http\Controllers\Kintone;

use Storage;

class DownloadFilesController extends KintoneApicontroller
{
    public function downloadFiles(array $fileList)
    {
        $fileKeyList = [];
        if (empty($fileList)) return;
        foreach ($fileList as $file) {
            $fileKeyList[] =  $file['fileKey'];
        }
        $result = $this->kintoneFile->multiGet($fileKeyList);
        foreach ($result as $key => $content) {
            $disk = Storage::disk('local');
            $disk->put($fileKeyList[$key], $content);
        }
    }
}
