<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
class Resource extends FormRequest
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
        // $validator = Validator::make($resource->all(), [
        //     'resource_name' => 'required',
        //     'image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        // ]);
        
        // if ($validator->fails()) {    
        //     return response()->json(['Message' => $validator->messages()]);
        // }
        return [
            'resource_name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        ];
    }
}
