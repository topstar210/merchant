<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table   = 'activity_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'type',
        'ip_address',
        'browser_agent',
        'created_at',
        'activity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
