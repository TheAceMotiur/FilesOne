<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Helpers\UploadHelper;
use App\Helpers\UploadUrlHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\FileUploadUrlRequest;
use App\Http\Requests\FetchFileRequest;
use App\Helpers\FileFetchHelper;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(
        FileUploadRequest $request
    ): JsonResponse|Response {
        try {
            $errors = $request->failedValidation(
                $request->getValidator()
            );
            if ($errors) {
                return response()->json([
                    'result' => false,
                    'errors' => $errors, 
                ]);
            };

            $formData = $request->only(
                'auto-remove',
                'password',
            );
            
            $uploader = new UploadHelper();
            $file = $request->file('file');
            $upload = $uploader->upload(
                $file,
                $formData
            );

            if ($upload[0]) {
                $slug = pageSlug('file');
                return response()->json([
                    'result' => true,
                    'key' => $upload[1], 
                    'url' => url("/{$slug}/{$upload[1]}"),
                ]);
            }

            return response()->json([
                'result' => false,
                'data' => $upload[1], 
            ]);

        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ]);
        }
    }

    public function upload_link(
        FileUploadUrlRequest $request
    ): JsonResponse {
        try {
            $errors = $request->failedValidation(
                $request->getValidator()
            );
            if ($errors) {
                return response()->json([
                    'result' => false,
                    'errors' => $errors, 
                ]);
            };
            
            $formData = $request->only(
                'file-url',
                'auto-remove',
                'password',
            );

            $uploadUrlHelper = new UploadUrlHelper();
            $fileUrl = $request->input('file-url');
            $upload = $uploadUrlHelper->upload(
                $fileUrl,
                $formData
            );

            if ($upload[0]) {
                $slug = pageSlug('file');
                return response()->json([
                    'result' => true,
                    'key' => $upload[1], 
                    'url' => url("/{$slug}/{$upload[1]}"),
                ]);
            }
    
            return response()->json([
                'result' => false,
                'data' => $upload[1], 
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ]);
        }
    }

    public function get_file_data(
        FetchFileRequest $request
    ): JsonResponse {
        try {
            $errors = $request->failedValidation(
                $request->getValidator()
            );
            if ($errors) {
                return response()->json([
                    'result' => false,
                    'errors' => $errors, 
                ]);
            };

            $formData = $request->only(
                'url',
            );
        
            $fileFetcher = new FileFetchHelper();
            $result = $fileFetcher->get($formData['url']);

            if ($result[0]) {
                return response()->json([
                    'result' => true,
                    'data' => $result[1] ?? false,
                    'fileName' => $result[2] ?? false,
                    'fileSizeBytes' => $result[3] ?? false,
                    'fileUrl' => $result[4] ?? false,
                ]);
            }

            return response()->json([
                'result' => false,
                'data' => $result[1], 
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ]);
        }
    }

    public function fetch_file(
        Request $request
    ): mixed {
        try {
            $url = $request->input('url');
            $fileFetcher = new FileFetchHelper();
            $data = $fileFetcher->fetch($url);

            if ($data[0]) {
                return response()->json([
                    'result' => true,
                    'data' => __('lang.file_added'),
                    'file' => $data[1],
                    'mime' => $data[2],
                ]);
            }

            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
                'error' => $data[1],
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'result' => false,
                'data' => __('lang.error'), 
            ]);
        }

    }

}
