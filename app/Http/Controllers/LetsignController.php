<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Kintone\GetRecordsController;
use App\Http\Controllers\Kintone\UpdateRecordsController;
use Illuminate\Http\Request;

class LetsignController extends Controller
{

    public $getRecordsController;
    public $updateRecordsController;
    public $kintoneInfo;
    public $secret;

    public function __construct()
    {
        $this->kintoneInfo = config('letsign.kintoneInfo');

        $config = [
            'domain' => $this->kintoneInfo['domain'],
            'subdomain' => $this->kintoneInfo['subdomain'],
            "use_api_token" => true,
            'token' => $this->kintoneInfo["token"]
        ];
        $this->getRecordsController = new GetRecordsController($config);
        $this->updateRecordsController = new updateRecordsController($config);
    }


    public function getCertUrl(Request $request)
    {
        $data = $request->getContent();
        $input = json_decode($data);

        $refCode =  $input->refCode;
        $certStatus = $input->certStatus;
        $records = [
            [
                "id" => 22,
                "record" => [
                    "certStatus" => [
                        "value" => $certStatus
                    ],
                    "refCode" => [
                        "value" => $refCode
                    ]
                ]
            ]
        ];

        $this->updateRecordsController->updateRecords($this->kintoneInfo["appId"], $records);
        $result =  ["code" => 0, "description" => "success"];
        return $result;
    }

    public function applyForSign(Request $request)
    {
        $data = $request->getContent();
        $input = json_decode($data);
        $records = [
            [
                "id" => 22,
                "record" => [
                    "appCode" => [
                        "value" => $input->appCode
                    ],
                    "contractCode" => [
                        "value" => $input->contractCode
                    ],
                    "resultCode" => [
                        "value" => $input->resultCode
                    ],
                    "transactionCode" => [
                        "value" => $input->transactionCode
                    ],
                    "resultDesc" => [
                        "value" => $input->resultDesc
                    ],
                    "downloadUrl" => [
                        "value" => $input->downloadUrl
                    ],
                    "viewPdfUrl" => [
                        "value" => $input->viewPdfUrl
                    ]
                ]
            ]
        ];

        $this->updateRecordsController->updateRecords($this->kintoneInfo["appId"], $records);
        $result =  ["code" => 0, "description" => "success"];
        return $result;
    }
}
