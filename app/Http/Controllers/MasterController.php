<?php

namespace App\Http\Controllers;

use App\Repository\Api\MasterApiRepository as MasterApiRepository;
use App\Models\VtResource;
use App\Models\VtSearchGroup;
use App\Models\VtString;
use App\Models\VtTemplateDeatil;
use App\Models\VtTemplatePagelayout;
use App\Models\VtTemplateFooter;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\Resource as ResourceRequest;
use App\Http\Requests\TemplateRequest;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Exception;


/*******************************************************************************
 * Report tool api                                                              *
 *                                                                              *
 * Version: 1.0                                                                 *
 * Date:    2022-08-26                                                          *
 * Author:  Shashi Kumar Sharma                                                 *
 *******************************************************************************/

class MasterController extends Controller
{
    protected $mstr;
    public $pdfapi;
    public $layout;
    private $_mstr;

    public function __construct(MasterApiRepository $mstr)
    {
        $this->_mstr = $mstr;
    }
    /************** Resource Master Start **************/


    // For save data in vt_resources table
    public function resourceSave(ResourceRequest $resource)
    {

        try {
            $imagePath = "";
            if ($resource->image) {
                $imagePath = time() . '.' . $resource->image->extension();
                $resource->image->move(public_path('images'), $imagePath);
            }

            $vtres = new VtResource();
            $vtres->resource_name = $resource->resource_name;
            $vtres->image_path = $imagePath;
            $vtres->status = 1;
            $vtres->save();
            return responseMsgs(true, "Successfully Saved", []);
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }

    // For update data in vt_resources table
    public function resourceUpdate(Request $resource)
    {
        $validator = Validator::make($resource->all(), [
            'id' => 'required|integer',
            'resource_name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        ]);

        if ($validator->fails()) {
            return validationError($validator);
        }
        try {
            $res = VtResource::find($resource->id);
            if ($res) {
                $imagePath = "";
                if ($resource->image) {
                    $imagePath = time() . '.' . $resource->image->extension();
                    $resource->image->move(public_path('images'), $imagePath);
                }

                $res->resource_name = $resource->resource_name;
                $res->image_path = $imagePath;
                $res->save();
                return responseMsgs(true, "Updated Successfully", []);
            } else {
                throw new Exception("id not found");
            }
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }

    // For get data according to the id in vt_resources table
    public function getresource(Request $resource)
    {
        try {
            $arr = array();
            if ($resource->id) {
                $arr = DB::table('vt_resources')->where('id', $resource->id)->first();
            } else {
                $res = DB::table('vt_resources')->orderByDesc('id')->get();
                foreach ($res as $data) {
                    $val['id'] = $data->id;
                    $val['resource_name'] = $data->resource_name;
                    $val['image_path'] = $data->image_path;
                    $val['status'] = $data->status;
                    array_push($arr, $val);
                }
            }

            return responseMsgs(true, "Fetched Data", remove_null($arr));
        } catch (Exception $e) {
            return responseMsgs(true, $e->getMessage(), []);
        }
    }
    /************** Resource Master End **************/

    /************** Search Group master Start **************/

    // For save data in vt_search_groups table
    public function saveGroup(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'searchGroup' => 'required',
            'isReport' => 'required'
        ]);

        if ($validator->fails())
            return validationError($validator);

        try {

            $vtgrp = new VtSearchGroup();
            $vtgrp->search_group = $data->searchGroup;
            $vtgrp->is_report = ($data->isReport == 'Yes') ? true : false;
            $vtgrp->status = 1;
            $vtgrp->parent_id = isset($data->parentId) ? $data->parentId : null;
            $vtgrp->save();
            return response()->json(['status' => true, 'Message' => "Save successfully"], 200);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    // For update data in vt_search_groups table
    public function updateGroup(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'id' => 'required|integer',
            'searchGroup' => 'required',
            'isReport' => 'required'
        ]);

        if ($validator->fails()) {
            return validationError($validator);
        }
        try {
            $res = VtSearchGroup::find($data->id);
            if ($res) {
                $res->search_group = $data->searchGroup;
                $res->is_report = ($data->isReport == 'Yes') ? true : false;
                $res->status = 1;
                $res->parent_id = isset($data->parentId) ? $data->parentId : null;
                $res->save();
                return responseMsgs(true, "Updated Successfully", []);
            } else {
                throw new Exception("Id not Found");
            }
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }

    // Get data for view and list in vt_search_groups table
    public function GetGroup(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $arr = DB::table('vt_search_groups')->where('id', $resource->id)->first(); // Particular single record based on id
            } else {
                $res = DB::table('vt_search_groups')->where('status', 1)->orderByDesc('id')->get(); // All records from table
                foreach ($res as $data) {
                    $isReport = 'No';
                    if ($data->is_report == true) {
                        $isReport = 'Yes';
                    }

                    $val['id'] = $data->id;
                    $val['search_group'] = $data->search_group;
                    $val['is_report'] = $isReport;
                    $val['status'] = $data->status;
                    $val['parent_id'] = $data->parent_id;
                    array_push($arr, $val);
                }
            }
            return responseMsgs(true, "Fetched Data", remove_null($arr));
        } catch (Exception $e) {
            return responseMsgs(false, $e->getMessage(), []);
        }
    }
    /************** Search Group master End **************/


    /************** String master Start **************/

    // For save data in vt_strings table
    public function SaveString(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'fieldName' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->messages()]);
        }

        try {

            $vtstr = new VtString();
            $vtstr->field_name = $data->fieldName;
            $vtstr->description = $data->description;
            $vtstr->status = 1;
            $vtstr->save();
            return response()->json(['status' => true, 'Message' => "Save successfully"], 200);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    // For update data in vt_strings table
    public function UpdateString(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'fieldName' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->messages()]);
        }
        try {
            $res = VtString::find($data->id);
            if ($res) {
                $res->field_name = $data->fieldName;
                $res->description = $data->description;
                $res->save();

                return response()->json(['status' => true, 'Message' => "Updated successfully"], 200);
            } else {
                return response()->json('Id Not Found', 404);
            }
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    // Get data for view and list in vt_strings table
    public function GetString(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $res = DB::table('vt_strings')->where('id', $resource->id)->get(); // Particular single record based on id
            } else {
                $res = DB::table('vt_strings')->where('status', 1)->orderByDesc('id')->get(); // All records from table
            }

            foreach ($res as $data) {

                $val['id'] = $data->id;
                $val['field_name'] = $data->field_name;
                $val['description'] = $data->description;
                $val['status'] = $data->status;
                array_push($arr, $val);
            }
            return response($arr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    /************** String master End **************/

    /************** Create template Start **************/

    // For save data in vt_templates table
    public function SaveTemplate(Request $request)
    {

        // if ($validator->fails()) {    
        //     return response()->json(['Message' => $validator->messages()]);
        // }
        //$validated = $request->isValidFile(); 
        //$validator = Validator::validate($request);
        //dd($this->failedValidation($request));
        return $this->_mstr->InsTemplate($request);
    }

    // For update data in vt_strings table
    public function UpdateTemplate(Request $data)
    {
        //$tempate = new VtTemplate();
        // $validator = Validator::make($data->all(), [
        //     'templateCode' => 'required',
        //     'templateName' => 'required',
        //     'paperSizeEnum' => 'required',
        //     'detailLayout' => 'required',
        //     'headerHeight' => 'required',
        //     'headerHeightPage2' => 'required',
        //     'footerHeight' => 'required',
        //     'detailLineSpacing' => 'required',
        //     'layoutSql' => 'required',
        //     'detailSql' => 'required',
        //     'footerSql' => 'required'
        // ]);

        // if ($validator->fails()) {    
        //     return response()->json(['Message' => $validator->messages()]);
        // }
        try {
            return $this->_mstr->upTemplate($data);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    //Get data for view and list in vt_templates table
    public function GetTemplate(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $res = DB::table('vt_templates')->where('id', $resource->id)->get(); // Particular single record based on id
            } else {
                $res = DB::table('vt_templates')->where('status', 1)->orderByDesc('id')->get(); // All records from table
            }

            foreach ($res as $data) {

                $val['id'] = $data->id;
                $val['template_code'] = $data->template_code;
                $val['template_name'] = $data->template_name;
                $val['paper_size_enum'] = $data->paper_size_enum;
                $val['detail_layout'] = $data->detail_layout;
                $val['header_height'] = $data->header_height;
                $val['header_height_page2'] = $data->header_height_page2;
                $val['footer_height'] = $data->footer_height;
                $val['detail_line_spacing'] = $data->detail_line_spacing;
                $val['layout_sql'] = $data->layout_sql;
                $val['detail_sql'] = $data->detail_sql;
                $val['footer_sql'] = $data->footer_sql;
                $val['is_default'] = $data->is_default;
                $val['is_landscape'] = $data->is_landscape;
                $val['is_global_header'] = $data->is_global_header;
                $val['is_render_global_header'] = $data->is_render_global_header;
                $val['is_page_layout_in_pager2'] = $data->is_page_layout_in_pager2;
                $val['groupby_expression'] = $data->groupby_expression;
                $val['is_show_grid_line'] = $data->is_show_grid_line;
                $val['header_distance'] = $data->header_distance;
                $val['screen_display_string'] = $data->screen_display_string;
                $val['parent_id'] = $data->parent_id;
                $val['label_row_count'] = $data->label_row_count;
                $val['label_column_count'] = $data->label_column_count;
                $val['is_detail_wordwrap'] = $data->is_detail_wordwrap;
                $val['is_compact_footer'] = $data->is_compact_footer;
                $val['status'] = $data->status;
                array_push($arr, $val);
            }
            return response($arr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    /************** Create template End **************/

    /************** Template page layout Start **************/

    // For save data in vt_template_pagelayouts table
    public function SaveTempPageLayouts(Request $datas)
    {
        //echo count($datas);

        try {

            foreach ($datas->request as $res) {
                $validator = Validator::make($datas->all(), [
                    'reportTemplate_id.*' => 'required',
                    'fieldType.*' => 'required',
                    'pageNo.*' => 'required',
                    'x.*' => 'required',
                    'y.*' => 'required',
                    'width.*' => 'required',
                    'height.*' => 'required',
                    'fontName.*' => 'required',
                    'fontSize.*' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['Message' => $validator->messages()]);
                }

                $data = (object)$res;

                $imagePath = "";
                if ($data->file) {
                    $imagePath = time() . '.JPG';
                    file_put_contents(public_path('images') . "/" . $imagePath, $data->file);

                    //$resource->image->move(public_path('images'), $imagePath);
                }

                $temp_layout = new VtTemplatePagelayout();
                $temp_layout->report_template_id = $data->reportTemplate_id;
                $temp_layout->field_type = $data->fieldType;
                $temp_layout->caption = $data->caption;
                $temp_layout->field_name = $data->fieldName;
                $temp_layout->resource = $imagePath;
                $temp_layout->page_no = $data->pageNo;
                $temp_layout->x = $data->x;
                $temp_layout->y = $data->y;
                $temp_layout->width = $data->width;
                $temp_layout->height = $data->height;
                $temp_layout->font_name = $data->fontName;
                $temp_layout->font_size = $data->fontSize;
                $temp_layout->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_layout->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_layout->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_layout->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_layout->alignment = $data->alignment;
                $temp_layout->color = $data->color;
                $temp_layout->status = 1;
                $temp_layout->save();
            }
            return response()->json(['status' => true, 'Message' => "Save successfully"], 200);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
        //return response()->json(['status' =>$data->resource], 200);
    }

    // For update data in vt_template_pagelayouts table
    public function UpdateTempPageLayouts(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'reportTemplateId' => 'required',
            'fieldType' => 'required',
            'pageNo' => 'required',
            'x' => 'required',
            'y' => 'required',
            'width' => 'required',
            'height' => 'required',
            'fontName' => 'required',
            'fontSize' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->messages()]);
        }
        try {
            $temp_layout = VtTemplatePagelayout::find($data->id);
            if ($temp_layout) {
                $temp_layout->report_template_id = $data->templateId;
                $temp_layout->field_type = $data->fieldType;
                $temp_layout->caption = $data->caption;
                $temp_layout->field_name = $data->fieldName;
                $temp_layout->resource = $data->resource;
                $temp_layout->page_no = $data->pageNo;
                $temp_layout->x = $data->x;
                $temp_layout->y = $data->y;
                $temp_layout->width = $data->width;
                $temp_layout->height = $data->height;
                $temp_layout->font_name = $data->fontName;
                $temp_layout->font_size = $data->fontSize;
                $temp_layout->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_layout->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_layout->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_layout->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_layout->alignment = $data->alignment;
                $temp_layout->save();

                return response()->json(['status' => true, 'Message' => "Updated successfully"], 200);
            } else {
                return response()->json('Id Not Found', 404);
            }
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    //Get data for view and list in vt_template_pagelayouts table
    public function GetTempPageLayouts(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $res = DB::table('vt_template_pagelayouts')
                    ->where('id', $resource->id)
                    ->get(); // Particular single record based on id
            } else {
                $res = DB::table('vt_template_pagelayouts')
                    ->where('status', 1)
                    ->orderByDesc('id')
                    ->get(); // All records from table
            }

            foreach ($res as $data) {

                $val['id'] = $data->id;
                $val['report_template_id'] = $data->report_template_id;
                $val['field_type'] = $data->field_type;
                $val['caption'] = $data->caption;
                $val['field_name'] = $data->field_name;
                $val['resource'] = $data->resource;
                $val['page_no'] = $data->page_no;
                $val['x'] = $data->x;
                $val['y'] = $data->y;
                $val['width'] = $data->width;
                $val['height'] = $data->height;
                $val['font_name'] = $data->font_name;
                $val['font_size'] = $data->font_size;
                $val['is_underline'] = $data->is_underline;
                $val['is_bold'] = $data->is_bold;
                $val['is_italic'] = $data->is_italic;
                $val['is_visible'] = $data->is_visible;
                $val['alignment'] = $data->alignment;
                $val['color'] = $data->color;
                $val['status'] = $data->status;
                array_push($arr, $val);
            }
            return response($arr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    /************** Template page layout End **************/

    /************** Template details Start **************/

    // For save data in vt_template_deatils table
    public function SaveTempDetails(Request $datas)
    {
        // $validator = Validator::make($data->all(), [
        //     'reportTemplateId' => 'required',
        //     'fieldType' => 'required',
        //     'x' => 'required',
        //     'y' => 'required',
        //     'width' => 'required',
        //     'fontName' => 'required',
        //     'fontSize' => 'required',
        // ]);

        // if ($validator->fails()) {    
        //     return response()->json(['Message' => $validator->messages()]);
        // }

        try {
            foreach ($datas->request as $res) {
                $data = (object)$res;
                $temp_dtls = new VtTemplateDeatil();
                $temp_dtls->report_template_id = $data->reportTemplate_id;
                $temp_dtls->x = $data->x;
                $temp_dtls->y = $data->y;
                $temp_dtls->field_type = $data->fieldType;
                $temp_dtls->field_name = $data->fieldName;
                $temp_dtls->font_name = $data->fontName;
                $temp_dtls->font_size = $data->fontSize;
                $temp_dtls->width = $data->width;
                $temp_dtls->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_dtls->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_dtls->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_dtls->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_dtls->is_boxed = ($data->isBoxed == 'Yes') ? true : false;
                $temp_dtls->alignment = $data->alignment;
                $temp_dtls->color = $data->color;
                $temp_dtls->status = 1;
                $temp_dtls->save();
            }
            return response()->json(['status' => true, 'Message' => "Save successfully"], 200);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    // For update data in vt_template_deatils table
    public function UpdateTempDetails(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'reportTemplateId' => 'required',
            'fieldType' => 'required',
            'x' => 'required',
            'y' => 'required',
            'width' => 'required',
            'fontName' => 'required',
            'fontSize' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->messages()]);
        }
        try {
            $temp_dtls = VtTemplateDeatil::find($data->id);
            if ($temp_dtls) {
                $temp_dtls->report_template_id = $data->templateId;
                $temp_dtls->x = $data->x;
                $temp_dtls->y = $data->y;
                $temp_dtls->field_type = $data->fieldType;
                $temp_dtls->field_name = $data->fieldName;
                $temp_dtls->font_name = $data->fontName;
                $temp_dtls->font_size = $data->fontSize;
                $temp_dtls->width = $data->width;
                $temp_dtls->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_dtls->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_dtls->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_dtls->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_dtls->is_boxed = ($data->isBoxed == 'Yes') ? true : false;
                $temp_dtls->alignment = $data->alignment;
                $temp_dtls->color = $data->color;
                $temp_dtls->save();

                return response()->json(['status' => true, 'Message' => "Updated successfully"], 200);
            } else {
                return response()->json('Id Not Found', 404);
            }
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    //Get data for view and list in vt_template_deatils table
    public function GetTempDetails(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $res = DB::table('vt_template_deatils')
                    ->where('id', $resource->id)
                    ->get(); // Particular single record based on id
            } else {
                $res = DB::table('vt_template_deatils')
                    ->where('status', 1)
                    ->orderByDesc('id')
                    ->get(); // All records from table
            }

            foreach ($res as $data) {

                $val['id'] = $data->id;
                $val['report_template_id'] = $data->report_template_id;
                $val['field_type'] = $data->field_type;
                $val['field_name'] = $data->field_name;
                $val['x'] = $data->x;
                $val['y'] = $data->y;
                $val['width'] = $data->width;
                $val['height'] = $data->height;
                $val['font_name'] = $data->font_name;
                $val['font_size'] = $data->font_size;
                $val['is_underline'] = $data->is_underline;
                $val['is_bold'] = $data->is_bold;
                $val['is_italic'] = $data->is_italic;
                $val['is_visible'] = $data->is_visible;
                $val['is_boxed'] = $data->is_boxed;
                $val['alignment'] = $data->alignment;
                $val['color'] = $data->color;
                $val['status'] = $data->status;
                array_push($arr, $val);
            }
            return response($arr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    /************** Template Details End **************/

    /************** Template footer Start **************/

    // For save data in vt_template_footers table
    public function SaveTempFooter(Request $datas)
    {
        // $validator = Validator::make($data->all(), [
        //     'reportTemplateId' => 'required',
        //     'fieldType' => 'required',
        //     'x' => 'required',
        //     'y' => 'required',
        //     'width' => 'required',
        //     'fontname' => 'required',
        //     'size' => 'required',
        // ]);

        // if ($validator->fails()) {    
        //     return response()->json(['Message' => $validator->messages()]);
        // }

        try {
            foreach ($datas->request as $res) {
                $data = (object)$res;
                $temp_footer = new VtTemplateFooter();
                $temp_footer->report_template_id = $data->reportTemplate_id;
                $temp_footer->serial_no = $data->serialNo;
                $temp_footer->field_type = $data->fieldType;
                $temp_footer->caption = $data->caption;
                $temp_footer->field_name = $data->fieldName;
                //$temp_footer->resource = $data->resource;
                $temp_footer->x = $data->x;
                $temp_footer->y = $data->y;
                $temp_footer->width = $data->width;
                $temp_footer->height = $data->height;
                $temp_footer->fontname = $data->fontname;
                $temp_footer->size = $data->size;
                $temp_footer->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_footer->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_footer->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_footer->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_footer->alignment = $data->alignment;
                $temp_footer->color = $data->color;
                $temp_footer->status = 1;
                $temp_footer->save();
            }
            return response()->json(['status' => true, 'Message' => "Save successfully"], 200);
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    // For update data in vt_template_footers table
    public function UpdateTempFooters(Request $data)
    {
        $validator = Validator::make($data->all(), [
            'reportTemplate_id' => 'required',
            'fieldType' => 'required',
            'x' => 'required',
            'y' => 'required',
            'width' => 'required',
            'fontname' => 'required',
            'size' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['Message' => $validator->messages()]);
        }
        try {
            $temp_footer = VtTemplateFooter::find($data->id);
            if ($temp_footer) {
                $temp_footer->report_template_id = $data->templateId;
                $temp_footer->serial_no = $data->serialNo;
                $temp_footer->field_type = $data->fieldType;
                $temp_footer->caption = $data->caption;
                $temp_footer->field_name = $data->fieldName;
                $temp_footer->resource = $data->resource;
                $temp_footer->x = $data->x;
                $temp_footer->y = $data->y;
                $temp_footer->width = $data->width;
                $temp_footer->height = $data->height;
                $temp_footer->fontname = $data->fontname;
                $temp_footer->size = $data->size;
                $temp_footer->is_underline = ($data->isUnderline == 'Yes') ? true : false;
                $temp_footer->is_bold = ($data->isBold  == 'Yes') ? true : false;
                $temp_footer->is_italic = ($data->isItalic  == 'Yes') ? true : false;
                $temp_footer->is_visible = ($data->isVisible == 'Yes') ? true : false;
                $temp_footer->alignment = $data->alignment;
                $temp_footer->color = $data->color;
                $temp_footer->save();

                return response()->json(['status' => true, 'Message' => "Updated successfully"], 200);
            } else {
                return response()->json('Id Not Found', 404);
            }
        } catch (Exception $e) {
            return response()->json([$e, 400]);
        }
    }

    //Get data for view and list in vt_template_footers table
    public function GetTempFooters(Request $resource)
    {
        try {
            $arr = array();
            // Check id found form request
            if ($resource->id) {
                $res = DB::table('vt_template_footers')
                    ->where('id', $resource->id)
                    ->get(); // Particular single record based on id
            } else {
                $res = DB::table('vt_template_footers')
                    ->where('status', 1)
                    ->orderByDesc('id')
                    ->get(); // All records from table
            }

            foreach ($res as $data) {

                $val['id'] = $data->id;
                $val['report_template_id'] = $data->report_template_id;
                $val['serial_no'] = $data->serial_no;
                $val['field_type'] = $data->field_type;
                $val['caption'] = $data->caption;
                $val['field_name'] = $data->field_name;
                $val['resource'] = $data->resource;
                $val['x'] = $data->x;
                $val['y'] = $data->y;
                $val['width'] = $data->width;
                $val['height'] = $data->height;
                $val['fontname'] = $data->fontname;
                $val['size'] = $data->size;
                $val['is_underline'] = $data->is_underline;
                $val['is_bold'] = $data->is_bold;
                $val['is_italic'] = $data->is_italic;
                $val['is_visible'] = $data->is_visible;
                $val['alignment'] = $data->alignment;
                $val['color'] = $data->color;
                $val['status'] = $data->status;
                array_push($arr, $val);
            }
            return response($arr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    /************** Template Footers End **************/

    public function MenuList()
    {
        try {
            $menuarr = array();

            $grpList = DB::table('vt_search_groups')
                ->where('status', 1)
                ->get();
            $arr = array();
            $subarr = array();
            $pid = 0;
            foreach ($grpList as $grp) {
                //if((empty($grp->parent_id) || $grp->parent_id == 0))
                {
                    $pid = $grp->id;
                    $arr['menu_id'] = $grp->id;
                    $arr['menu_name'] = $grp->search_group;
                    $arr['type'] = 'Parent Group';
                    $arr['sub_menu'] = $this->ChildMenu($grp->id);
                    array_push($menuarr, $arr);
                }
            }

            return response($menuarr, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function ChildMenu($parentmenu_id)
    {
        $menuarr = array();
        $tempList = DB::table('vt_templates')
            ->where('search_group_id', $parentmenu_id)
            ->get();

        foreach ($tempList as $temp) {
            $childarr['menu_id'] = $temp->id;
            $childarr['menu_name'] = $temp->template_name;
            $childarr['menu_code'] = $temp->template_code;
            $childarr['type'] = 'Child template';
            $submenu = array();
            if ($temp->detail_layout == 'General' || $temp->detail_layout == 'Form') {
                $childarr1['menu_id'] = 1;
                $childarr1['menu_name'] = 'Layout';
                $childarr1['menu_code'] = 'Layout';
                $childarr1['type'] = $temp->detail_layout . ' template';
                array_push($submenu, $childarr1);

                $childarr2['menu_id'] = 2;
                $childarr2['menu_name'] = 'Details';
                $childarr2['menu_code'] = 'Details';
                $childarr2['type'] = $temp->detail_layout . ' template';
                array_push($submenu, $childarr2);

                $childarr3['menu_id'] = 3;
                $childarr3['menu_name'] = 'Footer';
                $childarr3['menu_code'] = 'Footer';
                $childarr3['type'] = $temp->detail_layout . ' template';
                array_push($submenu, $childarr3);
            }

            if ($temp->detail_layout == 'Label' || $temp->detail_layout == 'Document') {
                $childarr1['menu_id'] = 1;
                $childarr1['menu_name'] = 'Layout';
                $childarr1['menu_code'] = 'Layout';
                $childarr1['type'] = $temp->detail_layout . ' template';
                array_push($submenu, $childarr1);
            }
            $childarr['submenu'] = $submenu;
            array_push($menuarr, $childarr);
        }
        //echo "<pre/>";print_r($menuarr);
        return $menuarr;
    }
}
