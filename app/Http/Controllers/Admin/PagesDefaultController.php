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

class PagesDefaultController extends Controller
{
    public function all(): View
    {
        return view('admin.pages.default.all.index', [
            'functions' => 'admin.pages.default.all.function',
            'sidebar' => 'pages_default',
            'pageName' => pageName([__('lang.pages'), __('lang.default')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $pages = Pages::where('custom', 0)->get();

        $pagesArr = [];
        if ($pages->isNotEmpty()) {
            foreach ($pages as $page) {
                if ($page->page_key == 'affiliate') {
                    $status = affiliateSetting('status') == 0 
                        ? 0 
                        : $page->status;
                    $pagesArr[] = [
                        'name' => $page->name,
                        'slug' => $page->url,
                        'status' => AdminHelper::pagesTableBadges(
                            $status,
                            __('lang.enabled'),
                            __('lang.disabled'),
                            'status'
                        ),
                        'action' => AdminHelper::pagesTableButtons(
                            $page->id,
                            $page->url,
                            $page->page_key,
                        ),
                    ];
                } else {
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
                        ),
                    ];
                }
            }
        }

        return response()->json([
            'result' => true,
            'data' => $pagesArr
        ]);
    }

    public function edit(
        int $pageId
    ): View {
        $page = Pages::where('custom', 0)->find($pageId);
        if (!$page) {
            abort(404);
        }

        $pageFile = str_replace(" ", "_", strtolower($page->page_key));
        $seo = json_decode($page['seo'], true);
        $settings = json_decode($page['settings'], true);
        $content = json_decode($page['content'], true);

        return view("admin.pages.default.edit.{$pageFile}", [
            'functions' => 'admin.pages.default.edit.function',
            'sidebar' => 'pages_default',
            'pageName' => pageName([__('lang.pages'), __('lang.default')]),
            'page' => $page,
            'settings' => $settings,
            'seo' => $seo,
            'content' => $content,
        ]);
    }

    public function edit_post(
        Request $request, 
        int $pageId
    ): RedirectResponse {
        $page = Pages::where('custom', 0)->find($pageId);
        if (!$page) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $pageId = $page->id;
        $request->merge([
            'page-url' => Str::slug($request->input('page-slug'), '-'),
        ]);

        if (
            $page->page_key == 'home' 
            || $page->page_key == '403' 
            || $page->page_key == '404' 
            || $page->page_key == '500'
        ) {
            $request->validate([
                'page-name' => 'required|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'no-index' => 'nullable|digits:1|in:1,0',
                'og-title' => 'nullable|string|max:255',
                'og-description' => 'nullable|string|max:1000',
                'og-image' => 'nullable|file|image|max:2048',
                'settings' => 'array',
                'content' => 'array',
            ]);
        } else {
            $request->validate([
                'page-name' => 'required|string|max:255',
                'page-url' => "required|string|max:255|unique:pages,url,{$pageId}",
                'keywords' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'no-index' => 'nullable|digits:1|in:1,0',
                'og-title' => 'nullable|string|max:255',
                'og-description' => 'nullable|string|max:1000',
                'og-image' => 'nullable|file|image|max:2048',
                'settings' => 'array',
                'content' => 'array',
            ]);
        }

        $user_id = Auth::id();
        $user_ip = $request->ip();

        $pageData = [
            'updated_by_id' => $user_id,
            'updated_by_ip' => $user_ip,
            'name' => $request->input('page-name'),
            'status' => 1,
            'custom' => 0,
        ];

        if (
            $page->page_key != 'home' 
            && $page->page_key != '403' 
            && $page->page_key != '404' 
            && $page->page_key != '500'
        ) {
            $pageData['url'] = $request->input('page-url');
        }
        
        // Page seo data
        $seoArr = json_decode($page->seo, true);
        $seo = [
            'title' => $request->input('page-name'),
            'keywords' => $request->input('keywords'),
            'description' => $request->input('description'),
            'no_index' => $request->input('no-index'),
            'og_title' => $request->input('og-title'),
            'og_description' => $request->input('og-description'),
        ];

        // Page og image hasFile
        if ($request->hasFile('og-image')) {

            $ogFile = $request->file('og-image');

            if (isset($seoArr['og_image']) && $seoArr['og_image']) {
                $ogFileOld = "uploads/img/page/{$seoArr['og_image']}";
                if (file_exists(public_path($ogFileOld))) {
                    @unlink(public_path($ogFileOld));
                }
            }

            $ogFileName = 'page_og_'
                . Str::random(32)
                . '.'
                . $ogFile->extension();
            $ogFile->move(public_path('uploads/img/page'), $ogFileName);

            $fileName = $ogFileName;
        }

        $seo["og_image"] = $fileName ?? ($seoArr['og_image'] ?? null);
        $pageData['seo'] = json_encode($seo);

        // Page content
        $pageData['settings'] = $request->input('settings')
            ? json_encode($request->input('settings'))
            : NULL;

        // Page content
        if ($request->has('content')) {
            $pageValues = json_decode($page->content, true);
            $content = $request->only(['content']);
            $contentArr = [];

            foreach ($content['content'] as $widget => $setting) {
                foreach ($setting as $key => $value) {
                    if (str_contains($key, 'image')) {
                        if ($request->hasFile("content.{$widget}.{$key}")) {
                            $request->validate([
                                "content.{$widget}.{$key}" => 'nullable|'
                                    . 'image|max:2048',
                            ]);
                        }
                    }
                }
            }

            foreach ($content['content'] as $widget => $setting) {
                foreach ($setting as $key => $value) {

                    if (str_contains($key, 'image')) {

                        if ($request->hasFile("content.{$widget}.{$key}")) {
          
                            $oldFile = widget($page->page_key, $widget, $key);
                            $oldFilePath = "uploads/img/page/{$oldFile}";
                            if (file_exists(public_path($oldFilePath))) {
                                @unlink(public_path($oldFilePath));
                            }

                            $file = $request->file("content.{$widget}.{$key}");
                            $fileName = 'widget_'
                                . Str::random(33)
                                . '.'
                                . $file->extension();
                            $file->move(
                                public_path('uploads/img/page'),
                                $fileName
                            );

                            $contentArr['content'][$widget][$key] = 
                                $fileName ?? null;
                        } else {
                            $contentArr['content'][$widget][$key] =
                                $pageValues[$widget][$key] ?? null;
                        }

                    } else {
                        $contentArr['content'][$widget][$key] =
                            isset($value) && $value
                            ? $value
                            : null;
                    }
                }
            }

            $pageData['content'] = json_encode($contentArr['content']);
        }

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
            'uploaded' => false,
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
