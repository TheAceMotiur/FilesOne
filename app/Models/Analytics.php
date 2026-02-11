<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;
    protected $table = "file_analytics";
    const CREATED_AT = 'created_at';
    const UPDATED_AT = NULL;
    protected $fillable = [
        'created_at',
        'file_id',
        'file_name',
        'uploader',
        'value',
    ];
}
