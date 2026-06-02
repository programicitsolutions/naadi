<?php 

// ============================================================
// PATH: app/Models/SensorReading.php
// ============================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = [
        'user_id',
        'heart_rate',
        'spo2',
        'ecg_signal',
        'focus_score',
        'is_distracted',
        'distraction_type',
    ];

    protected $casts = [
        'is_distracted' => 'boolean',
        'heart_rate'    => 'float',
        'spo2'          => 'float',
        'ecg_signal'    => 'float',
        'focus_score'   => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



