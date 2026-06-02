<?php 

// ============================================================
// PATH: app/Models/AiInsight.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiInsight extends Model
{
    protected $fillable = [
        'user_id',
        'insight_type',
        'message',
        'peak_focus_time',
        'recovery_score',
        'pattern_data',
    ];

    protected $casts = [
        'pattern_data'   => 'array',
        'recovery_score' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}