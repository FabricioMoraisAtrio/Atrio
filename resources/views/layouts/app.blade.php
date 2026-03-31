<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── VARIÁVEIS ── */
        :root {
            --bg-page:   #EEF4FB;
            --bg-card:   #FFFFFF;
            --bg-subtle: #F0F6FD;
            --bg-hover:  #E2EDF8;
            --border:    #C8DDF0;
            --border-sub:#D8E9F5;
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
            --bg-hover:  rgba(255,255,255,0.07);
            --border:    #384F6E;
            --border-sub:#2A3B58;
            --text-1:    #FFFFFF;
            --text-2:    #C8D8EE;
            --text-3:    #8AAAC8;
            --text-4:    #6090B4;
            --accent:    #4D9FFF;
            --accent-bg: rgba(77,159,255,0.20);
        }
        [data-theme="dark"] body { color: var(--text-1) !important; }

        /* ── BASE ── */
        body { background: var(--bg-page) !important; }
        aside { background: var(--bg-card) !important; border-color: var(--border) !important; }
        header { background: var(--bg-card) !important; border-color: var(--border) !important; }
        main { background: var(--bg-page) !important; }

        /* ── DARK: sidebar levemente mais escura que o conteúdo ── */
        [data-theme="dark"] aside {
            background: #1A2740 !important;
            border-color: #304560 !important;
        }
        [data-theme="dark"] header {
            background: #1C2A44 !important;
            border-color: #304560 !important;
        }

        /* ── DARK: tabelas ── */
        [data-theme="dark"] table { border-color: #304560 !important; }
        [data-theme="dark"] thead tr { background: #1A2740 !important; }
        [data-theme="dark"] tbody tr:hover td { background: rgba(77,159,255,0.08) !important; }
        [data-theme="dark"] td, [data-theme="dark"] th { border-color: #304560 !important; }

        /* ── CARDS E FUNDOS ── */
        [style*="background: #fff"], [style*="background:#fff"] { background: var(--bg-card) !important; }
        [style*="background: #F9FAFB"] { background: var(--bg-subtle) !important; }
        [style*="background: #F3F4F6"] { background: var(--bg-hover) !important; }
        [style*="background: #E8F0F9"] { background: var(--accent-bg) !important; }
        [style*="background: #E6F5F4"] { background: rgba(0,156,140,0.12) !important; }
        [style*="background: #F5EDE6"] { background: rgba(124,55,0,0.15) !important; }
        [style*="background: #F3E8FF"] { background: rgba(139,92,246,0.15) !important; }

        /* ── CARD PENDENTES ── */
        [style*="background: #FFFBEB"] { background: rgba(234,179,8,0.07) !important; }
        [style*="background: #FEF3C7"] { background: rgba(234,179,8,0.1) !important; }
        /* Mural de observações */
        [data-theme="dark"] .observation-feed-card {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }

        [data-theme="dark"] [style*="background: #FFFBEB"] {
            background: rgba(96,165,250,0.06) !important;
        }
        [data-theme="dark"] [style*="background: #FEF3C7"] {
            background: rgba(96,165,250,0.1) !important;
        }
        [data-theme="dark"] [style*="border: 1px solid #FDE68A"] {
            border-color: rgba(77,159,255,0.2) !important;
        }
        [data-theme="dark"] [style*="color: #92400E"] { color: #7EC8FF !important; }

        /* ── BORDAS ── */
        [style*="border: 1px solid #F3F4F6"]        { border-color: var(--border-sub) !important; }
        [style*="border-top: 1px solid #F9FAFB"]    { border-color: var(--border-sub) !important; }
        [style*="border-bottom: 1px solid #F3F4F6"] { border-color: var(--border-sub) !important; }
        [style*="border-bottom: 1px solid #F9FAFB"] { border-color: var(--border-sub) !important; }
        [style*="border-bottom: 1px solid #E5E7EB"] { border-color: var(--border) !important; }
        [style*="border-right: 1px solid #E5E7EB"]  { border-color: var(--border) !important; }
        [style*="border-bottom: 2px solid #E5E7EB"] { border-color: var(--border) !important; }

        /* ── TEXTOS ── */
        [style*="color: #111827"] { color: var(--text-1) !important; }
        [style*="color: #374151"] { color: var(--text-2) !important; }
        [style*="color: #6B7280"] { color: var(--text-3) !important; }
        [style*="color: #9CA3AF"] { color: var(--text-4) !important; }

        /* ── INPUTS ── */
        input, textarea, select { color: var(--text-1) !important; }
        [data-theme="dark"] select { background: var(--bg-subtle) !important; }
        [data-theme="dark"] input[type="date"] { background: var(--bg-subtle) !important; color-scheme: dark; }
        [data-theme="dark"] label[style*="background: #F3F4F6"] { color: var(--text-2) !important; }

        /* ── SIDEBAR ATIVO ── */
        [style*="background: #E8F0F9; color: #004B8D"] {
            background: var(--accent-bg) !important;
            color: var(--accent) !important;
        }

        /* ── BADGE TOPBAR ── */
        [style*="background: #F3F4F6; padding: 4px 10px"] {
            background: var(--bg-subtle) !important;
            color: var(--text-2) !important;
        }

        /* ── HOVER DARK ── */
        [data-theme="dark"] a[href]:hover {
            background: rgba(77,159,255,0.10) !important;
        }
        /* Hover no dark mode — handlers inline são removidos via JS, o CSS assume */
        [data-theme="dark"] tbody tr { background: transparent !important; }
        [data-theme="dark"] tbody tr:hover td {
            background: rgba(96,165,250,0.06) !important;
        }
        [data-theme="dark"] button[style*="background: #004B8D"]:hover {
            background: #2272CC !important;
        }
        [data-theme="dark"] a[style*="background: #004B8D"]:hover {
            background: #2272CC !important;
        }
        [data-theme="dark"] button[style*="color: #EF4444"]:hover {
            background: rgba(239,68,68,0.15) !important;
        }
        /* Sidebar item ativo no dark */
        [data-theme="dark"] [style*="background: #E8F0F9; color: #004B8D"] {
            background: rgba(77,159,255,0.22) !important;
            color: #7EC8FF !important;
        }
        /* Garante texto branco em labels e spans no dark */
        [data-theme="dark"] span[style*="color: #374151"],
        [data-theme="dark"] span[style*="color: #6B7280"],
        [data-theme="dark"] label[style*="color: #374151"],
        [data-theme="dark"] p[style*="color: #374151"] { color: var(--text-2) !important; }
        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] textarea::placeholder { color: var(--text-4) !important; }

        /* ── BOTÃO TEMA ── */
        #theme-toggle {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg-subtle);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: var(--text-3);
            transition: background 0.15s;
        }
        #theme-toggle:hover { background: var(--bg-hover) !important; }
    </style>
    @php
        $schoolTheme = null;
        $accentBg    = null;
        if (auth()->check()) {
            $rawColor = auth()->user()->school?->theme_color;
            if ($rawColor && preg_match('/^#[0-9A-Fa-f]{6}$/', $rawColor)) {
                $schoolTheme = $rawColor;
                $r  = hexdec(substr($rawColor, 1, 2));
                $g  = hexdec(substr($rawColor, 3, 2));
                $b  = hexdec(substr($rawColor, 5, 2));
                $lr = round($r * 0.14 + 255 * 0.86);
                $lg = round($g * 0.14 + 255 * 0.86);
                $lb = round($b * 0.14 + 255 * 0.86);
                $accentBg = sprintf('#%02x%02x%02x', $lr, $lg, $lb);
            }
        }
    @endphp
    @if($schoolTheme)
    <style>
        :root {
            --accent:    {{ $schoolTheme }};
            --accent-bg: {{ $accentBg }};
        }
        /* Override inline styles hardcoded com a cor padrão Átrio */
        [style*="background: #004B8D"] { background: {{ $schoolTheme }} !important; }
        [style*="color: #004B8D"]      { color: {{ $schoolTheme }} !important; }
        [style*="background: #E8F0F9"] { background: {{ $accentBg }} !important; }
        [style*="border-color: #004B8D"], [style*="border-bottom-color: #004B8D"] {
            border-color: {{ $schoolTheme }} !important;
        }
    </style>
    @endif
    <script>
        // Aplica tema antes do render para evitar flash
        (function() {
            const saved = localStorage.getItem('atrio-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            if (theme === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside style="width: 240px; border-right: 1px solid #E5E7EB; display: flex; flex-direction: column; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40;">

        {{-- Logo --}}
        <div style="padding: 24px 20px 20px; border-bottom: 1px solid #F3F4F6;">
            @php
                $roleDashboardMap = ['secretaria' => 'secretaria.dashboard', 'coordenador' => 'secretaria.dashboard', 'orientador' => 'secretaria.dashboard', 'professor' => 'professor.dashboard', 'pai' => 'pai.dashboard'];
                $dashboardRoute = auth()->check() ? ($roleDashboardMap[auth()->user()->getRoleNames()->first()] ?? '/') : '/';
            @endphp
            <a href="{{ auth()->check() ? route($dashboardRoute) : '/' }}"
               style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                @auth
                    @php $school = auth()->user()->school; @endphp
                    @if($school?->logo)
                        <img src="{{ route('school.logo', ['filename' => basename($school->logo)]) }}"
                             style="height: 40px; object-fit: contain; max-width: 120px; flex-shrink: 0;">
                        <div style="min-width: 0;">
                            <div style="font-size: 13px; font-weight: 700; color: #004B8D; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $school->name }}</div>
                            <div style="font-size: 10px; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                        </div>
                    @else
                        <div style="width: 36px; height: 36px; background: #004B8D; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                            </svg>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-size: 15px; font-weight: 700; color: #004B8D; letter-spacing: 0.5px;">ÁTRIO</div>
                            <div style="font-size: 10px; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase;">
                                {{ $school?->name ?? 'Portal Institucional' }}
                            </div>
                        </div>
                    @endif
                @else
                    <div style="width: 36px; height: 36px; background: #004B8D; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                            <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 15px; font-weight: 700; color: #004B8D; letter-spacing: 0.5px;">ÁTRIO</div>
                        <div style="font-size: 10px; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                    </div>
                @endauth
            </a>
        </div>

        <nav style="flex: 1; padding: 16px 12px;">
            @auth
                @hasanyrole(['coordenador', 'orientador'])
                    @php
                        $pendentesCount = \Illuminate\Support\Facades\Cache::remember(
                            'pendentes_count_' . session('school_id'),
                            now()->addMinutes(5),
                            fn() => \App\Models\Student::where('is_atypical', true)
                                ->with(['documents' => fn($q) => $q->where('year', date('Y'))->select('id','student_id','type')])
                                ->get()
                                ->filter(fn($a) => count(array_diff(['estudo_caso','pei','paee'], $a->documents->pluck('type')->toArray())) > 0)
                                ->count()
                        );
                        $items = [
                            ['route' => 'secretaria.dashboard',        'icon' => 'grid',     'label' => 'Painel'],
                            ['route' => 'secretaria.turmas.index',     'icon' => 'academic', 'label' => 'Turmas'],
                            ['route' => 'secretaria.alunos.index',     'icon' => 'users',    'label' => 'Alunos', 'badge' => $pendentesCount ?: null],
                            ['route' => 'secretaria.documentos.index', 'icon' => 'doc',      'label' => 'Documentos'],
                            ['route' => 'secretaria.laudos.index',     'icon' => 'laudo',    'label' => 'Laudos'],
                            ['route' => 'secretaria.usuarios.index',   'icon' => 'user',     'label' => 'Usuários'],
                        ];
                    @endphp
                @endhasanyrole

                @hasrole('secretaria')
                    @php $items = [
                        ['route' => 'secretaria.dashboard',      'icon' => 'grid',  'label' => 'Painel'],
                        ['route' => 'secretaria.alunos.index',   'icon' => 'users', 'label' => 'Alunos'],
                        ['route' => 'secretaria.usuarios.index', 'icon' => 'user',  'label' => 'Usuários'],
                    ]; @endphp
                @endhasrole

                @hasrole('professor')
                    @php $items = [
                        ['route' => 'professor.dashboard',        'icon' => 'grid',     'label' => 'Painel'],
                        ['route' => 'professor.turmas.index',     'icon' => 'academic', 'label' => 'Turmas'],
                        ['route' => 'professor.documentos.index', 'icon' => 'doc',      'label' => 'Documentos'],
                    ]; @endphp
                @endhasrole

                @hasrole('pai')
                    @php $items = [
                        ['route' => 'pai.dashboard', 'icon' => 'grid', 'label' => 'Painel'],
                    ]; @endphp
                @endhasrole

                @foreach($items ?? [] as $item)
                    @php
                        $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
                        $badge = $item['badge'] ?? null;
                    @endphp
                    <a href="{{ route($item['route']) }}"
                       style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 2px; font-size: 14px; font-weight: 500; text-decoration: none;
                           {{ $isActive ? 'background: #E8F0F9; color: #004B8D;' : 'color: #6B7280;' }}">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @include('layouts.partials.icon', ['icon' => $item['icon'], 'active' => $isActive])
                            {{ $item['label'] }}
                        </div>
                        @if($badge)
                            <span style="background: #EF4444; color: white; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 20px;">
                                {{ $badge }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endauth
        </nav>

        @auth
        <div style="padding: 16px 12px; border-top: 1px solid #F3F4F6;">
            <a href="{{ route('profile.edit') }}"
               style="display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; text-decoration: none; color: #374151;">
                @if(auth()->user()->avatar)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}"
                         style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                @else
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #004B8D; color: white; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; flex-shrink: 0;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 13px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="font-size: 11px; color: #9CA3AF;">
                        @php
                            $roleLabels = [
                                'secretaria'  => 'Secretaria',
                                'coordenador' => 'Coordenação',
                                'orientador'  => 'Orientação Pedagógica',
                                'professor'   => 'Professor',
                                'pai'         => 'Responsável',
                            ];
                            echo $roleLabels[auth()->user()->getRoleNames()->first()] ?? auth()->user()->getRoleNames()->first();
                        @endphp
                    </div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top: 4px;">
                @csrf
                <button type="submit"
                        style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; border: none; background: none; cursor: pointer; font-size: 13px; color: #EF4444; text-align: left;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Sair
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <div style="margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh;">

        <header style="border-bottom: 1px solid #E5E7EB; padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <span style="color: #111827; font-weight: 500;">@yield('title')</span>
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <button id="theme-toggle" onclick="toggleTheme()" title="Alternar tema">
                    <svg id="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                        <circle cx="12" cy="12" r="5"/>
                        <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>
                    <svg id="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>
            </div>
        </header>

        <main style="flex: 1; padding: 32px;">
            @if(session('success'))
                <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

<script>
// Cores da escola injetadas pelo servidor (usadas ao voltar para o tema claro)
const SCHOOL_ACCENT    = @json($schoolTheme ?? null);
const SCHOOL_ACCENT_BG = @json($accentBg ?? null);

function updateIcons(theme) {
    const sun  = document.getElementById('icon-sun');
    const moon = document.getElementById('icon-moon');
    if (!sun || !moon) return;
    sun.style.display  = theme === 'dark' ? 'block' : 'none';
    moon.style.display = theme === 'dark' ? 'none'  : 'block';
}

function applySchoolTheme() {
    if (!SCHOOL_ACCENT) return;
    const root = document.documentElement;
    root.style.setProperty('--accent',    SCHOOL_ACCENT);
    root.style.setProperty('--accent-bg', SCHOOL_ACCENT_BG || SCHOOL_ACCENT);
}

function removeSchoolTheme() {
    const root = document.documentElement;
    root.style.removeProperty('--accent');
    root.style.removeProperty('--accent-bg');
}

// Armazena os handlers inline originais para restaurar no tema claro
const _hoverStore = new WeakMap();

function applyHoverBehavior(theme) {
    if (theme === 'dark') {
        document.querySelectorAll('[onmouseover]').forEach(el => {
            _hoverStore.set(el, {
                over: el.getAttribute('onmouseover'),
                out:  el.getAttribute('onmouseout'),
            });
            el.removeAttribute('onmouseover');
            el.removeAttribute('onmouseout');
            // Limpa qualquer background inline que já tenha sido aplicado
            if (el.style.background && el.style.background !== 'transparent') {
                el.style.background = 'transparent';
            }
        });
    } else {
        // Restaura handlers ao voltar para o tema claro
        document.querySelectorAll('tr, a, button').forEach(el => {
            const stored = _hoverStore.get(el);
            if (stored) {
                el.setAttribute('onmouseover', stored.over);
                if (stored.out) el.setAttribute('onmouseout', stored.out);
                _hoverStore.delete(el);
            }
        });
    }
}

function toggleTheme() {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const next   = isDark ? 'light' : 'dark';

    if (next === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        removeSchoolTheme();
    } else {
        document.documentElement.removeAttribute('data-theme');
        applySchoolTheme();
    }

    applyHoverBehavior(next);
    localStorage.setItem('atrio-theme', next);
    updateIcons(next);
}

// Inicializa ao carregar
(function () {
    const saved       = localStorage.getItem('atrio-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme       = saved || (prefersDark ? 'dark' : 'light');

    if (theme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        applySchoolTheme();
    }
    updateIcons(theme);

    // Aplica remoção dos hover handlers após o DOM estar pronto
    if (theme === 'dark') {
        document.addEventListener('DOMContentLoaded', () => applyHoverBehavior('dark'));
    }
})();
</script>
</body>
</html>