<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Repository\PdfRepository as PdfRepository;
use App\Repository\Layout as Layout;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $pdfapi;
    public $layout;

    public function __construct(PdfRepository $pdfapi, Layout $layout)
    {
        $this->Pdfapi = $pdfapi;
        $this->layout = $layout;
    }




    /*
    *****************************************************************************
    * Generate PDF Report through request id
    *****************************************************************************
    */
    function GenPDF(Request $req)
    {

        $report = (array)$this->Pdfapi->GetTempData($req->id);
        if($report)
        {
            $report['layout'] = array();
            $report['layout_data'] = array();
            $report['details'] = array();
            $report['details_data'] = array();
            $report['footer'] = array();
            $report['footer_data'] = array();
            
            foreach($this->Pdfapi->GetTempPageLayout($req->id) as $layout)
                $report['layout'][] = $layout;
            
            foreach($this->Pdfapi->GetDatafromQuery($report['layout_sql']) as $layout)
                $report['layout_data'][] = $layout;
            
            foreach($this->Pdfapi->GetTempDetails($req->id) as $details)
                $report['details'][] = $details;

            foreach($this->Pdfapi->GetDatafromQuery($report['detail_sql']) as $data)
                $report['details_data'][] = $data;
            
            foreach($this->Pdfapi->GetTempFooter($req->id) as $footer)
                $report['footer'][] = $footer;

            foreach($this->Pdfapi->GetDatafromQuery($report['footer_sql']) as $foo)
                $report['footer_data'][] = $foo;
            
            //echo '<pre/>';print_r($report);
            
            $this->layout->create_report($report);
            exit;
        }else{
            return response()->json(['status'=> 0, 'msg'=> 'Record not found!'], 400);
        }

        
    }

    
    /*
    *****************************************************************************
    * Generate PDF Report through request array
    *****************************************************************************
    */
    function GenPDFArr(Request $reqarr)
    {
        
        if($reqarr)
        {
            foreach($this->Pdfapi->GetDatafromQuery($reqarr['layout_sql']) as $layout)
                $reqarr['layout_data'][] = $layout;
            
            foreach($this->Pdfapi->GetDatafromQuery($reqarr['detail_sql']) as $data)
                $reqarr['details_data'][] = $data;

            foreach($this->Pdfapi->GetDatafromQuery($reqarr['footer_sql']) as $foo)
                $reqarr['footer_data'][] = $foo;

            if(empty($reqarr['layout_sql']))
                $reqarr['layout_data'] = array();
            
            if(empty($reqarr['detail_sql']))
                $reqarr['details_data'] = array();
            
            if(empty($reqarr['footer_sql']))
                $reqarr['footer_data'] = array();
            
            
            return response(($this->layout->create_report($reqarr)), 200, [
                'Content-type'        => 'application/pdf',
            ]);
            
            //return $this->layout->create_report($reqarr);
            
        }else{
            return response()->json(['status'=> 0, 'msg'=> 'Record not found!'], 400);
        }
        
    }
    
    function check(Request $reqarr)
    {
        $url = "http://192.168.0.237:8000/api/getreport/template";
        //print_r($reqarr->request);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $res = json_encode((array)$reqarr->request);
        $data = $res;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        curl_close($curl);

        $file = fopen("test.pdf","w");
        fwrite($file, $resp);
        fclose($file);
                //return stream_filter_append($this->GenPDFArr($reqarr), "convert.base64-encode", STREAM_FILTER_WRITE);
                
                // $file = fopen("test.pdf","w");
                // fwrite($file, $data);
                // fclose($file);
        return response()->json(['status'=> 1, 'msg'=> 'File generated successfully'], 400);

    }
 
}
