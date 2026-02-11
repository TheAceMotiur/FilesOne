<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Helpers\SeoHelper;
use App\Models\BlogPost;
use App\Models\BlogComment;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class BlogController extends Controller
{
    public function blog(): View
    {
        $blogModel = new BlogPost;
        $postsCount = $blogModel
            ->fetchAll(false,false,true);
        $postsData = $blogModel
            ->fetchAll(6,false,true);

        $postsArr = [];
        if ($postsData) {
            foreach ($postsData as $post) {
                $postsArr[] = [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'featured_photo' => $post->featured_photo,
                    'postContent' => $post->content,
                    'category' => $post->categoryName,
                ];
            }
        }

        $seo = SeoHelper::pageSeo('blog');

        return view('frontend.blog.index', [
            'functions' => 'frontend.blog.function',
            'pageKey' => 'blog',
            'seoData' => $seo,
            'posts' => $postsArr,
            'loadMore' => $postsCount > $postsData
                ? true
                : false,
        ]);
    }

    public function blog_posts_data(
        int $limit, 
        int $offset
    ): JsonResponse {
        $blogModel = new BlogPost;
        $postsData = $blogModel
            ->fetchAll($limit,$offset,true);

        if ($postsData) {
            $postsHtml = '';
            foreach ($postsData as $post) {
                $postsHtml .= $this->blog_post_card([
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'featured_photo' => $post->featured_photo,
                    'postContent' => $post->content,
                    'category' => $post->categoryName,
                ]);
            }
        }

        $more = $limit < count($postsData)
            ? true
            : false;

        return response()->json([
            'result' => true, 
            'data' => $postsHtml, 
            'count' => count($postsData), 
            'more' => $more,
        ]);
    }

    private function blog_post_card(
        array $postData
    ): string {
        $image = setting('lazyload') == 1
            ? 'data-bg="' 
                . img('blog', $postData['featured_photo'], 'lg') 
                . '">'
            : 'style="background: url(' 
                . img('blog', $postData['featured_photo'], 'lg') 
                . ');">';
        $lazyLoad = setting('lazyload') == 1 
            ? ' lazy' 
            : '';

        $blogSlug = pageSlug('blog_inner', true);
        $url = LaravelLocalization::localizeUrl(
            "/{$blogSlug}/{$postData['slug']}"
        );

        return '<div class="col-md-6 col-xl-4">
            <article class="blog-card">
                <div class="blog-image overflow-hidden">
                    <div class="covered position-relative w-100 h-100' 
                        . $lazyLoad 
                        . '" 
                        ' . $image . '
                    </div>
                </div>
                <div class="card mx-4">
                    <div class="card-body text-center">
                        <p class="blog-category mb-1">
                            ' . $postData['category'] . '
                        </p>
                        <h2 class="blog-title m-0">
                            <a href="' . $url . '">
                                ' . $postData['title'] . '
                            </a>
                        </h2>
                    </div>
                </div>
            </article>
        </div>';
    }

    public function inner(
        string $slug
    ): View {
        $blogModel = new BlogPost;
        $post = $blogModel->fetchSingle($slug);

        if (!$post) {
            abort(404);
        }

        $pview = gettype($post->pageview) == 'integer' 
            ? $post->pageview + 1 
            : 1;
        BlogPost::where('slug', $slug)
            ->update([
                'pageview' => $pview
            ]);

        $commentsModel = new BlogComment;
        $comments = $commentsModel
            ->fetchAffiliatedComments($post->postId);

        $seo = SeoHelper::pageSeo('blog_inner', $post);

        return view('frontend.blog_inner.index', [
            'functions' => 'frontend.blog_inner.function',
            'post' => $post,
            'comments' => $comments,
            'pageKey' => 'blog_inner',
            'seoData' => $seo,
        ]);
    }

    public function comment_new(
        Request $request,
        string $slug
    ): RedirectResponse {
        $postUrl = LaravelLocalization::localizeUrl(
            pageSlug('blog_inner', true) . "/{$slug}"
        );
        
        if (!Auth::check()) {
            return redirect($postUrl)
                ->with('error', __('lang.login_to_comment'))
                ->with('scroller', '.new-comment');
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect($postUrl)
                ->withErrors($validator)
                ->withInput()
                ->with('scroller', '.new-comment');
        }

        $blogModel = new BlogPost;
        $post = $blogModel->fetchSingle($slug);

        if (!$post) {
            return redirect($postUrl)
                ->with('error', __('lang.error'))
                ->with('scroller', '.new-comment');
        }

        $userId = Auth::id();
        $userIp = $request->ip();
        $create = BlogComment::create([
            'created_by_id' => $userId,
            'created_by_ip' => $userIp,
            'updated_by_id' => $userId,
            'updated_by_ip' => $userIp,
            'comment' => $request->input('comment'),
            'post_id' => $post->postId,
            'status' => Auth::user()->type == 2
                ? 1
                : 0,
        ]);

        if ($create) {
            $response = Auth::user()->type == 2
                ? __('lang.comment_sent')
                : __('lang.comment_waiting');

            return redirect($postUrl)
                ->with('success', $response)
                ->with('scroller', '.comments');
        }

        return redirect($postUrl)
            ->with('error', __('lang.error'))
            ->with('scroller', '.new-comment');
    }

}
