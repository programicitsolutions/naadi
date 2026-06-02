{{-- PATH: resources/views/livewire/reports.blade.php --}}

<div class="min-h-screen bg-gray-950 text-white p-4 pb-20">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-black text-purple-400">📊 Reports</h1>
        <p class="text-xs text-gray-500">Your focus analytics</p>
    </div>

    {{-- TABS --}}
    <div class="flex gap-2 mb-6">
        @foreach(['daily' => '📅 Daily', 'weekly' => '📆 Weekly', 'recovery' => '💔 Recovery'] as $tab => $label)
        <button wire:click="setTab('{{ $tab }}')"
            class="flex-1 py-2 rounded-xl text-xs font-bold transition
            {{ $activeTab === $tab ? 'bg-purple-600 text-white' : 'bg-gray-900 text-gray-500' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- DAILY TAB --}}
    @if($activeTab === 'daily')

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-xs text-gray-500 mb-1">🎯 Avg Focus</p>
            <p class="text-3xl font-black
                @if($avgFocus > 70) text-green-400
                @elseif($avgFocus > 40) text-yellow-400
                @else text-red-400 @endif">
                {{ $avgFocus }}
            </p>
            <p class="text-xs text-gray-600">out of 100</p>
        </div>
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-xs text-gray-500 mb-1">⚠️ Distractions</p>
            <p class="text-3xl font-black text-orange-400">{{ $distractionCount }}</p>
            <p class="text-xs text-gray-600">times today</p>
        </div>
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-xs text-gray-500 mb-1">✅ Focused</p>
            <p class="text-3xl font-black text-green-400">{{ $totalFocused }}s</p>
            <p class="text-xs text-gray-600">seconds</p>
        </div>
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-xs text-gray-500 mb-1">😵 Distracted</p>
            <p class="text-3xl font-black text-red-400">{{ $totalDistracted }}s</p>
            <p class="text-xs text-gray-600">seconds</p>
        </div>
    </div>

    {{-- Hourly Chart --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800 mb-4">
        <p class="text-sm font-bold text-purple-400 mb-3">⏰ Hourly Focus Score</p>
        <canvas id="dailyChart" height="100"></canvas>
    </div>

    {{-- Top Distractions --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
        <p class="text-sm font-bold text-purple-400 mb-3">📱 Top Distractions</p>
        @forelse($topApps as $app)
        <div class="flex justify-between items-center py-2 border-b border-gray-800">
            <span class="text-sm text-gray-300">📱 {{ $app['app_name'] }}</span>
            <span class="text-sm font-bold text-orange-400">{{ $app['count'] }}x</span>
        </div>
        @empty
        <p class="text-gray-600 text-sm text-center py-4">No distractions logged! 🎉</p>
        @endforelse
    </div>

    @endif

    {{-- WEEKLY TAB --}}
    @if($activeTab === 'weekly')
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800 mb-4">
        <p class="text-sm font-bold text-purple-400 mb-3">📆 Weekly Focus Score</p>
        <canvas id="weeklyChart" height="120"></canvas>
    </div>

    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
        <p class="text-sm font-bold text-purple-400 mb-3">📊 Day by Day</p>
        @foreach($weeklyData as $day => $score)
        <div class="mb-3">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-gray-400">{{ $day }}</span>
                <span class="font-bold
                    @if($score > 70) text-green-400
                    @elseif($score > 40) text-yellow-400
                    @else text-red-400 @endif">
                    {{ $score }}%
                </span>
            </div>
            <div class="bg-gray-800 rounded-full h-2">
                <div class="h-full rounded-full
                    @if($score > 70) bg-green-400
                    @elseif($score > 40) bg-yellow-400
                    @else bg-red-400 @endif"
                     style="width: {{ $score }}%">
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- RECOVERY TAB 😄 --}}
    @if($activeTab === 'recovery')
    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 mb-4 text-center">
        <p class="text-4xl mb-2">💔</p>
        <p class="text-sm text-gray-500 mb-4">Moving On Recovery Score</p>

        {{-- Radial score --}}
        <div class="relative inline-flex items-center justify-center mb-4">
            <svg width="140" height="140" viewBox="0 0 140 140">
                <circle cx="70" cy="70" r="54" fill="none"
                    stroke="#1f2937" stroke-width="10"/>
                <circle cx="70" cy="70" r="54" fill="none"
                    stroke="#7c4dff" stroke-width="10"
                    stroke-dasharray="{{ ($recoveryPct / 100) * 339 }} 339"
                    stroke-linecap="round"
                    transform="rotate(-90 70 70)"/>
                <text x="70" y="65" text-anchor="middle"
                    fill="white" font-size="24" font-weight="bold">
                    {{ $recoveryPct }}%
                </text>
                <text x="70" y="82" text-anchor="middle"
                    fill="#6b7280" font-size="10">
                    Healed
                </text>
            </svg>
        </div>

        <p class="text-sm font-bold text-purple-400 mb-2">
            @if($recoveryPct >= 75) 🏆 Almost there! You're doing amazing!
            @elseif($recoveryPct >= 50) 💪 Halfway through! Keep going!
            @elseif($recoveryPct >= 25) 😊 Making progress! Day by day!
            @else 🌱 Every journey starts with one step!
            @endif
        </p>
        <p class="text-xs text-gray-600">
            Based on 30 days of emotional spike data
        </p>
    </div>

    {{-- Daily spike graph --}}
    <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
        <p class="text-sm font-bold text-purple-400 mb-1">📉 Emotional Spikes Reducing</p>
        <p class="text-xs text-gray-600 mb-3">Going down = You're healing 💪</p>
        <canvas id="recoveryChart" height="100"></canvas>
    </div>
    @endif

</div>

{{-- Charts --}}
<script>
document.addEventListener('livewire:load', function () {

    // Daily Chart
    @if($activeTab === 'daily')
    const dailyCtx = document.getElementById('dailyChart');
    if (dailyCtx) {
        new Chart(dailyCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json(array_keys($dailyData)),
                datasets: [{
                    label: 'Focus Score',
                    data: @json(array_values($dailyData)),
                    backgroundColor: 'rgba(124, 77, 255, 0.6)',
                    borderColor: '#7c4dff',
                    borderWidth: 2,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { min: 0, max: 100,
                         grid: { color: '#1f2937' },
                         ticks: { color: '#6b7280', font: { size: 10 } }
                    },
                    x: { grid: { display: false },
                         ticks: { color: '#6b7280', font: { size: 10 } }
                    }
                }
            }
        });
    }
    @endif

    // Weekly Chart
    @if($activeTab === 'weekly')
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json(array_keys($weeklyData)),
                datasets: [{
                    label: 'Focus Score',
                    data: @json(array_values($weeklyData)),
                    borderColor: '#00e676',
                    backgroundColor: 'rgba(0, 230, 118, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#00e676',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { min: 0, max: 100,
                         grid: { color: '#1f2937' },
                         ticks: { color: '#6b7280', font: { size: 10 } }
                    },
                    x: { grid: { display: false },
                         ticks: { color: '#6b7280', font: { size: 10 } }
                    }
                }
            }
        });
    }
    @endif

});
</script>