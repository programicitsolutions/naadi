<?php
// PATH: app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use App\Models\DistractionLog;
use App\Models\FocusSession;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // GET /api/reports/daily
    // Returns today's full report
    public function daily()
    {
        $userId   = Auth::id();
        $today    = today();

        $readings = SensorReading::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->get();

        $distractions = DistractionLog::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->get();

        $sessions = FocusSession::where('user_id', $userId)
            ->whereDate('started_at', $today)
            ->whereNotNull('ended_at')
            ->get();

        // Hourly focus breakdown
        $hourlyFocus = $readings
            ->groupBy(fn($r) => $r->created_at->format('H'))
            ->map(fn($g) => round($g->avg('focus_score'), 1));

        // Top distracting apps
        $topApps = $distractions
            ->whereNotNull('app_name')
            ->groupBy('app_name')
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->take(5);

        // Distraction by hour
        $distractionByHour = $distractions
            ->groupBy(fn($d) => Carbon::parse($d->detected_at)->format('H'))
            ->map(fn($g) => $g->count());

        $totalSeconds      = $readings->count();
        $focusedSeconds    = $readings->where('is_distracted', false)->count();
        $distractedSeconds = $readings->where('is_distracted', true)->count();
        $efficiency        = $totalSeconds > 0
            ? round(($focusedSeconds / $totalSeconds) * 100)
            : 0;

        return response()->json([
            'date'                => $today->format('D, d M Y'),
            'avg_focus_score'     => round($readings->avg('focus_score') ?? 0, 1),
            'avg_heart_rate'      => round($readings->avg('heart_rate') ?? 0, 1),
            'avg_spo2'            => round($readings->avg('spo2') ?? 0, 1),
            'total_focused_mins'  => round($focusedSeconds / 60, 1),
            'total_distracted_mins' => round($distractedSeconds / 60, 1),
            'distraction_count'   => $distractions->count(),
            'efficiency_percent'  => $efficiency,
            'sessions_count'      => $sessions->count(),
            'hourly_focus'        => $hourlyFocus,
            'top_apps'            => $topApps,
            'distraction_by_hour' => $distractionByHour,
            'peak_focus_hour'     => $hourlyFocus->sortDesc()->keys()->first() . ':00',
            'worst_hour'          => $hourlyFocus->sort()->keys()->first() . ':00',
            'grade'               => $this->getGrade($efficiency),
        ]);
    }

    // GET /api/reports/weekly
    // Returns last 7 days report
    public function weekly()
    {
        $userId = Auth::id();

        $weeklyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date     = today()->subDays($i);
            $readings = SensorReading::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->get();

            $distractions = DistractionLog::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->count();

            $totalSeconds   = $readings->count();
            $focusedSeconds = $readings->where('is_distracted', false)->count();
            $efficiency     = $totalSeconds > 0
                ? round(($focusedSeconds / $totalSeconds) * 100)
                : 0;

            $weeklyData->push([
                'date'               => $date->format('D'),
                'full_date'          => $date->format('d M'),
                'avg_focus_score'    => round($readings->avg('focus_score') ?? 0, 1),
                'avg_heart_rate'     => round($readings->avg('heart_rate') ?? 0, 1),
                'distraction_count'  => $distractions,
                'efficiency_percent' => $efficiency,
                'focused_mins'       => round($focusedSeconds / 60, 1),
            ]);
        }

        // Weekly summary
        $avgFocus      = round($weeklyData->avg('avg_focus_score'), 1);
        $totalFocused  = $weeklyData->sum('focused_mins');
        $bestDay       = $weeklyData->sortByDesc('avg_focus_score')->first();
        $worstDay      = $weeklyData->sortBy('avg_focus_score')->first();

        return response()->json([
            'days'              => $weeklyData,
            'avg_focus_score'   => $avgFocus,
            'total_focused_hrs' => round($totalFocused / 60, 1),
            'best_day'          => $bestDay['date'] ?? 'N/A',
            'worst_day'         => $worstDay['date'] ?? 'N/A',
            'weekly_grade'      => $this->getGrade($avgFocus),
            'trend'             => $this->getWeeklyTrend($weeklyData),
        ]);
    }

    // GET /api/reports/monthly
    // Returns last 30 days report
    public function monthly()
    {
        $userId = Auth::id();

        $monthlyData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date     = today()->subDays($i);
            $readings = SensorReading::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->get();

            $totalSeconds   = $readings->count();
            $focusedSeconds = $readings->where('is_distracted', false)->count();
            $efficiency     = $totalSeconds > 0
                ? round(($focusedSeconds / $totalSeconds) * 100)
                : 0;

            $monthlyData->push([
                'date'            => $date->format('d'),
                'focus_score'     => round($readings->avg('focus_score') ?? 0, 1),
                'efficiency'      => $efficiency,
                'focused_mins'    => round($focusedSeconds / 60, 1),
            ]);
        }

        // Emotional recovery tracking (Moving On Mode 😄)
        $emotionalSpikes = DistractionLog::where('user_id', $userId)
            ->where('trigger_type', 'emotional')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as spikes')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $firstSpikes   = $emotionalSpikes->first()->spikes ?? 0;
        $lastSpikes    = $emotionalSpikes->last()->spikes ?? 0;
        $recoveryPct   = $firstSpikes > 0
            ? max(0, round((1 - ($lastSpikes / $firstSpikes)) * 100))
            : 0;

        return response()->json([
            'days'              => $monthlyData,
            'avg_focus_score'   => round($monthlyData->avg('focus_score'), 1),
            'total_focused_hrs' => round($monthlyData->sum('focused_mins') / 60, 1),
            'best_focus_day'    => $monthlyData->sortByDesc('focus_score')->first()['date'] ?? 'N/A',
            'recovery_percent'  => $recoveryPct,
            'emotional_spikes'  => $emotionalSpikes,
            'monthly_grade'     => $this->getGrade($monthlyData->avg('focus_score')),
        ]);
    }

    // Generate daily report — called by cron job
    public function generateDailyReport($userId = null)
    {
        $userId   = $userId ?? Auth::id();
        $today    = today();
        $readings = SensorReading::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->get();

        if ($readings->isEmpty()) return null;

        $totalSeconds      = $readings->count();
        $focusedSeconds    = $readings->where('is_distracted', false)->count();
        $distractedSeconds = $readings->where('is_distracted', true)->count();
        $efficiency        = $totalSeconds > 0
            ? round(($focusedSeconds / $totalSeconds) * 100)
            : 0;

        // Save or update daily report
        return DailyReport::updateOrCreate(
            ['user_id' => $userId, 'report_date' => $today],
            [
                'avg_focus_score'       => round($readings->avg('focus_score') ?? 0, 1),
                'avg_heart_rate'        => round($readings->avg('heart_rate') ?? 0, 1),
                'total_focused_mins'    => round($focusedSeconds / 60),
                'total_distracted_mins' => round($distractedSeconds / 60),
                'distraction_count'     => DistractionLog::where('user_id', $userId)
                                            ->whereDate('created_at', $today)->count(),
                'efficiency_percent'    => $efficiency,
                'emotional_spikes'      => DistractionLog::where('user_id', $userId)
                                            ->where('trigger_type', 'emotional')
                                            ->whereDate('created_at', $today)->count(),
            ]
        );
    }

    // Grade helper
    private function getGrade($score): array
    {
        return match(true) {
            $score >= 85 => ['A+', '🏆 Outstanding!',  '#00e676'],
            $score >= 70 => ['A',  '🎯 Excellent!',    '#00e676'],
            $score >= 55 => ['B',  '💪 Good!',         '#ffeb3b'],
            $score >= 40 => ['C',  '😊 Average',       '#ff9800'],
            default      => ['D',  '🌱 Needs work',    '#ff5252'],
        };
    }

    // Weekly trend helper
    private function getWeeklyTrend($weeklyData): string
    {
        $firstHalf  = $weeklyData->take(3)->avg('avg_focus_score');
        $secondHalf = $weeklyData->skip(4)->avg('avg_focus_score');

        if ($secondHalf > $firstHalf + 5)  return 'improving 📈';
        if ($secondHalf < $firstHalf - 5)  return 'declining 📉';
        return 'stable ➡️';
    }
}