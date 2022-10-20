<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'templateCode' => 'required',
            'searchGroupId' => 'required',
            'templateName' => 'required',
            'paperSizeEnum' => 'required',
            //'detailLayout' => 'required',
            // 'headerHeight' => 'required',
            // 'headerHeightPage2' => 'required',
            // 'footerHeight' => 'required',
            // 'detailLineSpacing' => 'required',
            // 'layoutSql' => 'required',
            // 'detailSql' => 'required',
            // 'footerSql' => 'required'
        ];
    }
}
