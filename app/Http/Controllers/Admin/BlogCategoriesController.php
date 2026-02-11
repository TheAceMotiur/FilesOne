<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Helpers\AdminHelper;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BlogPost;

class BlogCategoriesController extends Controller
{
    public function all(): View
    {
        return view('admin.blog.categories.all.index', [
            'functions' => 'admin.blog.categories.all.function',
            'sidebar' => 'blog_categories',
            'pageName' => pageName([__('lang.blog'), __('lang.categories')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $categoriesModel = new BlogCategory;
        $categories = $categoriesModel->fetchAllCategories();
        $badge = '<span class="badge-4 ms-1">' . __('lang.default') . '</span>';

        $categoriesArr = [];
        foreach ( $categories as $category ) {
            $categoriesArr[] = [
                'name' => $category->id == 1
                    ? "{$category->name} {$badge}"
                    : $category->name,
                'posts' => $category->count,
                'action' => AdminHelper::blogCategoriesTableButtons(
                    $category->id
                ),
            ];
        }

        return response()->json([
            'result' => true,
            'data' => $categoriesArr
        ]);
    }

    public function add(): View
    {
        return view('admin.blog.categories.add.index', [
            'functions' => 'admin.blog.categories.add.function',
            'sidebar' => 'blog_categories',
            'pageName' => pageName([__('lang.blog'), __('lang.categories')]),
        ]);
    }

    public function add_post(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        $user_id = Auth::id();
        $user_ip = $request->ip();

        $create = BlogCategory::create([
            'created_by_id' => $user_id,
            'created_by_ip' => $user_ip,
            'updated_by_id' => $user_id,
            'updated_by_ip' => $user_ip,
            'name' => $request->input('name'),
        ]);

        if (!$create) {
            return back()
                ->with('error', __('lang.data_add_error'));
        }

        return back()
            ->with('success', __('lang.data_add'));
    }

    public function edit(
        int $categoryId
    ): View {
        $category = BlogCategory::find($categoryId);
        if (!$category) {
            abort(404);
        }

        return view('admin.blog.categories.edit.index', [
            'functions' => 'admin.blog.categories.edit.function',
            'sidebar' => 'blog_categories',
            'pageName' => pageName([__('lang.blog'), __('lang.categories')]),
            'category' => $category,
        ]);
    }

    public function edit_post(
        Request $request, 
        int $categoryId
    ): RedirectResponse {
        $category = BlogCategory::find($categoryId);
        if (!$category) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $request->validate([
            'name' => 'required|string|max:255|'
                . "unique:blog_categories,name,{$categoryId}",
        ]);

        $categoryData = [
            'updated_by_id' => Auth::id(),
            'updated_by_ip' => $request->ip(),
            'name' => $request->input('name'),
        ];

        $update = BlogCategory::where('id', $categoryId)
            ->update($categoryData);

        if (!$update) {
            return back()
                ->with('error', __('lang.data_update_error'));
        }

        return back()
            ->with('success', __('lang.data_update'));
    }

    public function delete(
        Request $request, 
        int $categoryId
    ): RedirectResponse {
        $category = BlogCategory::where('id', $categoryId)
            ->first();

        if (!$category) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        if ($category->id == 1) {
            return back()
                ->with('error', __('lang.default_category_cant_delete'));
        }

        if ($category->delete()) {

            $posts = BlogPost::where('category', $categoryId)->get();
            if ($posts) {
                foreach ( $posts as $post ) {
                    BlogPost::where('id', $post->id)
                        ->update([
                            'updated_by_id' => Auth::id(),
                            'updated_by_ip' => $request->ip(),
                            'category' => 1,
                        ]);
                }
            }

            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

}
