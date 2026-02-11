<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'created_by_id',
        'created_by_ip',
        'updated_by_id',
        'updated_by_ip',
        'name',
        'url',
        'settings',
        'content',
        'seo',
        'page_key',
        'status',
        'custom',
    ];
}
