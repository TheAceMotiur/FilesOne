<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLogs extends Model
{
    use HasFactory;

    protected $table = 'email_logs';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = NULL;
    protected $fillable = [
        'created_at',
        'created_by_ip',
        'name',
        'email',
        'subject',
        'message',
    ];

}
