<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\Pages;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PagesCustomController extends Controller
{
    public function all(): View
    {
        return view('admin.pages.custom.all.index', [
            'functions' => 'admin.pages.custom.all.function',
            'sidebar' => 'pages_custom',
            'pageName' => pageName([__('lang.pages'), __('lang.custom')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $pages = Pages::where('custom', 1)->get();
        $pagesArr = [];

        if ($pages->isNotEmpty()) {
            foreach ($pages as $page) {
                $pagesArr[] = [
                    'name' => $page->name,
                    'slug' => $page->url,
                    'status' => AdminHelper::pagesTableBadges(
                        $page->status,
                        __('lang.enabled'),
                        __('lang.disabled'),
                        'status'
                    ),
                    'action' => AdminHelper::pagesTableButtons(
                        $page->id,
                        $page->url,
                        $page->page_key,
                        true
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $pagesArr
        ]);
    }

    public function add(): View
    {
        return view('admin.pages.custom.add.index', [
            'functions' => 'admin.pages.custom.add.function',
            'sidebar' => 'pages_custom',
            'pageName' => pageName([__('lang.pages'), __('lang.custom')]),
        ]);
    }

    public function add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'page-name' => 'required|string|max:255',
            'status' => 'required|digits:1|in:1,0',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'no-index' => 'required|digits:1|in:1,0',
            'og-title' => 'nullable|string|max:255',
            'og-description' => 'nullable|string|max:1000',
            'og-image' => 'nullable|file|image|max:2048',
            'content' => 'required|array',
            'content.content' => 'required',
        ]);

        $user_id = Auth::id();
        $user_ip = $request->ip();
        $page_key = Str::slug($request->input('page-name'), '-');
        $url = $page_key . '-' . Str::random(6);

        $seo = [
            'title' => $request->input('page-name'),
            'keywords' => $request->input('keywords'),
            'description' => $request->input('description'),
            'no_index' => $request->input('no-index'),
            'og_title' => $request->input('og-title'),
            'og_description' => $request->input('og-description'),
        ];

        if ($request->hasFile('og-image')) {

            $ogFile = $request->file('og-image');
            $ogFileName = 'page_og_'
                . Str::random(32)
                . '.'
                . $ogFile->extension();
            $ogFile->move(public_path('uploads/img/page'), $ogFileName);
        }

        $seo["og_image"] = $ogFileName ?? null;

        // Page content
        $content = $request->input("content");

        if ($request->hasFile('content.header.bg_image')) {

            $bgFile = $request->file('content.header.bg_image');
            $bgFileName = 'page_header_'
                . Str::random(28)
                . '.'
                . $bgFile->extension();
            $bgFile->move(public_path('uploads/img/page'), $bgFileName);
        }

        $content["header"]["bg_image"] = $bgFileName ?? null;

        $create = Pages::create([
            'created_by_id' => $user_id,
            'created_by_ip' => $user_ip,
            'updated_by_id' => $user_id,
            'updated_by_ip' => $user_ip,
            'name' => $request->input('page-name'),
            'url' => $url,
            'content' => json_encode($content),
            'seo' => json_encode($seo),
            'page_key' => $page_key,
            'status' => $request->input('status'),
            'custom' => 1,
        ]);

        if (!$create) {
            return back()
                ->with('error', __('lang.data_add_error'));
        }

        if (Cache::has('pages')) {
            Cache::forget('pages');
        }

        return back()
            ->with('success', __('lang.data_add'));
    }

    public function edit(
        int $pageId
    ): View {
        $page = Pages::where('custom', 1)->find($pageId);
        if (!$page) {
            abort(404);
        }

        $seo = json_decode($page->seo, true);
        $content = json_decode($page->content, true);

        return view('admin.pages.custom.edit.index', [
            'functions' => 'admin.pages.custom.edit.function',
            'sidebar' => 'pages_custom',
            'pageName' => pageName([__('lang.pages'), __('lang.custom')]),
            'page' => $page,
            'seo' => $seo,
            'content' => $content,
        ]);
    }

    public function edit_post(
        Request $request, 
        int $pageId
    ): RedirectResponse {
        $page = Pages::where('custom', 1)
            ->where('id', $pageId)
            ->first();
        if (!$page) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        // Form validation
        $request->validate([
            'page-name' => 'required|string|max:255',
            'status' => 'required|digits:1|in:1,0',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'no-index' => 'required|digits:1|in:1,0',
            'og-title' => 'nullable|string|max:255',
            'og-description' => 'nullable|string|max:1000',
            'og-image' => 'nullable|file|image|max:2048',
            'content' => 'required|array',
            'content.content' => 'required',
        ]);

        $user_id = Auth::id();
        $user_ip = $request->ip();

        // Page data
        $pageData = [
            'updated_by_id' => $user_id,
            'updated_by_ip' => $user_ip,
            'name' => $request->input('page-name'),
            'status' => $request->input('status'),
            'custom' => 1,
        ];
        if ($page->name != $request->input('page-name')) {
            $page_key = Str::slug($request->input('page-name'), '-');
            $pageData['page_key'] = $page_key;
            $pageData['url'] = $page_key . '-' . Str::random(6);
        }

        // Seo data
        $seoArr = json_decode($page->seo, true);
        $seo = [
            'title' => $request->input('page-name'),
            'keywords' => $request->input('keywords'),
            'description' => $request->input('description'),
            'no_index' => $request->input('no-index'),
            'og_title' => $request->input('og-title'),
            'og_description' => $request->input('og-description'),
        ];

        if ($request->hasFile('og-image')) {

            $ogFile = $request->file('og-image');
            $ogFileOld = public_path("uploads/img/page/{$seoArr['og_image']}");

            if (isset($seoArr['og_image']) && file_exists($ogFileOld)) {
                @unlink($ogFileOld);
            }

            $ogFileName = 'page_og_'
                . Str::random(32)
                . '.'
                . $ogFile->extension();
            $ogFile->move(public_path('uploads/img/page'), $ogFileName);
        }

        $seo["og_image"] = $ogFileName ?? ($seoArr['og_image'] ?? null);
        $pageData['seo'] = json_encode($seo);

        // Page content
        $content = json_decode($page->content, true);
        $contentArr = $request->input("content");

        if ($request->hasFile('content.header.bg_image')) {

            $bgFile = $request->file('content.header.bg_image');
            $bgFileOld = public_path(
                "uploads/img/page/{$content['header']["bg_image"]}"
            );

            if (
                isset($content['header']["bg_image"]) 
                && file_exists($bgFileOld)
            ) {
                @unlink($bgFileOld);
            }

            $bgFileName = 'page_header_'
                . Str::random(28)
                . '.'
                . $bgFile->extension();
            $bgFile->move(public_path('uploads/img/page'), $bgFileName);
        }

        $contentArr["header"]["bg_image"] = $bgFileName
            ?? ($content["header"]["bg_image"] ?? null);
        $pageData['content'] = json_encode($contentArr);

        // Update page
        $update = Pages::where('id', $pageId)
            ->update($pageData);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        if (Cache::has('pages')) {
            Cache::forget('pages');
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function delete(
        int $pageId
    ): RedirectResponse {
        $page = Pages::where('custom', 1)
            ->where('id', $pageId)
            ->first();
        if (!$page) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $pageContent = json_decode($page->content, true);
        if (isset($pageContent['header']['bg_image'])) {
            @unlink(
                public_path(
                    'uploads/img/page/'
                    . $pageContent['header']['bg_image']
                )
            );
        }

        $pageSeo = json_decode($page->seo, true);
        if (isset($pageSeo['og_image'])) {
            @unlink(
                public_path(
                    'uploads/img/page/'
                    . $pageSeo['og_image']
                )
            );
        }

        $delete = $page->delete();

        if (!$delete) {
            return back()
                ->with('error', __('lang.data_delete_error'));
        }

        if (Cache::has('pages')) {
            Cache::forget('pages');
        }

        return back()
            ->with('success', __('lang.data_delete'));
    }

    public function upload_file(
        Request $request
    ): JsonResponse {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'uploaded' => false,
                "error" => [
                    "message" => $validator->errors(),
                ],
            ]);
        }

        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $fileName = 'page_inner_'
                . Str::random(29)
                . '.'
                . $file->extension();
            $file->move(public_path('uploads/img/page'), $fileName);

            return response()->json([
                'uploaded' => true,
                'url' => img('page', $fileName),
            ]);
        }

        return response()->json([
            'uploaded' => false
        ]);
    }

    public function delete_file(
        Request $request
    ): JsonResponse {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'data' => __('lang.data_delete_error'),
            ]);
        }

        $file = $request->input('file');
        if (file_exists(base_path($file))) {
            $delete = unlink(base_path($file));
            if ($delete) {
                return response()->json([
                    'result' => true,
                    'data' => __('lang.data_delete'),
                ]);
            }
        }

        return response()->json([
            'result' => false,
            'data' => __('lang.data_delete_error'),
        ]);
    }
    
}
