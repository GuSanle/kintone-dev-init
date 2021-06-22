<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//获取token
Route::post('/login', 'AuthController@login');
//备份
Route::post('/backupdevdemo', 'BackupAllController@backupDevdemo');
//初始化 同步
Route::post('/synctodev', 'SyncToDevController@syncToDev');



// Route::middleware('auth:api')->post('/syncall','SyncAllController@syncall');
// Route::middleware('auth:api')->post('/syncrecords','SyncRecordsController@syncRecords');
// Route::middleware('auth:api')->post('/backupall', 'BackupAllController@backupall');
// Route::middleware('auth:api')->post('/export','GetTemplatesController@export');

// Route::middleware('auth:api')->post('/restorerecords','RestoreRecordsController@restoreRecords');


Route::get('/t', 'Kintone\GetAppRelatedInfoController@getNeedUpdateFields');
Route::get('/test', function (Request $request) {
    return 123;
});

Route::get('/getappinfo', 'AppInfoController@getAppInfo');
Route::get('/download', 'DownloadTemplatesController@download');



//电子牵
Route::post('/letsigngetCertUrl', 'LetsignController@getCertUrl');
Route::group([
    'middleware' => 'letsign'
], function () {
    Route::post('/letsignapplyForSign', 'LetsignController@applyForSign');
});





// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

//     // Route::post('backupapp', 'BackupAppController@backupApp');
//     // Route::post('syncapp', 'SyncAppController@syncApp');

// });
