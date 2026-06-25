<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-page:   #EEF4FB;
            --bg-card:   #FFFFFF;
            --bg-subtle: #F0F6FD;
            --border:    #C8DDF0;
            --text-1:    #0D1F36;
            --text-2:    #2C4A6E;
            --text-3:    #5A7FA8;
            --text-4:    #8EB3D4;
            --accent:    #004B8D;
            --accent-bg: #D6E8F8;
        }
        [data-theme="dark"] {
            --bg-page:   #1C2B40;
            --bg-card:   #243352;
            --bg-subtle: #2A3B58;
            --border:    #384F6E;
            --text-1:    #F0F6FF;
            --text-2:    #C8D8EE;
            --text-3:    #8AAAC8;
            --text-4:    #6090B4;
            --accent:    #4D9FFF;
            --accent-bg: rgba(77,159,255,0.20);
        }
        body { background: var(--bg-page) !important; color: var(--text-1) !important; margin: 0; font-family: sans-serif; }

        [style*="color: #111827"],[style*="color:#111827"] { color: var(--text-1) !important; }
        [style*="color: #374151"],[style*="color:#374151"] { color: var(--text-2) !important; }
        [style*="color: #6B7280"],[style*="color:#6B7280"] { color: var(--text-3) !important; }
        [style*="color: #9CA3AF"],[style*="color:#9CA3AF"] { color: var(--text-4) !important; }
        [style*="background: #fff"],[style*="background:#fff"],
        [style*="background: white"],[style*="background: #FFFFFF"] { background: var(--bg-card) !important; }
        [style*="background: #F3F4F6"],[style*="background:#F3F4F6"],
        [style*="background: #F9FAFB"],[style*="background:#F9FAFB"] { background: var(--bg-subtle) !important; }
        [style*="border: 1px solid #F3F4F6"],[style*="border:1px solid #F3F4F6"] { border-color: var(--border) !important; }
        [style*="border: 1px solid #E5E7EB"],[style*="border:1px solid #E5E7EB"] { border-color: var(--border) !important; }

        #theme-toggle {
            width: 34px; height: 34px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--bg-subtle);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--text-3);
        }
    </style>
    @php
        $schoolTheme = null; $accentBg = null;
        if (auth()->check()) {
            $rawColor = auth()->user()->school?->theme_color;
            if ($rawColor && preg_match('/^#[0-9A-Fa-f]{6}$/', $rawColor)) {
                $schoolTheme = $rawColor;
                $r = hexdec(substr($rawColor,1,2)); $g = hexdec(substr($rawColor,3,2)); $b = hexdec(substr($rawColor,5,2));
                $accentBg = sprintf('#%02x%02x%02x', round($r*.14+255*.86), round($g*.14+255*.86), round($b*.14+255*.86));
            }
        }
    @endphp
    @if($schoolTheme)
    <style>
        :root { --accent: {{ $schoolTheme }}; --accent-bg: {{ $accentBg }}; }
        [style*="background: #004B8D"] { background: {{ $schoolTheme }} !important; }
        [style*="color: #004B8D"]      { color: {{ $schoolTheme }} !important; }
    </style>
    @endif
    <script>
        (function() {
            const saved = localStorage.getItem('atrio-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if ((saved || (prefersDark ? 'dark' : 'light')) === 'dark')
                document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
</head>
<body class="min-h-screen">

{{-- TOPBAR --}}
<header style="background: var(--bg-card); border-bottom: 1px solid var(--border); height: 64px; display: flex; align-items: center; padding: 0 32px; position: sticky; top: 0; z-index: 40;">

    {{-- Logo + Escola --}}
    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
        @auth
        @php
            $school = auth()->user()->school;
            $roleDashboardMap = ['admin' => 'secretaria.dashboard', 'coordenador' => 'secretaria.dashboard', 'orientador' => 'secretaria.dashboard', 'professor' => 'professor.dashboard'];
            $hubRole = auth()->user()->getRoleNames()->first();
            if (isset($roleDashboardMap[$hubRole])) {
                $hubDashRoute = $roleDashboardMap[$hubRole];
            } elseif ($hubRole && str_starts_with($hubRole, 's')) {
                $hubDashRoute = 'secretaria.dashboard';
            } else {
                $hubDashRoute = 'secretaria.dashboard';
            }
        @endphp
        <a href="{{ route($hubDashRoute) }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
            @if($school?->logo)
                <img src="{{ route('school.logo', ['filename' => basename($school->logo)]) }}"
                     style="height: 36px; object-fit: contain;">
            @else
                <div style="width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                        <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                    </svg>
                </div>
            @endif
            <div>
                <div style="font-size: 14px; font-weight: 700; color: var(--text-1);">{{ $school?->name ?? 'Átrio' }}</div>
                <div style="font-size: 11px; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px;">Portal Institucional</div>
            </div>
        </a>
        @endauth
    </div>

    {{-- Direita: usuário + tema + sair --}}
    <div style="display: flex; align-items: center; gap: 16px;">
        @auth
        <div style="font-size: 13px; text-align: right;">
            <div style="font-weight: 600; color: var(--text-1);">{{ auth()->user()->name }}</div>
            <div style="color: var(--text-4); font-size: 11px;">
                @php
                    $rl = ['admin' => 'Administrador', 'coordenador' => 'Coordenação', 'orientador' => 'Orientação', 'professor' => 'Professor'];
                    $hubRoleLabel = $rl[$hubRole] ?? null;
                    if (!$hubRoleLabel && $hubRole && str_starts_with($hubRole, 's')) {
                        $hubRoleLabel = \App\Models\SchoolRole::where('spatie_role', $hubRole)->value('name');
                    }
                    echo $hubRoleLabel ?? $hubRole;
                @endphp
            </div>
        </div>
        @endauth

        <button id="theme-toggle" onclick="toggleTheme()" title="Alternar tema">
            <svg id="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                <circle cx="12" cy="12" r="5"/>
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
            <svg id="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
        </button>

        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: 1px solid var(--danger-border); background: transparent; color: var(--danger); font-size: 13px; font-weight: 600; cursor: pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Sair
            </button>
        </form>
        @endauth
    </div>
</header>

{{-- CONTEÚDO --}}
<main style="max-width: 960px; margin: 0 auto; padding: 48px 24px;">
    @if(session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    @yield('content')
</main>

<script>
const SCHOOL_ACCENT    = @json($schoolTheme ?? null);
const SCHOOL_ACCENT_BG = @json($accentBg ?? null);

function updateIcons(theme) {
    const sun = document.getElementById('icon-sun'), moon = document.getElementById('icon-moon');
    if (!sun || !moon) return;
    sun.style.display  = theme === 'dark' ? 'block' : 'none';
    moon.style.display = theme === 'dark' ? 'none'  : 'block';
}

function toggleTheme() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next = isDark ? 'light' : 'dark';
    if (next === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.documentElement.removeAttribute('data-theme');
        if (SCHOOL_ACCENT) {
            document.documentElement.style.setProperty('--accent', SCHOOL_ACCENT);
            document.documentElement.style.setProperty('--accent-bg', SCHOOL_ACCENT_BG || SCHOOL_ACCENT);
        }
    }
    localStorage.setItem('atrio-theme', next);
    updateIcons(next);
}

document.addEventListener('DOMContentLoaded', function() {
    const theme = localStorage.getItem('atrio-theme') ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    updateIcons(theme);
    if (theme === 'light' && SCHOOL_ACCENT) {
        document.documentElement.style.setProperty('--accent', SCHOOL_ACCENT);
        document.documentElement.style.setProperty('--accent-bg', SCHOOL_ACCENT_BG || SCHOOL_ACCENT);
    }
});
</script>
</body>
</html>
