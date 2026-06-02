<?php
// PATH: app/Http/Controllers/FocusController.php

namespace App\Http\Controllers;

use App\Models\FocusSession;
use App\Models\SensorReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FocusController extends Controller
{
    // GET /api/focus/score
    // Returns current live focus score
    public function currentScore()
    {
        $latest = SensorReading::where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$latest) {
            return response()->json([
                'focus_score'   => 0,
                'heart_rate'    => 0,
                'spo2'          => 0,
                'is_distracted' => false,
                'message'       => 'No sensor data yet',
            ]);
        }

        return response()->json([
            'focus_score'      => $latest->focus_score,
            'heart_rate'       => $latest->heart_rate,
            'spo2'             => $latest->spo2,
            'is_distracted'    => $latest->is_distracted,
            'distraction_type' => $latest->distraction_type,
            'recorded_at'      => $latest->created_at->diffForHumans(),
        ]);
    }

    // GET /api/focus/session
    // Returns current active session info
    public function currentSession()
    {
        $session = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json([
                'active'  => false,
                'message' => 'No active session',
            ]);
        }

        $totalSeconds     = now()->diffInSeconds($session->started_at);
        $readings         = SensorReading::where('user_id', Auth::id())
            ->where('created_at', '>=', $session->started_at)
            ->get();

        $focusedSeconds   = $readings->where('is_distracted', false)->count();
        $distractedSeconds = $readings->where('is_distracted', true)->count();
        $efficiencyPct    = $totalSeconds > 0
            ? round(($focusedSeconds / $totalSeconds) * 100)
            : 0;

        return response()->json([
            'active'             => true,
            'started_at'         => $session->started_at->format('h:i A'),
            'total_seconds'      => $totalSeconds,
            'focused_seconds'    => $focusedSeconds,
            'distracted_seconds' => $distractedSeconds,
            'efficiency_percent' => $efficiencyPct,
            'avg_focus_score'    => round($readings->avg('focus_score') ?? 0, 1),
            'avg_heart_rate'     => round($readings->avg('heart_rate') ?? 0, 1),
        ]);
    }

    // POST /api/focus/start
    // Starts a new focus session
    public function startSession()
    {
        // End any existing active session first
        FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        $session = FocusSession::create([
            'user_id'    => Auth::id(),
            'started_at' => now(),
        ]);

        return response()->json([
            'success'    => true,
            'session_id' => $session->id,
            'started_at' => $session->started_at->format('h:i A'),
            'message'    => 'Focus session started! 💪',
        ]);
    }

    // POST /api/focus/stop
    // Stops the current session & saves summary
    public function stopSession()
    {
        $session = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->latest()
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'No active session found',
            ]);
        }

        // Calculate session stats
        $readings = SensorReading::where('user_id', Auth::id())
            ->where('created_at', '>=', $session->started_at)
            ->get();

        $totalSeconds      = now()->diffInSeconds($session->started_at);
        $focusedSeconds    = $readings->where('is_distracted', false)->count();
        $distractedSeconds = $readings->where('is_distracted', true)->count();
        $avgFocusScore     = round($readings->avg('focus_score') ?? 0, 1);
        $avgHeartRate      = round($readings->avg('heart_rate') ?? 0, 1);

        // Save session summary
        $session->update([
            'ended_at'           => now(),
            'total_seconds'      => $totalSeconds,
            'focused_seconds'    => $focusedSeconds,
            'distracted_seconds' => $distractedSeconds,
            'avg_focus_score'    => $avgFocusScore,
            'avg_heart_rate'     => $avgHeartRate,
        ]);

        // Grade the session
        $efficiencyPct = $totalSeconds > 0
            ? round(($focusedSeconds / $totalSeconds) * 100)
            : 0;

        $grade = match(true) {
            $efficiencyPct >= 85 => ['A+', '🏆 Outstanding!'],
            $efficiencyPct >= 70 => ['A',  '🎯 Great work!'],
            $efficiencyPct >= 55 => ['B',  '💪 Good effort!'],
            $efficiencyPct >= 40 => ['C',  '😊 Keep going!'],
            default              => ['D',  '🌱 Keep trying!'],
        };

        return response()->json([
            'success'            => true,
            'total_seconds'      => $totalSeconds,
            'focused_seconds'    => $focusedSeconds,
            'distracted_seconds' => $distractedSeconds,
            'efficiency_percent' => $efficiencyPct,
            'avg_focus_score'    => $avgFocusScore,
            'avg_heart_rate'     => $avgHeartRate,
            'grade'              => $grade[0],
            'message'            => $grade[1],
        ]);
    }

    // GET /api/focus/history
    // Returns past sessions list
    public function history()
    {
        $sessions = FocusSession::where('user_id', Auth::id())
            ->whereNotNull('ended_at')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($s) {
                $efficiency = $s->total_seconds > 0
                    ? round(($s->focused_seconds / $s->total_seconds) * 100)
                    : 0;
                return [
                    'id'                 => $s->id,
                    'date'               => $s->started_at->format('D, d M'),
                    'started_at'         => $s->started_at->format('h:i A'),
                    'ended_at'           => $s->ended_at->format('h:i A'),
                    'duration_mins'      => round($s->total_seconds / 60),
                    'efficiency_percent' => $efficiency,
                    'avg_focus_score'    => $s->avg_focus_score,
                    'avg_heart_rate'     => $s->avg_heart_rate,
                ];
            });

        return response()->json([
            'sessions'       => $sessions,
            'total_sessions' => $sessions->count(),
        ]);
    }
}