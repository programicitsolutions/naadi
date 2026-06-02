{{-- PATH: resources/views/splash.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroFocus नाडी</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #030712;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background animated gradient */
        .bg-glow {
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 20%, rgba(124,77,255,0.25) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 80%, rgba(0,188,212,0.2) 0%, transparent 60%),
                radial-gradient(ellipse at 50% 50%, rgba(0,230,118,0.05) 0%, transparent 70%);
            animation: bgPulse 4s ease-in-out infinite;
        }
        @keyframes bgPulse {
            0%,100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        /* Floating particles */
        .particle {
            position: fixed;
            border-radius: 50%;
            opacity: 0;
            animation: floatUp 6s ease-in-out infinite;
        }
        @keyframes floatUp {
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            10% { opacity: 0.6; }
            90% { opacity: 0.2; }
            100% { opacity: 0; transform: translateY(-100px) scale(1); }
        }

        /* Main splash container */
        .splash-container {
            position: relative;
            z-index: 10;
            text-align: center;
            animation: splashFadeIn 1s ease-out forwards;
        }
        @keyframes splashFadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Logo ring */
        .logo-ring {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(124,77,255,0.3), rgba(0,188,212,0.3));
            border: 2px solid rgba(124,77,255,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            position: relative;
            animation: logoGlow 2s ease-in-out infinite;
        }
        @keyframes logoGlow {
            0%,100% { box-shadow: 0 0 20px rgba(124,77,255,0.3), 0 0 60px rgba(124,77,255,0.1); }
            50% { box-shadow: 0 0 40px rgba(124,77,255,0.6), 0 0 100px rgba(0,188,212,0.2); }
        }

        /* Rotating outer ring */
        .logo-ring::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: #7c4dff;
            border-right-color: #00bcd4;
            animation: spin 3s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Brain icon */
        .brain-icon {
            font-size: 48px;
            background: linear-gradient(135deg, #7c4dff, #00bcd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: iconPulse 2s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%,100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Brand name */
        .brand-name {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(135deg, #fff 0%, #7c4dff 50%, #00bcd4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
            animation: textShimmer 3s ease-in-out infinite;
        }
        @keyframes textShimmer {
            0%,100% { filter: brightness(1); }
            50% { filter: brightness(1.3); }
        }

        /* Sanskrit name */
        .sanskrit-name {
            font-size: 22px;
            color: rgba(124,77,255,0.8);
            letter-spacing: 6px;
            margin-top: 4px;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        /* Tagline */
        .tagline {
            font-size: 13px;
            color: rgba(255,255,255,0.3);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 8px;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ECG line animation */
        .ecg-container {
            width: 280px;
            height: 50px;
            margin: 32px auto;
            overflow: hidden;
            animation: fadeInUp 1s ease-out 1s both;
        }
        .ecg-line {
            animation: ecgMove 2s linear infinite;
        }
        @keyframes ecgMove {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* Sensor icons row */
        .sensor-row {
            display: flex;
            justify-content: center;
            gap: 28px;
            margin: 24px 0;
            animation: fadeInUp 1s ease-out 1.2s both;
        }
        .sensor-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .sensor-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.04);
            transition: all 0.3s;
        }
        .sensor-label {
            font-size: 9px;
            color: rgba(255,255,255,0.25);
            letter-spacing: 0.5px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Loading bar */
        .loading-container {
            width: 200px;
            margin: 28px auto 0;
            animation: fadeInUp 1s ease-out 1.4s both;
        }
        .loading-track {
            height: 2px;
            background: rgba(255,255,255,0.08);
            border-radius: 1px;
            overflow: hidden;
        }
        .loading-fill {
            height: 100%;
            background: linear-gradient(90deg, #7c4dff, #00bcd4);
            border-radius: 1px;
            animation: loadingFill 3s ease-in-out forwards;
        }
        @keyframes loadingFill {
            0% { width: 0%; }
            100% { width: 100%; }
        }
        .loading-text {
            font-size: 10px;
            color: rgba(255,255,255,0.2);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 10px;
            animation: loadingDots 1.5s ease-in-out infinite;
        }
        @keyframes loadingDots {
            0%,100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        /* Live badge */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0,230,118,0.1);
            border: 1px solid rgba(0,230,118,0.2);
            border-radius: 20px;
            padding: 5px 14px;
            margin-top: 16px;
            animation: fadeInUp 1s ease-out 1.6s both;
        }
        .live-dot {
            width: 6px;
            height: 6px;
            background: #00e676;
            border-radius: 50%;
            animation: livePulse 1s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(0,230,118,0.4); }
            50% { box-shadow: 0 0 0 4px rgba(0,230,118,0); }
        }

        /* Audio button */
        .audio-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(124,77,255,0.2);
            border: 1px solid rgba(124,77,255,0.4);
            color: #7c4dff;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 100;
        }
        .audio-btn:hover {
            background: rgba(124,77,255,0.4);
            transform: scale(1.1);
        }

        /* Skip button */
        .skip-btn {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.3);
            padding: 8px 24px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            letter-spacing: 1px;
            transition: all 0.3s;
            z-index: 100;
            font-family: 'Inter', sans-serif;
        }
        .skip-btn:hover {
            color: white;
            border-color: rgba(255,255,255,0.3);
        }

        /* Made in India */
        .made-in {
            position: fixed;
            bottom: 30px;
            left: 30px;
            font-size: 11px;
            color: rgba(255,255,255,0.15);
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    {{-- Background glow --}}
    <div class="bg-glow"></div>

    {{-- Floating particles --}}
    <div class="particle" style="width:6px;height:6px;background:#7c4dff;left:15%;animation-delay:0s;animation-duration:7s;"></div>
    <div class="particle" style="width:4px;height:4px;background:#00bcd4;left:30%;animation-delay:1s;animation-duration:8s;"></div>
    <div class="particle" style="width:8px;height:8px;background:#00e676;left:50%;animation-delay:2s;animation-duration:6s;"></div>
    <div class="particle" style="width:4px;height:4px;background:#7c4dff;left:70%;animation-delay:0.5s;animation-duration:9s;"></div>
    <div class="particle" style="width:6px;height:6px;background:#00bcd4;left:85%;animation-delay:1.5s;animation-duration:7s;"></div>
    <div class="particle" style="width:3px;height:3px;background:#fff;left:25%;animation-delay:3s;animation-duration:8s;"></div>
    <div class="particle" style="width:5px;height:5px;background:#7c4dff;left:60%;animation-delay:2.5s;animation-duration:6s;"></div>

    {{-- Main splash --}}
    <div class="splash-container">

        {{-- Logo ring --}}
        <div class="logo-ring">
            <i class="fa-solid fa-brain brain-icon"></i>
        </div>

        {{-- Brand name --}}
        <h1 class="brand-name">NeuroFocus</h1>
        <p class="sanskrit-name">न ा ड ी</p>
        <p class="tagline">AI Wellness Monitor</p>

        {{-- ECG Animation --}}
        <div class="ecg-container">
            <svg width="560" height="50" viewBox="0 0 560 50" class="ecg-line">
                <polyline
                    points="0,25 20,25 30,25 35,5 40,45 45,25 55,25 75,25 80,25 85,10 90,40 95,25 105,25 125,25 130,25 135,5 140,45 145,25 155,25 175,25 180,25 185,10 190,40 195,25 205,25 225,25 230,25 235,5 240,45 245,25 255,25 275,25 280,25 285,10 290,40 295,25 305,25 325,25 330,25 335,5 340,45 345,25 355,25 375,25 380,25 385,10 390,40 395,25 405,25 425,25 430,25 435,5 440,45 445,25 455,25 475,25 480,25 485,10 490,40 495,25 505,25 525,25 530,25 535,5 540,45 545,25 555,25"
                    fill="none"
                    stroke="url(#ecgGrad)"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
                <defs>
                    <linearGradient id="ecgGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#7c4dff;stop-opacity:0"/>
                        <stop offset="30%" style="stop-color:#7c4dff;stop-opacity:1"/>
                        <stop offset="70%" style="stop-color:#00bcd4;stop-opacity:1"/>
                        <stop offset="100%" style="stop-color:#00bcd4;stop-opacity:0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        {{-- Sensor icons --}}
        <div class="sensor-row">
            <div class="sensor-item">
                <div class="sensor-icon" style="color:#7c4dff;border-color:rgba(124,77,255,0.3);">
                    <i class="fa-solid fa-head-side-brain"></i>
                </div>
                <span class="sensor-label">EEG</span>
            </div>
            <div class="sensor-item">
                <div class="sensor-icon" style="color:#ff5252;border-color:rgba(255,82,82,0.3);">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>
                <span class="sensor-label">Heart</span>
            </div>
            <div class="sensor-item">
                <div class="sensor-icon" style="color:#00bcd4;border-color:rgba(0,188,212,0.3);">
                    <i class="fa-solid fa-lungs"></i>
                </div>
                <span class="sensor-label">SpO2</span>
            </div>
            <div class="sensor-item">
                <div class="sensor-icon" style="color:#ff9800;border-color:rgba(255,152,0,0.3);">
                    <i class="fa-solid fa-wave-square"></i>
                </div>
                <span class="sensor-label">Pulse</span>
            </div>
            <div class="sensor-item">
                <div class="sensor-icon" style="color:#00e676;border-color:rgba(0,230,118,0.3);">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <span class="sensor-label">ESP32</span>
            </div>
        </div>

        {{-- Live badge --}}
        <div class="live-badge">
            <div class="live-dot"></div>
            <span style="color:#00e676;font-size:11px;font-weight:700;letter-spacing:1px;">
                INITIALIZING SENSORS
            </span>
        </div>

        {{-- Loading bar --}}
        <div class="loading-container">
            <div class="loading-track">
                <div class="loading-fill" id="loadingFill"></div>
            </div>
            <p class="loading-text" id="loadingText">Connecting to device...</p>
        </div>

    </div>

    {{-- Audio button --}}
    <button class="audio-btn" id="audioBtn" onclick="toggleAudio()">
        <i class="fa-solid fa-volume-xmark" id="audioIcon"></i>
    </button>

    {{-- Skip button --}}
    <button class="skip-btn" onclick="goToDashboard()">
        SKIP &nbsp;<i class="fa-solid fa-arrow-right"></i>
    </button>

    {{-- Made in India --}}
    <p class="made-in">
        <i class="fa-solid fa-location-dot"></i>
        Raichur, India 🇮🇳
    </p>

    {{-- Audio element - replace src with your audio file --}}
    <audio id="splashAudio" loop>
        {{-- Add your audio file here --}}
        {{-- <source src="{{ asset('audio/splash.mp3') }}" type="audio/mpeg"> --}}
    </audio>

    <script>
        // Loading messages sequence
        const messages = [
            "Connecting to device...",
            "Reading EEG signals...",
            "Calibrating heart rate...",
            "Syncing with cloud...",
            "Loading dashboard...",
        ];
        let msgIndex = 0;
        const loadingText = document.getElementById('loadingText');

        const msgInterval = setInterval(() => {
            msgIndex++;
            if (msgIndex < messages.length) {
                loadingText.textContent = messages[msgIndex];
            }
        }, 600);

        // Auto redirect to dashboard after 3 seconds
        setTimeout(() => {
            clearInterval(msgInterval);
            goToDashboard();
        }, 3500);

        // Go to dashboard
        function goToDashboard() {
            document.querySelector('.splash-container').style.animation =
                'splashFadeOut 0.5s ease-in forwards';

            // Add fade out animation
            document.body.style.transition = 'opacity 0.5s';
            document.body.style.opacity = '0';

            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 500);
        }

        // Audio toggle
        let audioPlaying = false;
        const audio = document.getElementById('splashAudio');
        const audioIcon = document.getElementById('audioIcon');

        function toggleAudio() {
            if (audioPlaying) {
                audio.pause();
                audioIcon.className = 'fa-solid fa-volume-xmark';
                audioPlaying = false;
            } else {
                audio.play().catch(() => {
                    console.log('Audio autoplay blocked by browser');
                });
                audioIcon.className = 'fa-solid fa-volume-high';
                audioPlaying = true;
            }
        }

        // Try autoplay on load
        window.addEventListener('load', () => {
            audio.play().then(() => {
                audioIcon.className = 'fa-solid fa-volume-high';
                audioPlaying = true;
            }).catch(() => {
                // Autoplay blocked — user must click
                audioIcon.className = 'fa-solid fa-volume-xmark';
            });
        });
    </script>

</body>
</html>