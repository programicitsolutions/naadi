{{-- PATH: resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — NeuroFocus नाडी</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: #030712;
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(124,77,255,0.2) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(0,188,212,0.15) 0%, transparent 50%);
            min-height: 100vh;
        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .input-field {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 14px;
            transition: all 0.3s;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.25); }
        .input-field:focus {
            outline: none;
            border-color: #7c4dff;
            background: rgba(124,77,255,0.08);
            box-shadow: 0 0 0 3px rgba(124,77,255,0.15);
        }
        .btn-primary {
            background: linear-gradient(135deg, #7c4dff 0%, #00bcd4 100%);
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 14px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(124,77,255,0.4);
        }
        .mode-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 8px;
        }
        .mode-card:hover {
            border-color: rgba(124,77,255,0.4);
            background: rgba(124,77,255,0.05);
        }
        .logo-ring {
            width: 70px; height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(124,77,255,0.3), rgba(0,188,212,0.3));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            border: 1px solid rgba(124,77,255,0.4);
            animation: glow 3s ease-in-out infinite;
        }
        @keyframes glow {
            0%,100% { box-shadow: 0 0 20px rgba(124,77,255,0.3); }
            50% { box-shadow: 0 0 40px rgba(124,77,255,0.6); }
        }
        .floating-dot {
            position: fixed;
            border-radius: 50%;
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }
    </style>
</head>
<body>
    <div class="floating-dot" style="width:300px;height:300px;background:#7c4dff;top:-100px;left:-100px;"></div>
    <div class="floating-dot" style="width:200px;height:200px;background:#00bcd4;bottom:-50px;right:-50px;animation-delay:3s;"></div>

    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        <div class="w-full max-w-sm">

            {{-- Logo --}}
            <div class="text-center mb-6">
                <div class="logo-ring">
                    <span style="font-size:32px;">🧠</span>
                </div>
                <h1 style="font-size:26px;font-weight:900;background:linear-gradient(135deg,#7c4dff,#00bcd4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    NeuroFocus
                </h1>
                <p style="color:rgba(255,255,255,0.3);font-size:12px;margin-top:4px;letter-spacing:2px;">
                    नाडी • AI WELLNESS
                </p>
            </div>

            {{-- Card --}}
            <div class="glass rounded-3xl p-7">

                <h2 style="font-size:20px;font-weight:800;color:white;margin-bottom:4px;">
                    Create Account 🚀
                </h2>
                <p style="color:rgba(255,255,255,0.3);font-size:12px;margin-bottom:24px;">
                    Start your wellness journey today
                </p>

                @if($errors->any())
                <div style="background:rgba(255,82,82,0.1);border:1px solid rgba(255,82,82,0.3);border-radius:12px;padding:12px;margin-bottom:20px;">
                    @foreach($errors->all() as $error)
                        <p style="color:#ff5252;font-size:12px;">⚠️ {{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Name + Age row --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
                        <div>
                            <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:6px;">NAME</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="input-field" placeholder="Amith" required />
                        </div>
                        <div>
                            <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:6px;">AGE</label>
                            <input type="number" name="age" value="{{ old('age') }}"
                                class="input-field" placeholder="25" min="5" max="100" />
                        </div>
                    </div>

                    {{-- Email --}}
                    <div style="margin-bottom:14px;">
                        <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:6px;">EMAIL</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="input-field" placeholder="your@email.com" required />
                    </div>

                    {{-- Password row --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                        <div>
                            <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:6px;">PASSWORD</label>
                            <input type="password" name="password"
                                class="input-field" placeholder="••••••" required />
                        </div>
                        <div>
                            <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:6px;">CONFIRM</label>
                            <input type="password" name="password_confirmation"
                                class="input-field" placeholder="••••••" required />
                        </div>
                    </div>

                    {{-- Mode Selection --}}
                    <div style="margin-bottom:24px;">
                        <label style="color:rgba(255,255,255,0.4);font-size:10px;font-weight:600;letter-spacing:1px;display:block;margin-bottom:10px;">
                            CHOOSE YOUR MODE
                        </label>
                        @foreach([
                            'focus'      => ['🎯', 'Focus Mode', 'Max productivity'],
                            'moving_on'  => ['💔', 'Moving On', 'Healing journey'],
                            'meditation' => ['🧘', 'Meditation', 'Inner calm'],
                            'sleep'      => ['😴', 'Sleep', 'Night recovery'],
                        ] as $val => [$icon, $label, $desc])
                        <label class="mode-card" style="cursor:pointer;">
                            <input type="radio" name="mode" value="{{ $val }}"
                                {{ old('mode', 'focus') === $val ? 'checked' : '' }}
                                style="accent-color:#7c4dff;" />
                            <span style="font-size:20px;">{{ $icon }}</span>
                            <div>
                                <p style="color:white;font-size:13px;font-weight:600;">{{ $label }}</p>
                                <p style="color:rgba(255,255,255,0.3);font-size:11px;">{{ $desc }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-primary">
                        Create Account →
                    </button>
                </form>

                <div style="text-align:center;margin-top:18px;">
                    <span style="color:rgba(255,255,255,0.25);font-size:13px;">Already have an account?</span>
                    <a href="{{ route('login') }}"
                       style="color:#7c4dff;font-weight:700;font-size:13px;text-decoration:none;margin-left:6px;">
                        Sign in
                    </a>
                </div>
            </div>

            <p style="text-align:center;color:rgba(255,255,255,0.1);font-size:11px;margin-top:20px;">
                🔒 End-to-end encrypted • Your data stays private
            </p>
        </div>
    </div>
</body>
</html>