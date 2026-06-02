{{-- PATH: resources/views/livewire/healing-tracker.blade.php --}}

<div wire:poll.5000ms="loadData"
     style="padding:20px;padding-bottom:100px;">

    {{-- ═══ HEADER ═══ --}}
    <div style="margin-bottom:24px;">
        <h1 style="font-size:22px;font-weight:900;color:#fff;">
            💙 Healing Tracker
        </h1>
        <p style="color:rgba(255,255,255,0.4);font-size:13px;">
            Day {{ $dayCount }} of your journey
        </p>
    </div>

    {{-- ═══ CURRENT MOOD CARD ═══ --}}
    <div style="background:rgba(255,255,255,0.03);
                border:1px solid rgba(255,255,255,0.07);
                border-radius:20px;padding:24px;
                margin-bottom:16px;text-align:center;
                border-left:4px solid {{ $currentColor }};">

        <div style="font-size:56px;margin-bottom:8px;">
            {{ $currentEmoji }}
        </div>

        <p style="font-size:22px;font-weight:900;
                  color:{{ $currentColor }};margin-bottom:4px;">
            {{ $currentMood }}
        </p>

        <p style="color:rgba(255,255,255,0.4);font-size:12px;">
            Heart: {{ $heartRate }} BPM &nbsp;|&nbsp;
            Focus: {{ $focusScore }}/100
        </p>

        {{-- Mood bar --}}
        <div style="background:rgba(255,255,255,0.08);
                    border-radius:10px;height:8px;
                    margin-top:16px;overflow:hidden;">
            <div style="height:100%;border-radius:10px;
                        background:{{ $currentColor }};
                        width:{{ $focusScore }}%;
                        transition:width 1s ease;">
            </div>
        </div>
        <div style="display:flex;justify-content:space-between;
                    margin-top:6px;">
            <span style="color:rgba(255,255,255,0.2);font-size:10px;">
                😤 Stressed
            </span>
            <span style="color:rgba(255,255,255,0.2);font-size:10px;">
                😊 Calm
            </span>
        </div>
    </div>

    {{-- ═══ TODAY'S MESSAGE ═══ --}}
    <div style="background:linear-gradient(135deg,
                rgba(124,77,255,0.1),rgba(0,188,212,0.1));
                border:1px solid rgba(124,77,255,0.2);
                border-radius:16px;padding:16px;
                margin-bottom:16px;">
        <p style="color:#7c4dff;font-size:11px;
                  font-weight:700;letter-spacing:1px;
                  margin-bottom:6px;">
            💬 TODAY'S MESSAGE
        </p>
        <p style="color:#fff;font-size:14px;
                  line-height:1.6;font-style:italic;">
            "{{ $todayMessage }}"
        </p>
    </div>

    {{-- ═══ TODAY'S STATS ═══ --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;
                gap:10px;margin-bottom:16px;">

        {{-- Today spikes --}}
        <div style="background:rgba(255,255,255,0.03);
                    border:1px solid rgba(255,255,255,0.07);
                    border-radius:16px;padding:14px;
                    text-align:center;">
            <p style="color:rgba(255,255,255,0.4);
                      font-size:10px;margin-bottom:6px;">
                TODAY
            </p>
            <p style="font-size:28px;font-weight:900;
                      color:{{ $todaySpikes <= 10 ? '#00e676' : ($todaySpikes <= 25 ? '#ffeb3b' : '#ff5252') }};">
                {{ $todaySpikes }}
            </p>
            <p style="color:rgba(255,255,255,0.3);font-size:10px;">
                spikes
            </p>
        </div>

        {{-- Recovery --}}
        <div style="background:rgba(255,255,255,0.03);
                    border:1px solid rgba(124,77,255,0.3);
                    border-radius:16px;padding:14px;
                    text-align:center;">
            <p style="color:rgba(255,255,255,0.4);
                      font-size:10px;margin-bottom:6px;">
                HEALING
            </p>
            <p style="font-size:28px;font-weight:900;
                      color:#7c4dff;">
                {{ $recoveryPercent }}%
            </p>
            <p style="color:rgba(255,255,255,0.3);font-size:10px;">
                recovery
            </p>
        </div>

        {{-- Best hour --}}
        <div style="background:rgba(255,255,255,0.03);
                    border:1px solid rgba(255,255,255,0.07);
                    border-radius:16px;padding:14px;
                    text-align:center;">
            <p style="color:rgba(255,255,255,0.4);
                      font-size:10px;margin-bottom:6px;">
                PEAK TIME
            </p>
            <p style="font-size:22px;font-weight:900;
                      color:#00bcd4;">
                {{ $peakHour }}
            </p>
            <p style="color:rgba(255,255,255,0.3);font-size:10px;">
                focus hour
            </p>
        </div>
    </div>

    {{-- ═══ WEEKLY HEALING GRAPH ═══ --}}
    <div style="background:rgba(255,255,255,0.03);
                border:1px solid rgba(255,255,255,0.07);
                border-radius:20px;padding:20px;
                margin-bottom:16px;">

        <p style="color:rgba(255,255,255,0.4);font-size:11px;
                  font-weight:700;letter-spacing:1px;
                  margin-bottom:16px;">
            📅 THIS WEEK — HOW YOU FELT
        </p>

        <div style="display:flex;gap:6px;align-items:flex-end;
                    height:80px;margin-bottom:12px;">
            @foreach($weekSpikes as $day)
            @php
                $maxSpikes = collect($weekSpikes)->max('spikes');
                $height    = $maxSpikes > 0
                    ? max(10, ($day['spikes'] / $maxSpikes) * 70)
                    : 10;
            @endphp
            <div style="flex:1;display:flex;
                        flex-direction:column;
                        align-items:center;gap:4px;">
                <span style="font-size:16px;">
                    {{ $day['emoji'] }}
                </span>
                <div style="width:100%;
                            height:{{ $height }}px;
                            background:{{ $day['color'] }};
                            border-radius:6px;
                            opacity:0.8;
                            transition:height 0.5s;">
                </div>
            </div>
            @endforeach
        </div>

        {{-- Day labels --}}
        <div style="display:flex;gap:6px;">
            @foreach($weekSpikes as $day)
            <div style="flex:1;text-align:center;">
                <p style="color:rgba(255,255,255,0.4);
                          font-size:10px;font-weight:600;">
                    {{ $day['day'] }}
                </p>
                <p style="font-size:9px;
                          color:{{ $day['color'] }};">
                    {{ $day['label'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══ WHAT THESE MEAN ═══ --}}
    <div style="background:rgba(255,255,255,0.03);
                border:1px solid rgba(255,255,255,0.07);
                border-radius:20px;padding:20px;
                margin-bottom:16px;">

        <p style="color:rgba(255,255,255,0.4);font-size:11px;
                  font-weight:700;letter-spacing:1px;
                  margin-bottom:14px;">
            💡 WHAT DO THESE MEAN?
        </p>

        @foreach([
            ['🌟', '#00e676', 'Perfect day',   '0 spikes',   'Your mind was fully at peace'],
            ['😊', '#00e676', 'Great day',      '1-5 spikes', 'Very few difficult moments'],
            ['😐', '#ffeb3b', 'Okay day',       '6-15 spikes','Some hard moments but managed'],
            ['😰', '#ff9800', 'Hard day',       '16-30 spikes','Mind was restless today'],
            ['😤', '#ff5252', 'Tough day',      '30+ spikes', 'Very difficult but you survived'],
        ] as [$emoji, $color, $label, $count, $desc])
        <div style="display:flex;align-items:center;
                    gap:12px;padding:8px 0;
                    border-bottom:1px solid rgba(255,255,255,0.05);">
            <span style="font-size:24px;width:30px;">
                {{ $emoji }}
            </span>
            <div style="flex:1;">
                <p style="color:{{ $color }};font-size:13px;
                          font-weight:700;">
                    {{ $label }}
                    <span style="color:rgba(255,255,255,0.3);
                                 font-size:11px;font-weight:400;">
                        ({{ $count }})
                    </span>
                </p>
                <p style="color:rgba(255,255,255,0.3);
                          font-size:11px;">
                    {{ $desc }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ═══ HEALING PROGRESS ═══ --}}
    <div style="background:linear-gradient(135deg,
                rgba(124,77,255,0.08),rgba(0,188,212,0.08));
                border:1px solid rgba(124,77,255,0.2);
                border-radius:20px;padding:20px;">

        <p style="color:rgba(255,255,255,0.4);font-size:11px;
                  font-weight:700;letter-spacing:1px;
                  margin-bottom:16px;">
            💙 YOUR HEALING JOURNEY
        </p>

        {{-- Progress ring --}}
        <div style="text-align:center;margin-bottom:16px;">
            <svg width="140" height="140" viewBox="0 0 140 140">
                <circle cx="70" cy="70" r="54"
                    fill="none"
                    stroke="rgba(255,255,255,0.08)"
                    stroke-width="10"/>
                <circle cx="70" cy="70" r="54"
                    fill="none"
                    stroke="#7c4dff"
                    stroke-width="10"
                    stroke-linecap="round"
                    stroke-dasharray="{{ ($recoveryPercent / 100) * 339 }} 339"
                    transform="rotate(-90 70 70)"
                    style="transition:stroke-dasharray 1s ease;
                           filter:drop-shadow(0 0 8px #7c4dff);"/>
                <text x="70" y="62" text-anchor="middle"
                    fill="white" font-size="24"
                    font-weight="900">
                    {{ $recoveryPercent }}%
                </text>
                <text x="70" y="80" text-anchor="middle"
                    fill="rgba(255,255,255,0.4)"
                    font-size="11">
                    healed
                </text>
                <text x="70" y="96" text-anchor="middle"
                    fill="#7c4dff" font-size="11">
                    Day {{ $dayCount }}
                </text>
            </svg>
        </div>

        {{-- Healing message --}}
        <div style="text-align:center;">
            @if($recoveryPercent >= 75)
                <p style="color:#00e676;font-size:15px;font-weight:700;">
                    🏆 Almost there! You're doing amazing!
                </p>
            @elseif($recoveryPercent >= 50)
                <p style="color:#7c4dff;font-size:15px;font-weight:700;">
                    💪 Halfway through! Keep going!
                </p>
            @elseif($recoveryPercent >= 25)
                <p style="color:#ffeb3b;font-size:15px;font-weight:700;">
                    😊 Making progress! Day by day!
                </p>
            @else
                <p style="color:#00bcd4;font-size:15px;font-weight:700;">
                    🌱 Every journey starts with one step!
                </p>
            @endif

            <p style="color:rgba(255,255,255,0.3);
                      font-size:12px;margin-top:8px;">
                Best focus day: {{ $bestDay }} &nbsp;|&nbsp;
                Peak hour: {{ $peakHour }}
            </p>
        </div>
    </div>

</div>