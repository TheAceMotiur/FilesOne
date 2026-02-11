<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function settings(): View
    {
        return view('user.settings.index', [
            'functions' => 'user.settings.function',
            'sidebar' => 'settings',
            'pageName' => pageName([__('lang.settings')]),
        ]);
    }

    public function settings_post(
        Request $request
    ): RedirectResponse {
        $userData = User::where('id', Auth::id())
            ->first();
        $userId = $userData->id;

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|max:100|unique:users,email,{$userId}",
            'password' => 'required|min:8',
            'password-new' => 'nullable|min:8|confirmed',
            'photo' => 'nullable|file|image|max:2048',
        ]);

        if (
            Hash::check(
                $request->input('password'),
                $userData['password']
            )
        ) {
            $profileData = [
                'updated_by_id' => $userId,
                'updated_by_ip' => $request->ip(),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ];

            if ($newPassword = $request->input('password-new')) {
                $profileData['password'] = Hash::make($newPassword);
            }

            if ($file = $request->file('photo')) {

                if ($userData->photo) {
                    $userPhotoOld = public_path(
                        "uploads/img/user/{$userData->photo}"
                    );
                    @unlink($userPhotoOld);
                }

                $fileName = 'avatar_'
                    . Str::random(33)
                    . '.'
                    . $file->extension();
                $file->move(public_path('uploads/img/user'), $fileName);

                $profileData["photo"] = $fileName;
            }

            $update = User::where('id', $userData->id)
                ->update($profileData);

            if ($update) {
                return back()
                    ->with('success', __('lang.data_update'));
            }

            return back()
                ->with('error', __('lang.data_update_error'));
        } else {
            return back()
                ->with('error', __('lang.data_update_error'));
        }
    }

}
