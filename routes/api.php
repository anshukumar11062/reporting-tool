<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\Controller;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


/**
 * Routes for API masters, api searchable and api edit
 * CreatedOn-27-08-2022
 */

Route::controller(MasterController::class)->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Menu List
    |--------------------------------------------------------------------------
    */
    Route::post('v1/getmenu', 'MenuList');


    /*
    |--------------------------------------------------------------------------
    | Resource master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/resource/save', 'resourceSave');
    Route::post('v1/resource/update', 'resourceUpdate');
    Route::post('v1/resource/view', 'getresource');
    Route::post('v1/resource/list', 'getresource');

    /*
    |--------------------------------------------------------------------------
    | Search Group master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/group/save', 'saveGroup');
    Route::post('v1/group/update', 'updateGroup');
    Route::post('v1/group/view', 'getGroup');
    Route::post('v1/group/list', 'getGroup');

    /*
    |--------------------------------------------------------------------------
    | String master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/string/save', 'SaveString');
    Route::put('v1/string/update/{id}', 'UpdateString');
    Route::get('v1/string/view/{id}', 'GetString');
    Route::get('v1/string/list', 'GetString');

    /*
    |--------------------------------------------------------------------------
    | Tempalte master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/template/save', 'SaveTemplate');
    Route::put('v1/template/update/{id}', 'UpdateTemplate');
    Route::get('v1/template/view/{id}', 'GetTemplate');
    Route::get('v1/template/list', 'GetTemplate');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Page Layouts
    |--------------------------------------------------------------------------
    */
    Route::post('v1/templatePL/save', 'SaveTempPageLayouts');
    Route::put('v1/templatePL/update/{id}', 'UpdateTempPageLayouts');
    Route::get('v1/templatePL/view/{id}', 'GetTempPageLayouts');
    Route::get('v1/templatePL/list', 'GetTempPageLayouts');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Details
    |--------------------------------------------------------------------------
    */
    Route::post('v1/templateDtl/save', 'SaveTempDetails');
    Route::put('v1/templateDtl/update/{id}', 'UpdateTempDetails');
    Route::get('v1/templateDtl/view/{id}', 'GetTempDetails');
    Route::get('v1/templateDtl/list', 'GetTempDetails');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Footer
    |--------------------------------------------------------------------------
    */
    Route::post('v1/templateFtr/save', 'SaveTempFooter');
    Route::put('v1/templateFtr/update/{id}', 'UpdateTempFooters');
    Route::get('v1/templateFtr/view/{id}', 'GetTempFooters');
    Route::get('v1/templateFtr/list', 'GetTempFooters');
});

Route::controller(Controller::class)->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Return report template
    |--------------------------------------------------------------------------
    */
    Route::post('v1/getreport/template', 'GenPDFArr');
    Route::post('v1/getreport/template1', 'check');
});
