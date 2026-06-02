<?php
// PATH: app/Http/Controllers/SensorController.php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use App\Models\DistractionLog;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // ESP32 sends data every second to this endpoint
    // POST /api/sensor/store
    public function store(Request $request)
    {
        $focusScore   = $this->calculateFocusScore(
                            $request->heart_rate,
                            $request->ecg_signal
                        );
        $isDistracted = $this->isDistracted(
                            $request->heart_rate,
                            $request->ecg_signal
                        );

        $reading = SensorReading::create([
            'user_id'          => $request->user_id,
            'heart_rate'       => $request->heart_rate,
            'spo2'             => $request->spo2 ?? 98,
            'ecg_signal'       => $request->ecg_signal,
            'focus_score'      => $focusScore,
            'is_distracted'    => $isDistracted,
            'distraction_type' => $request->distraction_type ?? null,
        ]);

        return response()->json([
            'success'       => true,
            'focus_score'   => $reading->focus_score,
            'is_distracted' => $reading->is_distracted,
            'vibrate'       => $isDistracted, // ESP32 reads this to trigger buzz!
        ]);
    }

    // Calculate focus score 0-100
    private function calculateFocusScore($heartRate, $ecgSignal): float
    {
        // Normal HR 60-70 = focused, High HR 90+ = distracted
        $hrScore  = max(0, 100 - (($heartRate - 65) * 2));
        $ecgScore = min(100, abs($ecgSignal) * 0.8);

        return round(($hrScore * 0.6) + ($ecgScore * 0.4), 2);
    }

    // Is user distracted?
    private function isDistracted($heartRate, $ecgSignal): bool
    {
        return $heartRate > 85 || $heartRate < 55;
    }

    // Log distraction event
    // POST /api/sensor/distraction
    public function logDistraction(Request $request)
    {
        DistractionLog::create([
            'user_id'               => $request->user_id,
            'trigger_type'          => $request->trigger_type,
            'app_name'              => $request->app_name ?? null,
            'heart_rate_at_trigger' => $request->heart_rate,
            'duration_seconds'      => $request->duration ?? 0,
            'detected_at'           => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // Get latest sensor reading
    // GET /api/sensor/latest/{user_id}
    public function latest($userId)
    {
        $reading = SensorReading::where('user_id', $userId)
                    ->latest()
                    ->first();

        return response()->json($reading);
    }
}