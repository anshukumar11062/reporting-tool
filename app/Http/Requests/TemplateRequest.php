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
            "searchGroupId" => "required|integer",
            "templateCode" => "required|string",
            "templateName" => "required|string",
            "paperSizeEnum" => "required|string",
            "detailLayout" => "required|string",
            "headerHeight" => "required|integer",
            "headerHeightPage2" => "required|integer",
            "footerHeight" => "required|integer",
            "detailLineSpacing" => "required|integer",
            "layoutSql" => "required|string",
            "detailSql" => "required|string",
            "footerSql" => "required|string",
            "isDefault" => "required|bool",
            "isLandscape" => "required|bool",
            "isGlobalHeader" => "required|bool",
            "isRenderGlobalHeader" => "required|bool",
            "isPageLayoutInPager2" => "required|bool",
            "groupbyExpression" => "nullable|string",
            "isShowGridLine" => "required|bool",
            "headerDistance" => "required|integer",
            "screenDisplayString" => "required|string",
            "parentId" => "nullable|integer",
            "labelRowCount" => "nullable|integer",
            "labelColumnCount" => "nullable|integer",
            "isDetailWordwrap" => "required|bool",
            "isCompactFooter" => "required|bool"
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
