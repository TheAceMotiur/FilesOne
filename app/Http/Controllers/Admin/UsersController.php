<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Helpers\AdminHelper;

class UsersController extends Controller
{
    public function all(): View
    {
        return view('admin.users.all.index', [
            'functions' => 'admin.users.all.function',
            'sidebar' => 'users_all',
            'pageName' => pageName([__('lang.users'), __('lang.all')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $userModel = new User;
        $usersData = $userModel->fetchAllUsers();

        if ($usersData) {
            $usersArr = [];
            foreach ($usersData as $user) {
                $usersArr[] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'status' => AdminHelper::usersTableBadges(
                        $user['verified'],
                        __('lang.verified'),
                        __('lang.not_verified'),
                    ),
                    'action' => AdminHelper::usersTableButtons(
                        $user['id'],
                    ),
                ];
            }

            return response()->json([
                'result' => true,
                'data' => $usersArr
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }

    public function add(): View
    {
        return view('admin.users.add.index', [
            'functions' => 'admin.users.add.function',
            'sidebar' => 'users_add',
            'pageName' => pageName([__('lang.users'), __('lang.add')]),
        ]);
    }

    public function add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'photo' => 'nullable|file|image|max:2048',
        ]);

        $userId = Auth::id();
        $userIp = $request->ip();
        $apiToken = uniqueCode('users','api_token');
        $accountData = [
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'type' => 1,
            'password' => Hash::make($request->input('password')),
            'verified' => 1,
            'api_token' => $apiToken,
        ];

        if ($file = $request->file('photo')) {
            $fileName = 'avatar_' . Str::random(33) . '.' . $file->extension();
            $file->move(public_path('uploads/img/user'), $fileName);
            $accountData["photo"] = $fileName;
        }

        $create = User::create($accountData);

        if ($create) {
            return back()
                ->with('success', __('lang.data_add'));
        }

        return back()
            ->with('error', __('lang.data_add_error'));
    }

    public function edit(
        int $userId
    ): View {
        $user = User::where('type', 1)
            ->where('id', $userId)
            ->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit.index', [
            'functions' => 'admin.users.edit.function',
            'sidebar' => 'users_all',
            'pageName' => pageName([__('lang.users'), __('lang.edit')]),
            'user' => $user,
        ]);
    }

    public function edit_post(
        Request $request, 
        int $userId
    ): RedirectResponse {
        $userData = User::where('id', $userId)
            ->first();

        if (!$userData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|max:100|unique:users,email,{$userId}",
            'password' => 'nullable|min:8|confirmed',
            'photo' => 'nullable|file|image|max:2048',
            'verified' => 'required|in:0,1',
        ]);

        $userId = Auth::id();
        $accountData = [
            'updated_by_id' => $userId,
            'updated_by_ip' => $request->ip(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'verified' => $request->input('verified'),
        ];

        if ($password = $request->input('password')) {
            $accountData["password"] = Hash::make($password);
        }

        if ($file = $request->file('photo')) {

            if (isset($userData->photo) && $userData->photo) {
                $userPhoto = public_path("uploads/img/user/{$userData->photo}");
                if (file_exists($userPhoto)) {
                    @unlink($userPhoto);
                }
            }
            $fileName = 'avatar_' . Str::random(33) . '.' . $file->extension();
            $file->move(public_path('uploads/img/user'), $fileName);

            $accountData["photo"] = $fileName;
        }

        $update = $userData->update($accountData);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function delete(
        int $userId
    ): RedirectResponse {
        $userData = User::where('id', $userId)
            ->first();

        if (!$userData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($userData->delete()) {

            if ($userData->photo) {
                $userPhotoOld = public_path(
                    "uploads/img/user/{$userData->photo}"
                );
                if (file_exists($userPhotoOld)) {
                    @unlink($userPhotoOld);
                }
            }

            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('success', __('lang.data_delete_error'));
    }

}
