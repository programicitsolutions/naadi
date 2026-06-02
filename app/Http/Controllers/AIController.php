<?php
// PATH: app/Http/Controllers/AIController.php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use App\Models\DistractionLog;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
    // GET /api/ai/insights
    public function getInsights()
    {
        $userId  = Auth::id();
        $readings = SensorReading::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(7))
            ->get();

        // Find peak focus hour
        $peakHour = $readings
            ->groupBy(fn($r) => $r->created_at->format('H'))
            ->map(fn($group) => $group->avg('focus_score'))
            ->sortDesc()
            ->keys()
            ->first();

        // Top distracting app
        $topDistraction = DistractionLog::where('user_id', $userId)
            ->whereNotNull('app_name')
            ->selectRaw('app_name, COUNT(*) as count')
            ->groupBy('app_name')
            ->orderByDesc('count')
            ->first();

        $avgFocus = round($readings->avg('focus_score'), 1);

        return response()->json([
            'peak_focus_hour'  => $peakHour ? "{$peakHour}:00" : '10:00',
            'avg_focus_score'  => $avgFocus,
            'top_distraction'  => $topDistraction->app_name ?? 'None',
            'insight_message'  => $this->generateInsight($avgFocus, $peakHour),
            'weekly_trend'     => $avgFocus > 65 ? 'improving' : 'needs_work',
        ]);
    }

    // GET /api/ai/recovery
    // Moving On / Heartbreak recovery tracker 😄
    public function recoveryScore()
    {
        $userId = Auth::id();

        $dailySpikes = DistractionLog::where('user_id', $userId)
            ->where('trigger_type', 'emotional')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as spikes')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $firstDaySpikes = $dailySpikes->first()->spikes ?? 0;
        $lastDaySpikes  = $dailySpikes->last()->spikes ?? 0;
        $recoveryPct    = $firstDaySpikes > 0
            ? round((1 - ($lastDaySpikes / $firstDaySpikes)) * 100)
            : 0;

        return response()->json([
            'recovery_percent' => max(0, $recoveryPct),
            'daily_spikes'     => $dailySpikes,
            'message'          => $recoveryPct > 50
                ? "You're healing beautifully! 💪"
                : "Keep going! Every day gets better! 😊",
        ]);
    }

    // GET /api/ai/peak-hours
    public function peakHours()
    {
        $userId = Auth::id();

        $hourlyData = SensorReading::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('H'))
            ->map(fn($group) => round($group->avg('focus_score'), 1))
            ->sortKeys();

        return response()->json([
            'hourly_scores' => $hourlyData,
            'best_hour'     => $hourlyData->sortDesc()->keys()->first(),
            'worst_hour'    => $hourlyData->sort()->keys()->first(),
        ]);
    }

    // Generate human readable insight message
    private function generateInsight($avgFocus, $peakHour): string
    {
        if ($avgFocus >= 80) {
            return "Excellent week! Your focus is outstanding. Keep it up! 🏆";
        } elseif ($avgFocus >= 60) {
            return "Good progress! You focus best around {$peakHour}:00. Schedule important work then! 💪";
        } else {
            return "Your focus needs attention. Try breathing exercises when distracted. 🧘";
        }
    }
}