<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Helpers\AdminHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function all(): View
    {
        return view('admin.blog.posts.all.index', [
            'functions' => 'admin.blog.posts.all.function',
            'sidebar' => 'blog_posts',
            'pageName' => pageName([__('lang.blog'), __('lang.posts')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $postModel = new BlogPost;
        $postData = $postModel->fetchAll();

        if ($postData) {
            $postArr = [];
            foreach ($postData as $post) {
                $postArr[] = [
                    'date' => dateFormat($post->created_at),
                    'title' => $post->title,
                    'category' => $post->categoryName,
                    'status' => $post->status == 1 
                        ? __('lang.public') 
                        : __('lang.private'),
                    'action' => AdminHelper::blogPostTableButtons(
                        $post->id,
                        $post->slug,
                    ),
                ];
            }

            return response()->json([
                'result' => true,
                'data' => $postArr
            ]);
        }

        return response()->json([
            'result' => false,
        ]);
    }

    public function add(): View
    {
        $categories = BlogCategory::get();

        return view('admin.blog.posts.add.index', [
            'functions' => 'admin.blog.posts.add.function',
            'sidebar' => 'blog_posts',
            'pageName' => pageName([__('lang.blog'), __('lang.post')]),
            'categories' => $categories,
        ]);
    }

    public function add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'title' => 'required|string|max:255',
            'featured-photo' => 'nullable|file|image|max:2048',
            'category' => 'required|numeric|max:255',
            'status' => 'required|digits:1|in:1,0',
            'content' => 'required|min:255',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'og-title' => 'nullable|string|max:255',
            'og-description' => 'nullable|string|max:255',
            'no-index' => 'required|digits:1|in:1,0',
            'og-image' => 'nullable|file|image|max:2048',
        ]);

        $user_id = Auth::id();
        $user_ip = $request->ip();
        $url = Str::slug(
            $request->input('title') . '-' . Str::random(6), 
            '-'
        );

        $seo = [
            'keywords' => $request->input('keywords'),
            'description' => $request->input('description'),
            'no_index' => $request->input('no-index'),
            'og_title' => $request->input('og-title'),
            'og_description' => $request->input('og-description'),
        ];

        if ($request->hasFile('og-image')) {

            $ogFile = $request->file('og-image');
            $ogFileName = 'blog_og_'
                . Str::random(32)
                . '.'
                . $ogFile->extension();
            $ogFile->move(public_path('uploads/img/blog'), $ogFileName);
        }

        $seo["og_image"] = $ogFileName ?? null;

        if ($request->hasFile('featured-photo')) {

            $featuredFile = $request->file('featured-photo');
            $featuredFileName = 'blog_featured_'
                . Str::random(26)
                . '.'
                . $featuredFile->extension();
            $featuredFile->move(
                public_path('uploads/img/blog'),
                $featuredFileName
            );
        }

        $create = BlogPost::create([
            'created_by_id' => $user_id,
            'created_by_ip' => $user_ip,
            'updated_by_id' => $user_id,
            'updated_by_ip' => $user_ip,
            'title' => $request->input('title'),
            'slug' => $url,
            'featured_photo' => $featuredFileName ?? null,
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'seo' => json_encode($seo),
            'status' => $request->input('status'),
        ]);

        if (!$create) {
            return back()
                ->with('error', __('lang.data_add_error'));
        }
        return back()
            ->with('success', __('lang.data_add'));
    }

    public function edit(
        int $postId
    ): View {
        if (!$post = BlogPost::find($postId)) {
            abort(404);
        }
        $categories = BlogCategory::get();

        return view('admin.blog.posts.edit.index', [
            'functions' => 'admin.blog.posts.edit.function',
            'sidebar' => 'blog_posts',
            'pageName' => pageName([__('lang.blog'), __('lang.post')]),
            'post' => $post,
            'seo' => json_decode($post['seo'], true),
            'categories' => $categories,
        ]);
    }

    public function edit_post(
        Request $request, 
        int $postId
    ): RedirectResponse {

        $post = BlogPost::find($postId);
        if (!$post) {
            return back()->with('error', __('lang.data_not_found'));
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'featured-photo' => 'nullable|file|image|max:2048',
            'category' => 'required|numeric|max:255',
            'status' => 'required|digits:1|in:1,0',
            'content' => 'required|min:255',
            'keywords' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'og-title' => 'nullable|string|max:255',
            'og-description' => 'nullable|string|max:255',
            'no-index' => 'required|digits:1|in:1,0',
            'og-image' => 'nullable|file|image|max:2048',
        ]);

        $postData = [
            'updated_by_id' => Auth::id(),
            'updated_by_ip' => $request->ip(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'status' => $request->input('status'),
        ];

        // If the post url has changed, change the post url
        if ($post->title != $request->input('title')) {
            $url = Str::slug(
                $request->input('title') . '-' . Str::random(6),
                '-'
            );
            $postData['slug'] = $url;
        }

        // Seo data
        $seoArr = json_decode($post->seo, true);
        $seo = [
            'keywords' => $request->input('keywords'),
            'description' => $request->input('description'),
            'no_index' => $request->input('no-index'),
            'og_title' => $request->input('og-title'),
            'og_description' => $request->input('og-description'),
        ];

        if ($request->hasFile('og-image')) {

            if (isset($seoArr->og_image) && $seoArr->og_image) {
                $ogFileOld = public_path(
                    "uploads/img/blog/{$seoArr->og_image}"
                );
                if (file_exists($ogFileOld)) {
                    @unlink($ogFileOld);
                }
            }

            $ogFile = $request->file('og-image');
            $ogFileName = 'blog_og_' 
                . Str::random(32) 
                . '.' 
                . $ogFile->extension();
            $ogFile->move(public_path('uploads/img/blog'), $ogFileName);
        }

        $seo["og_image"] = $ogFileName ?? ($seoArr['og_image'] ?? null);
        $postData['seo'] = json_encode($seo);

        if ($request->hasFile('featured-photo')) {

            if (isset($post->featured_photo) && $post->featured_photo) {
                $featureFileOld = public_path(
                    "uploads/img/blog/{$post->featured_photo}"
                );
                if (file_exists($featureFileOld)) {
                    @unlink($featureFileOld);
                }
            }

            $featuredFile = $request->file('featured-photo');
            $featuredFileName = 'blog_featured_'
                . Str::random(24)
                . '.'
                . $featuredFile->extension();
            $featuredFile->move(
                public_path('uploads/img/blog'),
                $featuredFileName
            );
        }

        $postData['featured_photo'] =
            $featuredFileName ?? ($post->featured_photo ?? null);

        $update = BlogPost::where('id', $postId)
            ->update($postData);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }
        return back()
            ->with('success', __('lang.data_update'));
    }

    public function delete(
        int $postId
    ): RedirectResponse {
        $postModel = new BlogPost;
        $delete = $postModel->deletePost($postId);

        if (!$delete[0]) {
            return back()
                ->with('error', $delete[1]);
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
            $fileName = 'blog_inner_'
                . Str::random(29)
                . '.'
                . $file->extension();
            $file->move(public_path('uploads/img/blog'), $fileName);

            return response()->json([
                'uploaded' => true,
                'url' => img('blog', $fileName),
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
