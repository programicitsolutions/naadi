{{-- PATH: resources/views/livewire/dashboard.blade.php --}}

<div wire:poll.2000ms="loadLatestData" style="padding:20px;padding-bottom:100px;">

    <style>
        .glass-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            transition: all 0.3s;
        }
        .glass-card:hover {
            border-color: rgba(124,77,255,0.25);
            background: rgba(124,77,255,0.04);
        }
        .gradient-text {
            background: linear-gradient(135deg, #7c4dff, #00bcd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
        }
        .sensor-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 18px;
            padding: 16px;
            flex: 1;
            transition: all 0.3s;
        }
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
            margin-top: 8px;
        }
        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.8s ease;
        }
        .log-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .log-item:last-child { border-bottom: none; }
        .btn-start {
            background: linear-gradient(135deg, #00e676, #00bcd4);
            border: none;
            padding: 12px 28px;
            border-radius: 14px;
            color: #000;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.3px;
        }
        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,230,118,0.3);
        }
        .btn-stop {
            background: linear-gradient(135deg, #ff5252, #ff1744);
            border: none;
            padding: 12px 28px;
            border-radius: 14px;
            color: white;
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-stop:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255,82,82,0.3);
        }
        .warning-banner {
            background: linear-gradient(135deg, rgba(255,23,68,0.2), rgba(255,82,82,0.1));
            border: 1px solid rgba(255,82,82,0.4);
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            animation: warningPulse 1s ease-in-out infinite;
        }
        @keyframes warningPulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,82,82,0.3); }
            50% { box-shadow: 0 0 20px rgba(255,82,82,0.2); }
        }
        .heartbeat {
            animation: heartbeat 1s ease-in-out infinite;
        }
        @keyframes heartbeat {
            0%,100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .ring-container {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1.5px;
            margin-bottom: 14px;
            text-transform: uppercase;
        }
    </style>

    {{-- ═══════════════════════════════ --}}
    {{-- HEADER --}}
    {{-- ═══════════════════════════════ --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;padding-top:8px;">
        <div>
            <p style="color:rgba(255,255,255,0.3);font-size:12px;margin-bottom:2px;">
                Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }} 👋
            </p>
            <h1 style="font-size:22px;font-weight:900;color:white;">
                {{ Auth::user()->name }}
            </h1>
            <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
                <div style="width:6px;height:6px;border-radius:50%;background:#00e676;animation:pulseDot 2s infinite;"></div>
                <span style="color:#00e676;font-size:11px;font-weight:600;">Device Active</span>
            </div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            @if(!$sessionRunning)
                <button wire:click="startSession" class="btn-start">▶ Start</button>
            @else
                <button wire:click="stopSession" class="btn-stop">⏹ Stop</button>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════ --}}
    {{-- WARNING BANNER --}}
    {{-- ═══════════════════════════════ --}}
    @if($warning)
    <div class="warning-banner" style="margin-bottom:20px;">
        <span style="font-size:28px;" class="heartbeat">📳</span>
        <div>
            <p style="color:#ff5252;font-weight:800;font-size:14px;margin-bottom:2px;">
                DISTRACTION DETECTED
            </p>
            <p style="color:rgba(255,255,255,0.6);font-size:12px;">{{ $warning }}</p>
        </div>
        <div style="margin-left:auto;background:rgba(255,82,82,0.2);border-radius:10px;padding:6px 12px;">
            <span style="color:#ff5252;font-size:11px;font-weight:700;">BUZZ!</span>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════ --}}
    {{-- FOCUS SCORE RING --}}
    {{-- ═══════════════════════════════ --}}
    <div class="glass-card" style="padding:24px;margin-bottom:16px;text-align:center;">
        <p class="section-title">🎯 Focus Score</p>

        {{-- SVG Ring --}}
        <div class="ring-container" style="margin-bottom:16px;">
            <svg width="130" height="130" viewBox="0 0 130 130" style="transform:rotate(-90deg)">
                <circle cx="65" cy="65" r="54"
                    fill="none" stroke="rgba(255,255,255,0.06)" stroke-width="10"/>
                <circle cx="65" cy="65" r="54"
                    fill="none"
                    stroke="{{ $focusScore > 70 ? '#00e676' : ($focusScore > 40 ? '#ffeb3b' : '#ff5252') }}"
                    stroke-width="10"
                    stroke-linecap="round"
                    stroke-dasharray="{{ ($focusScore / 100) * 339.3 }} 339.3"
                    style="transition: stroke-dasharray 1s ease, stroke 0.5s ease;
                           filter: drop-shadow(0 0 8px {{ $focusScore > 70 ? '#00e676' : ($focusScore > 40 ? '#ffeb3b' : '#ff5252') }});"/>
            </svg>
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;">
                <p style="font-size:32px;font-weight:900;
                    color:{{ $focusScore > 70 ? '#00e676' : ($focusScore > 40 ? '#ffeb3b' : '#ff5252') }};
                    line-height:1;">
                    {{ $focusScore }}
                </p>
                <p style="color:rgba(255,255,255,0.3);font-size:11px;font-weight:600;">/ 100</p>
            </div>
        </div>

        {{-- Status badge --}}
        <div style="display:inline-flex;align-items:center;gap:8px;
            background:{{ $isDistracted ? 'rgba(255,82,82,0.1)' : 'rgba(0,230,118,0.1)' }};
            border:1px solid {{ $isDistracted ? 'rgba(255,82,82,0.3)' : 'rgba(0,230,118,0.3)' }};
            border-radius:20px;padding:6px 16px;">
            <div style="width:7px;height:7px;border-radius:50%;
                background:{{ $isDistracted ? '#ff5252' : '#00e676' }};
                animation:pulseDot 1.5s infinite;"></div>
            <span style="font-size:13px;font-weight:700;
                color:{{ $isDistracted ? '#ff5252' : '#00e676' }};">
                {{ $isDistracted ? '⚠️ Distracted' : '✅ In Focus' }}
            </span>
        </div>

        <style>
            @keyframes pulseDot {
                0%,100% { box-shadow: 0 0 0 0 currentColor; opacity:1; }
                50% { box-shadow: 0 0 6px 3px transparent; opacity:0.7; }
            }
        </style>
    </div>

    {{-- ═══════════════════════════════ --}}
    {{-- LIVE SENSORS --}}
    {{-- ═══════════════════════════════ --}}
    <p class="section-title" style="padding:0 4px;">Live Sensors</p>
    <div style="display:flex;gap:10px;margin-bottom:16px;">

        {{-- Heart Rate --}}
        <div class="sensor-card">
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                <span style="font-size:18px;" class="{{ $isDistracted ? 'heartbeat' : '' }}">❤️</span>
                <span style="color:rgba(255,255,255,0.4);font-size:11px;font-weight:600;letter-spacing:0.5px;">HEART RATE</span>
            </div>
            <p style="font-size:30px;font-weight:900;color:#ff5252;line-height:1;">{{ $heartRate }}</p>
            <p style="color:rgba(255,255,255,0.25);font-size:11px;margin-top:2px;">BPM</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ min(100, ($heartRate / 130) * 100) }}%;background:linear-gradient(90deg,#ff5252,#ff1744);"></div>
            </div>
        </div>

        {{-- SpO2 --}}
        <div class="sensor-card">
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                <span style="font-size:18px;">💨</span>
                <span style="color:rgba(255,255,255,0.4);font-size:11px;font-weight:600;letter-spacing:0.5px;">BLOOD O2</span>
            </div>
            <p style="font-size:30px;font-weight:900;color:#00bcd4;line-height:1;">{{ $spo2 }}</p>
            <p style="color:rgba(255,255,255,0.25);font-size:11px;margin-top:2px;">SpO2 %</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $spo2 }}%;background:linear-gradient(90deg,#00bcd4,#00e5ff);"></div>
            </div>
        </div>

        {{-- ECG --}}
        <div class="sensor-card">
            <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                <span style="font-size:18px;">🧠</span>
                <span style="color:rgba(255,255,255,0.4);font-size:11px;font-weight:600;letter-spacing:0.5px;">EEG/ECG</span>
            </div>
            <p style="font-size:30px;font-weight:900;color:#7c4dff;line-height:1;">{{ $focusScore }}</p>
            <p style="color:rgba(255,255,255,0.25);font-size:11px;margin-top:2px;">Signal</p>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $focusScore }}%;background:linear-gradient(90deg,#7c4dff,#00bcd4);"></div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════ --}}
    {{-- SESSION STATS --}}
    {{-- ═══════════════════════════════ --}}
    <p class="section-title" style="padding:0 4px;">Session Stats</p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">

        <div class="glass-card" style="padding:16px;">
            <p style="color:rgba(255,255,255,0.35);font-size:11px;font-weight:600;letter-spacing:0.5px;margin-bottom:8px;">⏱ SESSION TIME</p>
            <p style="font-size:26px;font-weight:900;color:white;font-variant-numeric:tabular-nums;">
                {{ gmdate('i:s', $sessionTime) }}
            </p>
        </div>

        <div class="glass-card" style="padding:16px;">
            <p style="color:rgba(255,255,255,0.35);font-size:11px;font-weight:600;letter-spacing:0.5px;margin-bottom:8px;">🎯 EFFICIENCY</p>
            <p style="font-size:26px;font-weight:900;
                color:{{ $efficiencyPct > 70 ? '#00e676' : ($efficiencyPct > 40 ? '#ffeb3b' : '#ff5252') }};">
                {{ $efficiencyPct }}%
            </p>
        </div>

        <div class="glass-card" style="padding:16px;">
            <p style="color:rgba(255,255,255,0.35);font-size:11px;font-weight:600;letter-spacing:0.5px;margin-bottom:8px;">✅ FOCUSED</p>
            <p style="font-size:26px;font-weight:900;color:#00e676;font-variant-numeric:tabular-nums;">
                {{ gmdate('i:s', $focusedTime) }}
            </p>
        </div>

        <div class="glass-card" style="padding:16px;">
            <p style="color:rgba(255,255,255,0.35);font-size:11px;font-weight:600;letter-spacing:0.5px;margin-bottom:8px;">⚠️ DISTRACTED</p>
            <p style="font-size:26px;font-weight:900;color:#ff5252;font-variant-numeric:tabular-nums;">
                {{ gmdate('i:s', $distractedTime) }}
            </p>
        </div>
    </div>

    {{-- ═══════════════════════════════ --}}
    {{-- HEART RATE CHART --}}
    {{-- ═══════════════════════════════ --}}
    <div class="glass-card" style="padding:20px;margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <p class="section-title" style="margin:0;">📈 Live Heart Rate</p>
            <div style="display:flex;align-items:center;gap:5px;">
                <div style="width:6px;height:6px;border-radius:50%;background:#ff5252;animation:pulseDot 1s infinite;"></div>
                <span style="color:#ff5252;font-size:11px;font-weight:600;">{{ $heartRate }} BPM</span>
            </div>
        </div>
        <canvas id="hrChart" height="70"></canvas>
    </div>

    {{-- ═══════════════════════════════ --}}
    {{-- DISTRACTION LOG --}}
    {{-- ═══════════════════════════════ --}}
    <div class="glass-card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <p class="section-title" style="margin:0;">📋 Distraction Log</p>
            <span style="background:rgba(255,152,0,0.1);border:1px solid rgba(255,152,0,0.3);color:#ff9800;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">
                {{ count($recentLogs) }} today
            </span>
        </div>

        @forelse($recentLogs as $log)
        <div class="log-item">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,152,0,0.1);border:1px solid rgba(255,152,0,0.2);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;">
                    ⚠️
                </div>
                <div>
                    <p style="color:white;font-size:13px;font-weight:600;">{{ $log->trigger_type }}</p>
                    @if($log->app_name)
                    <p style="color:rgba(255,255,255,0.3);font-size:11px;">{{ $log->app_name }}</p>
                    @endif
                </div>
            </div>
            <div style="text-align:right;">
                <p style="color:#ff9800;font-size:11px;font-weight:600;">{{ $log->heart_rate_at_trigger }} bpm</p>
                <p style="color:rgba(255,255,255,0.2);font-size:10px;">{{ $log->detected_at->diffForHumans() }}</p>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px 0;">
            <p style="font-size:32px;margin-bottom:8px;">🎉</p>
            <p style="color:rgba(255,255,255,0.3);font-size:13px;">No distractions yet!</p>
            <p style="color:rgba(255,255,255,0.15);font-size:11px;margin-top:4px;">Keep up the great work</p>
        </div>
        @endforelse
    </div>

</div>

{{-- Chart.js --}}
<script>
const ctx = document.getElementById('hrChart');
if (ctx) {
    const hrChart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: Array(30).fill(''),
            datasets: [{
                data: Array.from({length:30}, () => Math.floor(Math.random()*25)+60),
                borderColor: '#ff5252',
                backgroundColor: (context) => {
                    const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 80);
                    gradient.addColorStop(0, 'rgba(255,82,82,0.3)');
                    gradient.addColorStop(1, 'rgba(255,82,82,0)');
                    return gradient;
                },
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
            }]
        },
        options: {
            responsive: true,
            animation: { duration: 300 },
            plugins: { legend: { display: false } },
            scales: {
                x: { display: false },
                y: {
                    min: 50, max: 120,
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: 'rgba(255,255,255,0.25)', font: { size: 10 }, stepSize: 20 },
                    border: { display: false }
                }
            }
        }
    });

    // Update every 2 seconds
    setInterval(() => {
        const newVal = Math.floor(Math.random() * 25) + 60;
        hrChart.data.datasets[0].data.push(newVal);
        hrChart.data.datasets[0].data.shift();
        hrChart.data.labels.push('');
        hrChart.data.labels.shift();
        hrChart.update('none');
    }, 2000);
}
</script>