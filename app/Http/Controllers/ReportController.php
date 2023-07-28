<?php

namespace App\Http\Controllers;

use App\BLL\GenerateReportBll;
use App\BLL\GenerateSearchReportBll;
use App\BLL\GetTemplateByIdBll;
use App\Models\VtSearchGroup;
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
    public function generateReport(Request $req)
    {
        try {
            $mVtSearchGroup = new VtSearchGroup();
            $searchGroup = $mVtSearchGroup::find($req->template['searchGroupId']);
            if (collect($searchGroup)->isEmpty())
                throw new Exception("Search Group not Available");
            // preview Only available for pdf reports
            if ($searchGroup->is_report == true) {
                $response = $this->_generateReportBll->createReport($req);
                return response($response, 200, [
                    'Content-type'        => 'application/pdf',
                ]);
            } else
                throw new Exception("Preview Not Available for the Search Reports");
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

    /**
     * | Generate Search Type Reports
     */
    public function generateSearchReport(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required|integer'
        ]);

        if ($validator->fails())
            return validationError($validator);

        try {
            $getTemplateByIdBll = new GetTemplateByIdBll;
            $generateSearchReportBll = new GenerateSearchReportBll;
            $template = $getTemplateByIdBll->getTemplate($req->id);
            $response = $generateSearchReportBll->generate($req, $template);
            return responseMsgs(true, "Template Details", remove_null($response));
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }
}
