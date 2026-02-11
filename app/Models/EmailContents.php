<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContents extends Model
{
    use HasFactory;

    protected $table = "email_contents";
    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'updated_at',
        'updated_by_id',
        'updated_by_ip',
        'name',
        'content',
        'variables',
    ];
}
