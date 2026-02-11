<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FileFetchHelper
{
    public function get(
        string $fileUrl
    ): mixed {
        return $this->get_data($fileUrl);
    }

    private function get_data($fileUrl): mixed
    {
        try {
            $fileName = pathinfo($fileUrl, PATHINFO_BASENAME) ?? false;
            $fileExt = pathinfo($fileUrl, PATHINFO_EXTENSION) ?? false;
            
            if (!$fileName || !$fileExt) {
                return [
                    false,
                    __('lang.file_url_incorrect'),
                ];
            }

            // Check file url status
            $fileHeaders = self::fileStatus($fileUrl);
            if (!$fileHeaders) {
                return [
                    false,
                    __('lang.cannot_retrieved_file'),
                ];
            }

            // Check file size
            $fileSizeBytes = self::fileSize($fileUrl);
            $fileSize = (int) intval($fileSizeBytes) / 1024;
            $maxSize = config("upload.MAX_FILE_SIZE");
            if ($fileSize > $maxSize) {
                return [
                    false,
                    __("lang.file_max_size",['var' => formatKiloBytes($maxSize)]),
                ];
            }

            // Check file format (Extension)
            $allowedTypes = uploadableTypes('array');
            if (!in_array($fileExt, $allowedTypes)) {
                return [
                    false,
                    __('lang.file_cannot_upload'),
                ];
            }

            // Check user permission for upload process
            $canIUpload = PlanHelper::canIUpload(
                Auth::id(), 
                $fileName, 
                $fileSize
            );
            if (!$canIUpload[0]) {
                return [
                    false,
                    $canIUpload[1],
                ];
            }

            return [
                true,
                __('lang.file_added'),
                $fileName,
                $fileSizeBytes,
                $fileUrl,
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    private function fileStatus($url)
    {
        try {
            $agent = request()->header('User-Agent');
            $response = Http::withHeaders(['User-Agent' => $agent])->get($url);
            return $response->successful()
                ? true
                : false;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    private function fileSize($url)
    {
        try {
            $agent = request()->header('User-Agent');
            $response = Http::withHeaders(['User-Agent' => $agent])->get($url);
            return $response->header('Content-Length');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            false;
        }
    }

    private function fileContentType($url)
    {
        try {
            $agent = request()->header('User-Agent');
            $response = Http::withHeaders(['User-Agent' => $agent])->get($url);
            return $response->header('Content-Type');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            false;
        }
    }

    private function fileBody($url)
    {
        try {
            $agent = request()->header('User-Agent');
            $response = Http::withHeaders(['User-Agent' => $agent])->get($url);
            return $response->body();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            false;
        }
    }

    public function fetch($url): mixed 
    {
        try {
            $urlForm = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
            $urlBase = str_ireplace('www.', '', parse_url(config('app.url'), PHP_URL_HOST));
            $urlArr = explode('/', $url);
            $fileName = end($urlArr);

            if ($urlForm == $urlBase) {

                $path = Storage::disk('temp')->path($fileName);
                $mime = Storage::disk('temp')->mimeType($fileName);
                $mimetypeArr = explode(';', $mime);

                $data = $this->fileBody($path);
                if (!$data) {
                    return [
                        false,
                        __('lang.error'), 
                    ];
                }

                $base64 = base64_encode($data);

                return [
                    true,
                    "data:{$mimetypeArr[0]};base64,{$base64}",
                    $mime
                ];

            } else {
                $contents = $this->fileBody($url);
                if (!$contents) {
                    return [
                        false,
                        __('lang.error'), 
                    ];
                }

                $content_type = $this->fileContentType($url);
                if (!$content_type) {
                    return [
                        false,
                        __('lang.error'), 
                    ];
                }

                $base64 = base64_encode($contents);
                
                return [
                    true,
                    "data:{$content_type};base64,{$base64}",
                    $content_type
                ];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

    public function download($url): mixed 
    {
        try {
            $urlForm = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
            $urlBase = str_ireplace('www.', '', parse_url(config('app.url'), PHP_URL_HOST));
            $urlArr = explode('/', $url);
            $fileName = end($urlArr);

            if ($urlForm == $urlBase) {
                $contents = $this->fileBody($url);
                if (!$contents) {
                    return [
                        false,
                        __('lang.error'), 
                    ];
                }

                $filename = basename($url);
                Storage::disk('temp')->put($filename, $contents);

                return [
                    true,
                    $filename
                ];

            } else {
                $contents = $this->fileBody($url);
                if (!$contents) {
                    return [
                        false,
                        __('lang.error'), 
                    ];
                }

                $filename = basename($url);
                Storage::disk('temp')->put($filename, $contents);

                return [
                    true,
                    $filename
                ];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [
                false,
                __('lang.error'), 
            ];
        }
    }

}
