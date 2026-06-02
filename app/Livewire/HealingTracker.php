<?php
// ============================================================
// PATH: app/Livewire/HealingTracker.php
// ============================================================

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorReading;
use App\Models\DistractionLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HealingTracker extends Component
{
    public $todaySpikes      = 0;
    public $yesterdaySpikes  = 0;
    public $weekSpikes       = [];
    public $recoveryPercent  = 0;
    public $currentMood      = '';
    public $currentEmoji     = '';
    public $currentColor     = '';
    public $heartRate        = 0;
    public $focusScore       = 0;
    public $todayMessage     = '';
    public $dayCount         = 0;
    public $bestDay          = '';
    public $peakHour         = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $userId = Auth::id();

        // ── Latest sensor reading ──
        $latest = SensorReading::where('user_id', $userId)
            ->latest()->first();

        if ($latest) {
            $this->heartRate  = $latest->heart_rate;
            $this->focusScore = $latest->focus_score;
        }

        // ── Today's emotional spikes ──
        $this->todaySpikes = SensorReading::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->where('is_distracted', true)
            ->count();

        // ── Yesterday's spikes ──
        $this->yesterdaySpikes = SensorReading::where('user_id', $userId)
            ->whereDate('created_at', today()->subDay())
            ->where('is_distracted', true)
            ->count();

        // ── Weekly spikes ──
        $this->weekSpikes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date   = today()->subDays($i);
            $spikes = SensorReading::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->where('is_distracted', true)
                ->count();

            $this->weekSpikes[] = [
                'day'    => $date->format('D'),
                'date'   => $date->format('d M'),
                'spikes' => $spikes,
                'emoji'  => $this->getSpikeEmoji($spikes),
                'color'  => $this->getSpikeColor($spikes),
                'label'  => $this->getSpikeLabel($spikes),
            ];
        }

        // ── Recovery percentage ──
        $firstDay = SensorReading::where('user_id', $userId)
            ->where('is_distracted', true)
            ->whereDate('created_at', today()->subDays(30))
            ->count();

        $this->recoveryPercent = $firstDay > 0
            ? max(0, round((1 - ($this->todaySpikes / $firstDay)) * 100))
            : 0;

        // ── Day count since start ──
        $firstReading = SensorReading::where('user_id', $userId)
            ->oldest()->first();
        $this->dayCount = $firstReading
            ? Carbon::parse($firstReading->created_at)->diffInDays(today()) + 1
            : 1;

        // ── Current mood ──
        $this->setCurrentMood();

        // ── Today's message ──
        $this->todayMessage = $this->getTodayMessage();

        // ── Best day this week ──
        $best = collect($this->weekSpikes)->sortBy('spikes')->first();
        $this->bestDay = $best['day'] ?? 'N/A';

        // ── Peak focus hour ──
        $peak = SensorReading::where('user_id', $userId)
            ->where('is_distracted', false)
            ->where('created_at', '>=', now()->subDays(7))
            ->get()
            ->groupBy(fn($r) => $r->created_at->format('H'))
            ->map(fn($g) => $g->count())
            ->sortDesc()
            ->keys()
            ->first();

        $this->peakHour = $peak ? $peak . ':00' : '10:00';
    }

    // ── Set current mood based on heart rate ──
    private function setCurrentMood()
    {
        $hr = $this->heartRate;

        if ($hr == 0) {
            $this->currentMood  = 'No reading';
            $this->currentEmoji = '📵';
            $this->currentColor = '#666';
        } elseif ($hr < 60) {
            $this->currentMood  = 'Very Calm';
            $this->currentEmoji = '😴';
            $this->currentColor = '#00bcd4';
        } elseif ($hr <= 72) {
            $this->currentMood  = 'Calm & Focused';
            $this->currentEmoji = '😊';
            $this->currentColor = '#00e676';
        } elseif ($hr <= 82) {
            $this->currentMood  = 'Slightly Tense';
            $this->currentEmoji = '😐';
            $this->currentColor = '#ffeb3b';
        } elseif ($hr <= 92) {
            $this->currentMood  = 'Stressed';
            $this->currentEmoji = '😰';
            $this->currentColor = '#ff9800';
        } else {
            $this->currentMood  = 'Very Stressed';
            $this->currentEmoji = '😤';
            $this->currentColor = '#ff5252';
        }
    }

    // ── Spike emoji ──
    private function getSpikeEmoji($spikes): string
    {
        if ($spikes == 0)    return '🌟';
        if ($spikes <= 5)    return '😊';
        if ($spikes <= 15)   return '😐';
        if ($spikes <= 30)   return '😰';
        return '😤';
    }

    // ── Spike color ──
    private function getSpikeColor($spikes): string
    {
        if ($spikes == 0)    return '#00e676';
        if ($spikes <= 5)    return '#00e676';
        if ($spikes <= 15)   return '#ffeb3b';
        if ($spikes <= 30)   return '#ff9800';
        return '#ff5252';
    }

    // ── Spike label ──
    private function getSpikeLabel($spikes): string
    {
        if ($spikes == 0)    return 'Perfect day!';
        if ($spikes <= 5)    return 'Great day';
        if ($spikes <= 15)   return 'Okay day';
        if ($spikes <= 30)   return 'Hard day';
        return 'Tough day';
    }

    // ── Today's motivational message ──
    private function getTodayMessage(): string
    {
        $spikes = $this->todaySpikes;
        $diff   = $this->yesterdaySpikes - $spikes;

        if ($spikes == 0)
            return "Perfect day so far! Your mind is at peace. 🌟";
        if ($diff > 0)
            return "Better than yesterday! Down by {$diff} spikes. Keep going! 💪";
        if ($diff < 0)
            return "Tough day. But you showed up. That's enough. 💙";
        if ($spikes <= 5)
            return "You're doing really well today! Stay strong. 😊";
        if ($spikes <= 15)
            return "Some hard moments today. But you're still here. 💙";
        return "Every spike you overcame made you stronger. 💪";
    }

    public function render()
    {
        return view('livewire.healing-tracker')
            ->layout('layouts.app');
    }
}