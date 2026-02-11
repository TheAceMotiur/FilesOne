<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogComment extends Model
{
    use HasFactory;

    protected $table = 'blog_comments';

    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'comment',
        'post_id',
        'status',
    ];

    public function fetchAllComments()
    {
        $data = DB::table('blog_comments')
            ->leftJoin(
                'users', 
                'blog_comments.created_by_id', 
                'users.id'
            )
            ->join(
                'blog_posts', 
                'blog_comments.post_id', 
                'blog_posts.id'
            )
            ->select('blog_comments.id','blog_comments.comment')
            ->addSelect('blog_comments.status')
            ->addSelect('blog_comments.created_at as date')
            ->addSelect('users.name as userName')
            ->addSelect('users.photo as userPhoto')
            ->addSelect('blog_posts.slug as url')
            ->orderBy('blog_comments.created_at','desc')
            ->get();

        if ($data->isNotEmpty()) {
            return $data;
        }

        return false;
    }

    public function fetchAffiliatedComments(
        int $postId
    ) {
        $data = DB::table('blog_comments')
            ->leftJoin('users', 'blog_comments.created_by_id', 'users.id')
            ->select('blog_comments.comment','blog_comments.status')
            ->addSelect('blog_comments.created_at as date')
            ->addSelect('users.name as userName')
            ->addSelect('users.photo as userPhoto')
            ->addSelect('users.type as userType')
            ->where('blog_comments.post_id', '=', $postId)
            ->where('blog_comments.status', '=', 1)
            ->orderBy('blog_comments.created_at','desc')
            ->get();

        if ($data->isNotEmpty()) {
            return $data;
        }

        return false;
    }

}
