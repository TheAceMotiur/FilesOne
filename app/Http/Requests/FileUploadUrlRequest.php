<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class FileUploadUrlRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function rules(): array
    {
        return [
            'file-url' => [
                'required', 
                'url:http,https', 
            ],
            'auto-remove' => [
                'nullable', 
                'in:5m,30m,1h,6h,12h,1d,1w,1m'
            ],
            'password' => [
                'nullable', 
                'min:3', 
                'max:15'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file-url.required' => __("lang.file_url_not_specified"),
            'file-url.url' => __("lang.file_url_incorrect"),
            'auto-remove.in' => __("lang.auto_remove_format_incorrect"),
            'password.min' => __("lang.password_min"),
            'password.max' => __("lang.password_max"),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return $validator->errors()->messages();
    }

    public function getValidator()
    {
        return $this->getValidatorInstance();
    }
}
