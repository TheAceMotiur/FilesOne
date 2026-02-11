<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    use HasFactory;
    protected $table = 'subscribers';
    protected $fillable = [
        'created_at',
        'created_by_ip',
        'updated_at',
        'updated_by_ip',
        'email',
        'verification_code',
        'verified',
    ];
}
