<?php
// PATH: app/Livewire/Dashboard.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorReading;
use App\Models\DistractionLog;
use App\Models\FocusSession;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $heartRate      = 72;
    public $focusScore     = 85;
    public $spo2           = 98;
    public $isDistracted   = false;
    public $sessionTime    = 0;
    public $focusedTime    = 0;
    public $distractedTime = 0;
    public $sessionRunning = false;
    public $warning        = '';
    public $recentLogs     = [];
    public $efficiencyPct  = 0;

    public function mount()
    {
        $this->loadLatestData();
        $this->recentLogs = DistractionLog::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();
    }

    // Auto refresh every 2 seconds via wire:poll
    public function loadLatestData()
    {
        $latest = SensorReading::where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($latest) {
            $this->heartRate    = $latest->heart_rate;
            $this->focusScore   = $latest->focus_score;
            $this->spo2         = $latest->spo2;
            $this->isDistracted = $latest->is_distracted;
            $this->warning      = $latest->is_distracted
                ? '⚠️ Distraction detected! Get back to work!'
                : '';
        }

        if ($this->sessionTime > 0) {
            $this->efficiencyPct = round(
                ($this->focusedTime / $this->sessionTime) * 100
            );
        }
    }

    public function startSession()
    {
        $this->sessionRunning = true;
        FocusSession::create([
            'user_id'    => Auth::id(),
            'started_at' => now(),
        ]);
    }

    public function stopSession()
    {
        $this->sessionRunning = false;
        FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->update([
                'ended_at'           => now(),
                'total_seconds'      => $this->sessionTime,
                'focused_seconds'    => $this->focusedTime,
                'distracted_seconds' => $this->distractedTime,
                'avg_focus_score'    => $this->focusScore,
                'avg_heart_rate'     => $this->heartRate,
            ]);
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app');
    }
}