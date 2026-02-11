<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Upload;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class FilePrivacy
{
    public function handle(Request $request, Closure $next): Response
    {
        $filekey = $request->route('filekey');
        
        $fileData = Upload::where("short_key", $filekey)->first();

        if (!$fileData) {
            abort(404);
        }

        if (isset($fileData->password) && $fileData->password) {

            if (Auth::check()) {
                $user = Auth::user();
                if (
                    $user->type == 2 
                    || $fileData->created_by_id == Auth::id()
                ) {
                    $permission = true;
                } else {
                    $check = $this->checkPermission($filekey);
                    if ($check) {
                        $permission = true;
                    } else {
                        $permission = false;
                    }
                }

            } else {
                $check = $this->checkPermission($filekey);
                if ($check) {
                    $permission = true;
                } else {
                    $permission = false;
                }
            }
        } else {
            $permission = true;
        }

        if ($permission) {
            return $next($request);
        } else {
            return response()
                ->file(public_path('assets/image/password-protected.webp'));
        }
    }

    private function checkPermission(
        string $filekey
    ): bool {
        $filesArr = request()->session()->get('files');

        if (isset($filesArr) && $filesArr) {
            $decryptedArr = [];
            foreach ($filesArr as $file) {
                try {
                    $decrypted = Crypt::decryptString($file);
                    array_push($decryptedArr, $decrypted);
                } catch (DecryptException $e) {
                    // Ignore errors
                }
            }

            $permission = in_array($filekey, $decryptedArr)
                ? true
                : false;
        } else {
            $permission = false;
        }

        return $permission;
    }

}
