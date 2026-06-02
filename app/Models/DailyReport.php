<?php 
// ============================================================
// PATH: app/Models/DailyReport.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $fillable = [
        'user_id',
        'report_date',
        'avg_focus_score',
        'avg_heart_rate',
        'total_focused_mins',
        'total_distracted_mins',
        'distraction_count',
        'efficiency_percent',
        'emotional_spikes',
    ];

    protected $casts = [
        'report_date'      => 'date',
        'avg_focus_score'  => 'float',
        'avg_heart_rate'   => 'float',
        'efficiency_percent' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}