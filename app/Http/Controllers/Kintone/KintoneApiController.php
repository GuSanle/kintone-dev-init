<?php

namespace App\Http\Controllers\Kintone;

use App\Facades\KintoneApi;
use App\Http\Controllers\Controller;

class KintoneApiController extends controller
{
    protected $kintoneApp;
    protected $kintoneApps;
    protected $kintonePreview;
    protected $kintoneFile;
    protected $kintoneRecords;
    protected $kintoneCursor;
    protected $kintoneNonpublicApi;
    protected $kintoneSpace;

    public function __construct($config)
    {
        $this->kintoneApp = KintoneApi::app($config);
        $this->kintoneApps = KintoneApi::apps($config);
        $this->kintonePreview = KintoneApi::preview($config);
        $this->kintoneFile = KintoneApi::file($config);
        $this->kintoneRecords = KintoneApi::records($config);
        $this->kintoneCursor = KintoneApi::cursor($config);
        $this->kintoneNonpublicApi = KintoneApi::nonpublicApi($config);
        $this->kintoneSpace = KintoneApi::space($config);
    }
}
