<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'name',
    ];

    /**
     * Fetch all blog categories
     * @return mixed
     */
    public function fetchAllCategories(
        mixed $order = 'date',
    ) {
        return DB::table('blog_categories')
            ->leftJoin(
                'blog_posts',
                'blog_categories.id',
                'blog_posts.category'
            )
            ->select('blog_categories.*')
            ->addSelect(DB::raw('count(blog_posts.id) as count'))
            ->groupBy('blog_categories.id')

            ->when($order, function ($query) use ($order)
            {
                if ($order == 'date') {
                    $query->orderBy('blog_categories.created_at', 'desc');
                } elseif ($order == 'count') {
                    $query->orderBy('count', 'desc');
                }
            })
            ->when(!$order, function ($query)
            {
                $query->orderBy('blog_categories.created_at', 'desc');
            })
            ->orderBy('blog_categories.created_at', 'desc')
            ->get();
    }

}
