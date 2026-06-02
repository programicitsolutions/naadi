{{-- PATH: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeuroFocus — नाडी</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: #030712;
            background-image:
                radial-gradient(ellipse at 10% 10%, rgba(124,77,255,0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 90% 90%, rgba(0,188,212,0.08) 0%, transparent 50%);
            min-height: 100vh;
        }
        .glass {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.07);
        }
        .glass-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            padding: 18px;
            transition: all 0.3s;
        }
        .glass-card:hover {
            border-color: rgba(124,77,255,0.3);
            background: rgba(124,77,255,0.04);
        }
        .nav-link {
            color: rgba(255,255,255,0.4);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(124,77,255,0.2);
        }
        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 8px 16px;
            border-radius: 12px;
            transition: all 0.3s;
            text-decoration: none;
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 600;
        }
        .bottom-nav-item.active, .bottom-nav-item:hover {
            color: #7c4dff;
            background: rgba(124,77,255,0.1);
        }
        .gradient-text {
            background: linear-gradient(135deg, #7c4dff, #00bcd4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .pulse-dot {
            width: 8px; height: 8px;
            background: #00e676;
            border-radius: 50%;
            animation: pulseDot 2s infinite;
        }
        @keyframes pulseDot {
            0%,100% { box-shadow: 0 0 0 0 rgba(0,230,118,0.4); }
            50% { box-shadow: 0 0 0 6px rgba(0,230,118,0); }
        }
        @keyframes shake {
            0%,100% { transform: rotate(0deg); }
            25% { transform: rotate(-15deg); }
            75% { transform: rotate(15deg); }
        }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(124,77,255,0.3); border-radius: 2px; }
    </style>
    @livewireStyles
</head>
<body>

    {{-- TOP NAVIGATION --}}
    <nav style="background:rgba(3,7,18,0.8);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.06);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;">

        {{-- Logo --}}
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#7c4dff,#00bcd4);display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-brain" style="color:white;font-size:16px;"></i>
            </div>
            <div>
                <span class="gradient-text" style="font-size:16px;font-weight:900;">NeuroFocus</span>
                <span style="color:rgba(255,255,255,0.2);font-size:10px;margin-left:6px;letter-spacing:1px;">नाडी</span>
            </div>
            <div style="display:flex;align-items:center;gap:5px;background:rgba(0,230,118,0.1);border:1px solid rgba(0,230,118,0.2);border-radius:20px;padding:3px 10px;">
                <div class="pulse-dot"></div>
                <span style="color:#00e676;font-size:10px;font-weight:600;">LIVE</span>
            </div>
        </div>

        {{-- Nav links (desktop) --}}
        <div style="display:flex;gap:4px;" class="hidden md:flex">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i> Reports
            </a>
            <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> Settings
            </a>
        </div>

        {{-- User + Logout --}}
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="text-align:right;" class="hidden md:block">
                <p style="color:white;font-size:12px;font-weight:600;">
                    <i class="fa-solid fa-user" style="color:#7c4dff;"></i>
                    {{ Auth::user()->name }}
                </p>
                <p style="color:rgba(255,255,255,0.3);font-size:10px;">{{ Auth::user()->mode }} mode</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    style="background:rgba(255,82,82,0.1);border:1px solid rgba(255,82,82,0.2);color:#ff5252;padding:7px 14px;border-radius:10px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.3s;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main style="max-width:680px;margin:0 auto;padding-bottom:80px;">
        {{ $slot }}
    </main>

    {{-- BOTTOM NAV (Mobile) --}}
    <nav style="position:fixed;bottom:0;left:0;right:0;background:rgba(3,7,18,0.9);backdrop-filter:blur(20px);border-top:1px solid rgba(255,255,255,0.06);display:flex;justify-content:space-around;padding:8px 16px;z-index:50;" class="md:hidden">
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house" style="font-size:20px;"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('reports') }}" class="bottom-nav-item {{ request()->routeIs('reports') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-bar" style="font-size:20px;"></i>
            <span>Reports</span>
        </a>
        <a href="{{ route('settings') }}" class="bottom-nav-item {{ request()->routeIs('settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear" style="font-size:20px;"></i>
            <span>Settings</span>
        </a>
    </nav>

    @livewireScripts
</body>
</html>