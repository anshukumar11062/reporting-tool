<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ReportController;

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
    Route::post('v1/resource/deactivate', 'deactivateResource');

    /*
    |--------------------------------------------------------------------------
    | Search Group master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/group/save', 'saveGroup');
    Route::post('v1/group/update', 'updateGroup');
    Route::post('v1/group/view', 'getGroup');
    Route::post('v1/group/deactivate', 'deactivateGroup');

    /*
    |--------------------------------------------------------------------------
    | String master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/string/save', 'saveString');
    Route::post('v1/string/update', 'updateString');
    Route::post('v1/string/view', 'getString');
    Route::post('v1/string/deactivate', 'deactivateString');

    /*
    |--------------------------------------------------------------------------
    | Tempalte master
    |--------------------------------------------------------------------------
    */
    Route::post('v1/template/save', 'saveTemplate');
    Route::post('v1/template/update', 'updateTemplate');
    Route::post('v1/template/view', 'getTemplate');
    Route::post('v1/template/list', 'getTemplate');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Page Layouts
    |--------------------------------------------------------------------------
    */
    Route::post('v1/template-pl/save', 'saveTempPageLayouts');
    Route::post('v1/template-pl/update', 'updateTempPageLayouts');
    Route::post('v1/template-pl/view', 'getTempPageLayouts');

    /*
    |--------------------------------------------------------------------------
    | Tempalte Details
    |--------------------------------------------------------------------------
    */
    Route::post('v1/template-dtl/save', 'saveTempDetails');
    Route::post('v1/template-dtl/update', 'updateTempDetails');
    Route::post('v1/template-dtl/view', 'getTempDetails');

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
});

/**
 * | Report Generation Controller
 */
Route::controller(ReportController::class)->group(function () {
    Route::post('v1/report/generate', 'reportGenerate');
    Route::post('v1/report/query-result', 'queryResult');                  // Get Report Query Result
});
