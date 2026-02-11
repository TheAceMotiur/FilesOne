<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'id',
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'title',
        'slug',
        'featured_photo',
        'content',
        'category',
        'seo',
        'pageview',
        'status',
    ];
    
    public function fetchAll(
        mixed $limit = false,
        mixed $offset = false,
        mixed $public = false,
    ) {
        $postData = DB::table('blog_posts')
            ->leftJoin(
                'blog_categories',
                'blog_posts.category',
                'blog_categories.id'
            )
            ->select('blog_posts.*')
            ->addSelect(
                'blog_categories.name as categoryName'
            )
            ->when($public, function ( $query): void {
                $query->where('blog_posts.status', '=', 1);
            })
            ->when($limit, function ( $query) use ($limit): void {
                $query->limit($limit);
            })
            ->when($offset, function ( $query) use ($offset): void {
                $query->offset($offset);
            })
            ->orderByDesc('blog_posts.created_at')
            ->get();

        return $postData->isNotEmpty()
            ? $postData->toArray()
            : false;
    }

    public function fetchSingle(
        string $slug
    ) {
        return DB::table('blog_posts')
            ->leftJoin(
                'blog_categories',
                'blog_posts.category',
                'blog_categories.id'
            )
            ->leftJoin(
                'users',
                'blog_posts.created_by_id',
                'users.id'
            )
            ->select(
                'blog_posts.id as postId',
                'blog_posts.title',
                'blog_posts.slug',
                'blog_posts.featured_photo',
                'blog_posts.pageview'
            )
            ->addSelect(
                'blog_posts.content as postContent',
                'blog_posts.created_at'
            )
            ->addSelect('blog_categories.name as categoryName')
            ->addSelect('users.id as userId')
            ->addSelect('users.name as userName')
            ->addSelect('users.photo as userPhoto')
            ->addSelect('blog_posts.seo')
            ->where('blog_posts.slug', '=', $slug)
            ->where('blog_posts.status', '=', 1)
            ->first();
    }

    /**
     * Delete an blog post
     * @param int $postId
     * @return mixed
     */
    public function deletePost(
        int $postId
    ) {
        $blogPost = DB::table('blog_posts')
            ->where('id', $postId)
            ->first();

        if (!$blogPost) {
            return [
                false,
                __('data_not_found')
            ];
        }

        preg_match_all('@src="([^"]+)"@', $blogPost->content, $match);
        $images = array_pop($match);
        foreach ( $images as $image ) {
            if (file_exists(base_path($image))) {
                @unlink(base_path($image));
            }
        }
        if (isset($blogPost->featured_photo) && $blogPost->featured_photo) {
            if (
                file_exists(
                    public_path("uploads/img/blog/{$blogPost->featured_photo}")
                )
            ) {
                @unlink(
                    public_path("uploads/img/blog/{$blogPost->featured_photo}")
                );
            }
        }
        $seo = json_decode($blogPost->seo, true);
        if (isset($seo['og_image']) && $seo['og_image']) {
            if (
                file_exists(public_path("uploads/img/blog/{$seo['og_image']}"))
            ) {
                @unlink(public_path("uploads/img/blog/{$seo['og_image']}"));
            }
        }

        $delete = DB::table('blog_posts')
            ->where('id', $postId)
            ->delete();

        if ($delete) {
            return [
                true,
                __('data_delete')
            ];
        }

        return [
            false,
            __('data_delete_error')
        ];
    }

}
