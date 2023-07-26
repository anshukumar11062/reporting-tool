<?php

namespace App\Http\Controllers;

use App\Bll\GenerateReportBll;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * | Author - Anshu Kumar
 * | Created On-21-07-2023 
 * | Creation for - Report Creation 
 */

class ReportController extends Controller
{
    private $_generateReportBll;
    public function __construct()
    {
        $this->_generateReportBll = new GenerateReportBll;
    }

    //
    public function reportGenerate(Request $req)
    {
        try {
            $response = $this->_generateReportBll->createReport($req);
            return response($response, 200, [
                'Content-type'        => 'application/pdf',
            ]);
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }

    /**
     * | Get Query Result
     */
    public function queryResult(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'requestQuery' => 'required|string'
        ]);

        if ($validator->fails())
            return validationError($validator);
        try {
            $query = $req->requestQuery;
            $lowerQuery = Str::lower($query);
            $strQuery = Str::contains($lowerQuery, ['update', 'delete', 'insert']);
            if ($strQuery)
                throw new Exception("Unauthorized Query");
            $queryResult = DB::select($query);
            return responseMsgs(true, "Query Result", remove_null($queryResult));
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }
}
