<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Models\BlogComment;
use App\Helpers\AdminHelper;

class BlogCommentsController extends Controller
{
    public function all(): View
    {
        return view('admin.blog.comments.index', [
            'functions' => 'admin.blog.comments.function',
            'sidebar' => 'blog_comments',
            'pageName' => pageName([__('lang.blog'), __('lang.comments')]),
        ]);
    }

    public function all_post(): JsonResponse
    {
        $commentsModel = new BlogComment;
        $comments = $commentsModel->fetchAllComments();

        $commentsArr = [];
        if ($comments) {
            foreach ($comments as $comment) {
                $commentsArr[] = [
                    'date' => dateFormat(
                        $comment->date,
                    ),
                    'user' => AdminHelper::userBlock(
                        $comment->userName,
                        img('user', $comment->userPhoto)
                    ),
                    'comment' => e($comment->comment),
                    'action' => AdminHelper::blogCommentsTableButtons(
                        $comment->id,
                        $comment->url,
                        $comment->status,
                    ),
                ];
            }
        }

        return response()->json([
            'result' => true,
            'data' => $commentsArr
        ]);
    }

    public function verify(
        int $commentId
    ): RedirectResponse {
        $commentData = BlogComment::where('id', $commentId)
            ->first();

        if (!$commentData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $update = $commentData->update([
            'status' => 1,
        ]);

        if ($update) {
            return back()
                ->with('success', __('lang.data_update'));
        }

        return back()
            ->with('error', __('lang.data_update_error'));
    }

    public function delete(
        int $commentId
    ): RedirectResponse {
        $commentData = BlogComment::where('id', $commentId)
            ->first();

        if (!$commentData) {
            return back()
                ->with('error', __('lang.data_not_found'));
        }

        $delete = $commentData->delete();

        if ($delete) {
            return back()
                ->with('success', __('lang.data_delete'));
        }

        return back()
            ->with('error', __('lang.data_delete_error'));
    }

}
