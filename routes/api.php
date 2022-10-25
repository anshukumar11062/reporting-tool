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
    Route::get('getmenu', 'MenuList');


    /*
    |--------------------------------------------------------------------------
    | Resource master
    |--------------------------------------------------------------------------
    */
    Route::post('resource/save', 'resource_save');
    Route::put('resource/update/{id}', 'resource_update');
    Route::get('resource/view/{id}', 'getresource');
    Route::get('resource/list', 'getresource');

    /*
    |--------------------------------------------------------------------------
    | Search Group master
    |--------------------------------------------------------------------------
    */
    Route::post('group/save', 'SaveGroup');
    Route::put('group/update/{id}', 'UpdateGroup');
    Route::get('group/view/{id}', 'GetGroup');
    Route::get('group/list', 'GetGroup');

    /*
    |--------------------------------------------------------------------------
    | String master
    |--------------------------------------------------------------------------
    */
    Route::post('string/save', 'SaveString');
    Route::put('string/update/{id}', 'UpdateString');
    Route::get('string/view/{id}', 'GetString');
    Route::get('string/list', 'GetString');

    /*
    |--------------------------------------------------------------------------
    | Tempalte master
    |--------------------------------------------------------------------------
    */
    Route::post('template/save', 'SaveTemplate');
    Route::put('template/update/{id}', 'UpdateTemplate');
    Route::get('template/view/{id}', 'GetTemplate');
    Route::get('template/list', 'GetTemplate');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Page Layouts
    |--------------------------------------------------------------------------
    */
    Route::post('templatePL/save', 'SaveTempPageLayouts');
    Route::put('templatePL/update/{id}', 'UpdateTempPageLayouts');
    Route::get('templatePL/view/{id}', 'GetTempPageLayouts');
    Route::get('templatePL/list', 'GetTempPageLayouts');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Details
    |--------------------------------------------------------------------------
    */
    Route::post('templateDtl/save', 'SaveTempDetails');
    Route::put('templateDtl/update/{id}', 'UpdateTempDetails');
    Route::get('templateDtl/view/{id}', 'GetTempDetails');
    Route::get('templateDtl/list', 'GetTempDetails');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Footer
    |--------------------------------------------------------------------------
    */
    Route::post('templateFtr/save', 'SaveTempFooter');
    Route::put('templateFtr/update/{id}', 'UpdateTempFooters');
    Route::get('templateFtr/view/{id}', 'GetTempFooters');
    Route::get('templateFtr/list', 'GetTempFooters');
});

Route::controller(Controller::class)->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Return report template
    |--------------------------------------------------------------------------
    */
    Route::post('getreport/template', 'GenPDFArr');
    Route::post('getreport/template1', 'check');
});
