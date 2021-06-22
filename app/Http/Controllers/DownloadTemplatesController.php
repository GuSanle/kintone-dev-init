<?php

namespace App\Http\Controllers;

use Request;
use App\Utils\Utils;
use Arr;
use Storage;


class DownloadTemplatesController extends Controller
{




    public function __construct()
    {
    }

    public function download()
    {
        // return Storage::get('store.sptpl');
        $pathToFile = Storage::disk('local')->path('store.sptpl');
        return response()->download($pathToFile, 'store.sptpl');
        // return response()->stream(function () {
        //     // $contents;
        // });
    }
}
