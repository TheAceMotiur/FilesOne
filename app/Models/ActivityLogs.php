<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'created_at',
        'created_by_id',
        'created_by_ip',
        'action',
        'details',
        'additional',
    ];
}
