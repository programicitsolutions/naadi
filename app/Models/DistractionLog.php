<?php 

// ============================================================
// PATH: app/Models/DistractionLog.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistractionLog extends Model
{
    protected $fillable = [
        'user_id',
        'trigger_type',
        'app_name',
        'heart_rate_at_trigger',
        'duration_seconds',
        'detected_at',
    ];

    protected $casts = [
        'detected_at'           => 'datetime',
        'heart_rate_at_trigger' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}