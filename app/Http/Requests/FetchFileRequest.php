<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class FetchFileRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function rules(): array
    {
        return [
            'url' => [
                'required', 
                'url:http,https', 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => [__("lang.file_url_not_specified")],
            'url.url' => [__("lang.url_not_valid")],
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
