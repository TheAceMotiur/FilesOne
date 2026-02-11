<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class FileUploadRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function rules(): array
    {
        $uploadableTypes = uploadableMimeTypes();
        $maxSize = config('upload.MAX_FILE_SIZE');

        return [
            'file' => [
                'required', 
                'file', 
                "mimetypes:{$uploadableTypes}",
                "max: {$maxSize}",
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
        $maxSize = config('upload.MAX_FILE_SIZE');

        return [
            'file.required' => __("lang.file_not_selected"),
            'file.file' => __("lang.file_not_selected_non_files"),
            'file.mimetypes' => __("lang.cannot_upload_filetype"),
            'file.max' => __("lang.file_max_size",['var' => formatKiloBytes($maxSize)]),
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
