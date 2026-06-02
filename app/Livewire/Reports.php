<?php
// PATH: app/Livewire/Reports.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorReading;
use App\Models\DistractionLog;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;

class Reports extends Component
{
    public $activeTab    = 'daily';
    public $dailyData    = [];
    public $weeklyData   = [];
    public $topApps      = [];
    public $recoveryPct  = 0;
    public $avgFocus     = 0;
    public $totalFocused = 0;
    public $totalDistracted = 0;
    public $distractionCount = 0;

    public function mount()
    {
        $this->loadDailyReport();
        $this->loadWeeklyReport();
        $this->loadTopDistractions();
        $this->loadRecoveryScore();
    }

    public function loadDailyReport()
    {
        $readings = SensorReading::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->get();

        $this->avgFocus        = round($readings->avg('focus_score') ?? 0, 1);
        $this->totalFocused    = $readings->where('is_distracted', false)->count();
        $this->totalDistracted = $readings->where('is_distracted', true)->count();
        $this->distractionCount = DistractionLog::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        // Hourly focus scores for chart
        $this->dailyData = $readings
            ->groupBy(fn($r) => $r->created_at->format('H'))
            ->map(fn($g) => round($g->avg('focus_score'), 1))
            ->toArray();
    }

    public function loadWeeklyReport()
    {
        $this->weeklyData = SensorReading::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('D'))
            ->map(fn($g) => round($g->avg('focus_score'), 1))
            ->toArray();
    }

    public function loadTopDistractions()
    {
        $this->topApps = DistractionLog::where('user_id', Auth::id())
            ->whereNotNull('app_name')
            ->selectRaw('app_name, COUNT(*) as count')
            ->groupBy('app_name')
            ->orderByDesc('count')
            ->take(5)
            ->get()
            ->toArray();
    }

    public function loadRecoveryScore()
    {
        $first = DistractionLog::where('user_id', Auth::id())
            ->where('trigger_type', 'emotional')
            ->whereDate('created_at', now()->subDays(30))
            ->count();

        $last = DistractionLog::where('user_id', Auth::id())
            ->where('trigger_type', 'emotional')
            ->whereDate('created_at', today())
            ->count();

        $this->recoveryPct = $first > 0
            ? max(0, round((1 - ($last / $first)) * 100))
            : 0;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.reports')
            ->layout('layouts.app');
    }
}