<?php 
// ============================================================
// PATH: app/Models/FocusSession.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FocusSession extends Model
{
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'total_seconds',
        'focused_seconds',
        'distracted_seconds',
        'avg_focus_score',
        'avg_heart_rate',
    ];

    protected $casts = [
        'started_at'      => 'datetime',
        'ended_at'        => 'datetime',
        'avg_focus_score' => 'float',
        'avg_heart_rate'  => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

