<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Helpers\UploadRestHelper;
use App\Helpers\UploadUrlRestHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FileUploadRestRequest;
use App\Http\Requests\FileUploadUrlRestRequest;
use Exception;

class UploadController extends Controller
{
    public function upload(
        FileUploadRestRequest $request
    ): JsonResponse|Response {
        $errors = $request->failedValidation(
            $request->getValidator()
        );
        if ($errors) {
            return response()->json([
                'result' => false,
                'errors' => $errors, 
            ], 400);
        };

        $formData = $request->only(
            'auto-remove',
            'password',
        );

        $file = $request->file('file');

        try {
            $uploader = new UploadRestHelper();
            $upload = $uploader->upload(
                $file,
                $formData
            );

            if ($upload[0]) {
                $slug = pageSlug('file');
                return response()->json([
                    'result' => true,
                    'url' => url("/{$slug}/{$upload[1]}"),
                ], 200);
            }

            return response()->json([
                'result' => false,
                'data' => $upload[1], 
            ], 400);

        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ], 500);
        }
    }

    public function upload_link(
        FileUploadUrlRestRequest $request
    ): JsonResponse {
        $errors = $request->failedValidation(
            $request->getValidator()
        );
        if ($errors) {
            return response()->json([
                'result' => false,
                'errors' => $errors, 
            ], 400);
        };
        
        $formData = $request->only(
            'file-link',
            'auto-remove',
            'password',
        );

        try {
            $uploadUrlHelper = new UploadUrlRestHelper();
            $fileUrl = $request->input('file-link');
            $upload = $uploadUrlHelper->upload(
                $fileUrl,
                $formData
            );

            if ($upload[0]) {
                $slug = pageSlug('file');
                return response()->json([
                    'result' => true,
                    'url' => url("/{$slug}/{$upload[1]}"),
                ], 200);
            }
    
            return response()->json([
                'result' => false,
                'data' => $upload[1], 
            ], 400);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ], 500);
        }
    }
}
