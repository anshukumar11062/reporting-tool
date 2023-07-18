<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "template" => "required|array",                         // Template Validation
            "template.searchGroupId" => "required|integer",
            "template.templateCode" => "required|string",
            "template.templateName" => "required|string",
            "template.paperSizeEnum" => "required|string",
            "template.detailLayout" => "required|string",
            "template.headerHeight" => "required|integer",
            "template.headerHeightPage2" => "required|integer",
            "template.footerHeight" => "required|integer",
            "template.detailLineSpacing" => "required|integer",
            "template.layoutSql" => "required|string",
            "template.detailSql" => "required|string",
            "template.footerSql" => "required|string",
            "template.isDefault" => "required|bool",
            "template.isLandscape" => "required|bool",
            "template.isGlobalHeader" => "required|bool",
            "template.isRenderGlobalHeader" => "required|bool",
            "template.isPageLayoutInPager2" => "required|bool",
            "template.groupbyExpression" => "nullable|string",
            "template.isShowGridLine" => "required|bool",
            "template.headerDistance" => "required|integer",
            "template.screenDisplayString" => "required|string",
            "template.parentId" => "nullable|integer",
            "template.labelRowCount" => "nullable|integer",
            "template.labelColumnCount" => "nullable|integer",
            "template.isDetailWordwrap" => "required|bool",
            "template.isCompactFooter" => "required|bool",

            "layouts" => 'required|array',                      // Layout Validation
            'layouts.*.fieldType' => 'required',
            'layouts.*.caption' => 'required',
            'layouts.*.fieldName' => 'required',
            'layouts.*.pageNo' => 'required',
            'layouts.*.x' => 'required',
            'layouts.*.y' => 'required',
            'layouts.*.width' => 'required',
            'layouts.*.height' => 'required',
            'layouts.*.fontName' => 'required',
            'layouts.*.fontSize' => 'required',
            'layouts.*.isUnderline' => 'required|bool',
            'layouts.*.isBold' => 'required|bool',
            'layouts.*.isItalic' => 'required|bool',
            'layouts.*.isVisible' => 'required|bool',
            'layouts.*.alignment' => 'required',
            'layouts.*.color' => 'required',
            'layouts.*.file' =>  'mimes:jpeg,jpg,png,gif|nullable|max:10000', // max 10000kb,


            'details' => 'required|array',                      // Details Validation
            'details.*.x' => 'required|numeric',
            'details.*.y' => 'required|numeric',
            'details.*.fieldType' => 'required|string',
            'details.*.fieldName' => 'required|string',
            'details.*.fontName' => 'required|string',
            'details.*.fontSize' => 'required|string',
            'details.*.width' => 'required|integer',
            'details.*.isUnderline' => 'required|bool',
            'details.*.isBold' => 'required|bool',
            'details.*.isItalic' => 'required|bool',
            'details.*.isVisible' => 'required|bool',
            'details.*.isBoxed' => 'required|bool',
            'details.*.alignment' => 'required|string',
            'details.*.color' => 'required|string',

            'footer' => 'required|array',
            'footer.*.serialNo' => 'required|integer',
            'footer.*.fieldType' => 'required',
            'footer.*.caption' => 'required',
            'footer.*.fieldName' => 'required|string',
            // 'footer.resource' => 'required|string',
            'footer.*.x' => 'required|numeric',
            'footer.*.y' => 'required|numeric',
            'footer.*.width' => 'required|numeric',
            'footer.*.height' => 'required|numeric',
            'footer.*.fontname' => 'required',
            'footer.*.size' => 'required',
            'footer.*.isUnderline' => 'required|bool',
            'footer.*.isBold' => 'required|bool',
            'footer.*.isItalic' => 'required|bool',
            'footer.*.isVisible' => 'required|bool',
            'footer.*.isBoxed' => 'required|bool',
            'footer.*.alignment' => 'required|string',
            'footer.*.color' => 'required|string',
        ];
    }

    // Validation Error Message
    protected function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => false,
                    'message' => 'The given data was invalid',
                    'errors' => $validator->errors()
                ],
                422
            )
        );
    }
}
