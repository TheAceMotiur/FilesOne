<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;
    protected $table = "file_affiliate";
    const CREATED_AT = 'created_at';
    const UPDATED_AT = NULL;
    protected $fillable = [
        'created_at',
        'file_id',
        'file_name',
        'uploader',
        'value',
        'additional',
    ];
}
