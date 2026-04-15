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
            --border-sub:#2D4265;
            --text-1:    #F0F6FF;
            --text-2:    #C8D8EE;
            --text-3:    #8AAAC8;
            --text-4:    #6090B4;
            --accent:    #4D9FFF;
            --accent-bg: rgba(77,159,255,0.20);
        }

        /* ── BASE ── */
        body { background: var(--bg-page) !important; color: var(--text-1) !important; }
        aside { background: var(--bg-card) !important; border-color: var(--border) !important; }
        header { background: var(--bg-card) !important; border-color: var(--border) !important; }
        main { background: var(--bg-page) !important; }

        /* ── DARK: estrutura ── */
        [data-theme="dark"] aside  { background: #1A2740 !important; border-color: #304560 !important; }
        [data-theme="dark"] header { background: #1C2A44 !important; border-color: #304560 !important; }
        [data-theme="dark"] table  { border-color: #304560 !important; }
        [data-theme="dark"] thead tr { background: #1A2740 !important; }
        [data-theme="dark"] td, [data-theme="dark"] th { border-color: #304560 !important; }
        [data-theme="dark"] tbody tr { background: transparent !important; }
        [data-theme="dark"] tbody tr:hover td { background: rgba(96,165,250,0.06) !important; }

        /* ── TEXTOS — com e sem espaço após os dois pontos ── */
        [style*="color: #0D1F36"],[style*="color:#0D1F36"] { color: var(--text-1) !important; }
        [style*="color: #0F172A"],[style*="color:#0F172A"] { color: var(--text-1) !important; }
        [style*="color: #111827"],[style*="color:#111827"] { color: var(--text-1) !important; }
        [style*="color: #1F2937"],[style*="color:#1F2937"] { color: var(--text-1) !important; }
        [style*="color: #374151"],[style*="color:#374151"] { color: var(--text-2) !important; }
        [style*="color: #4B5563"],[style*="color:#4B5563"] { color: var(--text-2) !important; }
        [style*="color: #6B7280"],[style*="color:#6B7280"] { color: var(--text-3) !important; }
        [style*="color: #9CA3AF"],[style*="color:#9CA3AF"] { color: var(--text-4) !important; }
        [style*="color: #D1D5DB"],[style*="color:#D1D5DB"] { color: var(--text-3) !important; }

        /* ── FUNDOS BRANCOS E CINZAS — com e sem espaço ── */
        [style*="background: #fff"],[style*="background:#fff"],
        [style*="background: white"],[style*="background:white"],
        [style*="background: #FFFFFF"],[style*="background:#FFFFFF"] { background: var(--bg-card) !important; }

        [style*="background: #F8FAFC"],[style*="background:#F8FAFC"],
        [style*="background: #F9FAFB"],[style*="background:#F9FAFB"],
        [style*="background: #FAFAFA"],[style*="background:#FAFAFA"],
        [style*="background: #F3F4F6"],[style*="background:#F3F4F6"],
        [style*="background: #F0F5FB"],[style*="background:#F0F5FB"],
        [style*="background: #F0F6FD"],[style*="background:#F0F6FD"] { background: var(--bg-subtle) !important; }

        /* ── FUNDOS COLORIDOS — com e sem espaço ── */
        [style*="background: #E8F0F9"],[style*="background:#E8F0F9"] { background: var(--accent-bg) !important; }
        [style*="background: #E6F5F4"],[style*="background:#E6F5F4"] { background: rgba(0,156,140,0.15) !important; }
        [style*="background: #F5EDE6"],[style*="background:#F5EDE6"] { background: rgba(124,55,0,0.18) !important; }
        [style*="background: #F3E8FF"],[style*="background:#F3E8FF"] { background: rgba(139,92,246,0.18) !important; }
        [style*="background: #EDE9FE"],[style*="background:#EDE9FE"] { background: rgba(109,40,217,0.18) !important; }
        [style*="background: #EFF6E8"],[style*="background:#EFF6E8"] { background: rgba(61,122,39,0.15) !important; }
        [style*="background: #FFF4E6"],[style*="background:#FFF4E6"] { background: rgba(199,122,0,0.15) !important; }
        [style*="background: #F0F5FF"],[style*="background:#F0F5FF"] { background: rgba(59,91,219,0.15) !important; }
        [style*="background: #E8FAF7"],[style*="background:#E8FAF7"] { background: rgba(0,156,140,0.13) !important; }

        /* ── STATUS BADGES ── */
        [style*="background: #ECFDF5"],[style*="background:#ECFDF5"] { background: rgba(6,95,70,0.28) !important; }
        [style*="background: #FEF2F2"],[style*="background:#FEF2F2"] { background: rgba(153,27,27,0.28) !important; }
        [style*="background: #FEF3C7"],[style*="background:#FEF3C7"],
        [style*="background: #FFFBEB"],[style*="background:#FFFBEB"] { background: rgba(146,64,14,0.25) !important; }

        /* ── CAIXAS INFO AZUIS (EFF6FF/BFDBFE/DBEAFE) ── */
        [style*="background: #EFF6FF"],[style*="background:#EFF6FF"] { background: rgba(59,130,246,0.12) !important; }
        [style*="background: #DBEAFE"],[style*="background:#DBEAFE"] { background: rgba(59,130,246,0.18) !important; }
        [style*="background: #D1FAE5"],[style*="background:#D1FAE5"] { background: rgba(16,185,129,0.18) !important; }
        [style*="border: 1px solid #BFDBFE"],[style*="border:1px solid #BFDBFE"] { border-color: rgba(59,130,246,0.35) !important; }
        [style*="border-left: 2px solid #BFDBFE"],[style*="border-left:2px solid #BFDBFE"] { border-left-color: rgba(59,130,246,0.4) !important; }

        /* ── CAIXAS INVENTÁRIO PEI (categorias) ── */
        [style*="background: #F0EBF8"],[style*="background:#F0EBF8"] { background: rgba(109,40,217,0.18) !important; }
        [style*="border: 1px solid #E8F0F9"],[style*="border:1px solid #E8F0F9"] { border-color: rgba(0,75,141,0.3) !important; }
        [style*="border: 1px solid #E6F5F4"],[style*="border:1px solid #E6F5F4"] { border-color: rgba(0,156,140,0.3) !important; }
        [style*="border: 1px solid #F0EBF8"],[style*="border:1px solid #F0EBF8"] { border-color: rgba(109,40,217,0.3) !important; }
        [style*="border: 1px solid #C5D8F0"],[style*="border:1px solid #C5D8F0"] { border-color: var(--border) !important; }

        /* ── DARK: textos de badge com cor forte ── */
        [data-theme="dark"] [style*="color: #065F46"],[data-theme="dark"] [style*="color:#065F46"] { color: #6EDDB8 !important; }
        [data-theme="dark"] [style*="color: #991B1B"],[data-theme="dark"] [style*="color:#991B1B"] { color: #FCA5A5 !important; }
        [data-theme="dark"] [style*="color: #92400E"],[data-theme="dark"] [style*="color:#92400E"] { color: #FCD34D !important; }
        [data-theme="dark"] [style*="color: #7C3700"],[data-theme="dark"] [style*="color:#7C3700"] { color: #FCD34D !important; }
        [data-theme="dark"] [style*="color: #7E22CE"],[data-theme="dark"] [style*="color:#7E22CE"] { color: #C4B5FD !important; }
        [data-theme="dark"] [style*="color: #7C3AED"],[data-theme="dark"] [style*="color:#7C3AED"] { color: #C4B5FD !important; }
        [data-theme="dark"] [style*="color: #009C8C"],[data-theme="dark"] [style*="color:#009C8C"] { color: #5EEAD4 !important; }
        [data-theme="dark"] [style*="color: #004B8D"],[data-theme="dark"] [style*="color:#004B8D"] { color: var(--accent) !important; }
        [data-theme="dark"] [style*="color: #3D7A27"],[data-theme="dark"] [style*="color:#3D7A27"] { color: #86EFAC !important; }
        [data-theme="dark"] [style*="color: #B45309"],[data-theme="dark"] [style*="color:#B45309"] { color: #FCD34D !important; }
        [data-theme="dark"] [style*="color: #6D28D9"],[data-theme="dark"] [style*="color:#6D28D9"] { color: #C4B5FD !important; }
        [data-theme="dark"] [style*="color: #1E40AF"],[data-theme="dark"] [style*="color:#1E40AF"] { color: #93C5FD !important; }
        [data-theme="dark"] [style*="color: #1D4ED8"],[data-theme="dark"] [style*="color:#1D4ED8"] { color: #60A5FA !important; }

        /* ── BORDAS — com e sem espaço ── */
        [style*="border:1px solid #F3F4F6"],
        [style*="border: 1px solid #F3F4F6"]        { border-color: var(--border-sub) !important; }
        [style*="border:1px solid #E5E7EB"],
        [style*="border: 1px solid #E5E7EB"]        { border-color: var(--border) !important; }
        [style*="border:1px solid #E2EAF4"],
        [style*="border: 1px solid #E2EAF4"]        { border-color: var(--border) !important; }
        [style*="border:1px solid #D1D5DB"],
        [style*="border: 1px solid #D1D5DB"]        { border-color: var(--border) !important; }
        [style*="border-top:1px solid #F9FAFB"],
        [style*="border-top: 1px solid #F9FAFB"]    { border-color: var(--border-sub) !important; }
        [style*="border-bottom:1px solid #F3F4F6"],
        [style*="border-bottom: 1px solid #F3F4F6"] { border-color: var(--border-sub) !important; }
        [style*="border-bottom:1px solid #F9FAFB"],
        [style*="border-bottom: 1px solid #F9FAFB"] { border-color: var(--border-sub) !important; }
        [style*="border-bottom:1px solid #E5E7EB"],
        [style*="border-bottom: 1px solid #E5E7EB"] { border-color: var(--border) !important; }
        [style*="border-right:1px solid #E5E7EB"],
        [style*="border-right: 1px solid #E5E7EB"]  { border-color: var(--border) !important; }
        [style*="border-bottom:2px solid #E5E7EB"],
        [style*="border-bottom: 2px solid #E5E7EB"] { border-color: var(--border) !important; }

        /* ── DARK: bordas coloridas de alerta ── */
        [data-theme="dark"] [style*="border:1px solid #6EE7B7"],
        [data-theme="dark"] [style*="border: 1px solid #6EE7B7"] { border-color: rgba(110,221,184,0.3) !important; }
        [data-theme="dark"] [style*="border:1px solid #FECACA"],
        [data-theme="dark"] [style*="border: 1px solid #FECACA"] { border-color: rgba(252,165,165,0.3) !important; }
        [data-theme="dark"] [style*="border:1px solid #FDE68A"],
        [data-theme="dark"] [style*="border: 1px solid #FDE68A"] { border-color: rgba(253,230,138,0.2) !important; }

        /* ── INPUTS ── */
        input, textarea, select { color: var(--text-1) !important; }
        [data-theme="dark"] input  { background: transparent !important; }
        [data-theme="dark"] textarea { background: var(--bg-subtle) !important; border-color: var(--border) !important; }
        [data-theme="dark"] select { background: var(--bg-subtle) !important; border-color: var(--border) !important; }
        [data-theme="dark"] input[type="date"] { background: var(--bg-subtle) !important; color-scheme: dark; }
        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] textarea::placeholder { color: var(--text-4) !important; }

        /* ── SIDEBAR ATIVO ── */
        [style*="background: #E8F0F9; color: #004B8D"] {
            background: var(--accent-bg) !important;
            color: var(--accent) !important;
        }
        [data-theme="dark"] [style*="background: #E8F0F9; color: #004B8D"] {
            background: rgba(77,159,255,0.22) !important;
            color: #7EC8FF !important;
        }

        /* ── MURAL DE OBSERVAÇÕES ── */
        [data-theme="dark"] .observation-feed-card {
            background: var(--bg-card) !important;
            border-color: var(--border) !important;
        }

        /* ── HOVER DARK ── */
        [data-theme="dark"] button[style*="background: #004B8D"]:hover { background: #2272CC !important; }
        [data-theme="dark"] a[style*="background: #004B8D"]:hover       { background: #2272CC !important; }
        [data-theme="dark"] button[style*="color: #EF4444"]:hover        { background: rgba(239,68,68,0.15) !important; }

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
        /* Restaura variáveis dark que foram sobrescritas pelo school theme */
        [data-theme="dark"] {
            --accent:    #4D9FFF;
            --accent-bg: rgba(77,159,255,0.20);
        }
        /* Override inline styles — apenas no tema claro */
        html:not([data-theme="dark"]) [style*="background: #004B8D"] { background: {{ $schoolTheme }} !important; }
        html:not([data-theme="dark"]) [style*="background: #E8F0F9"] { background: {{ $accentBg }} !important; }
        /* Cor do texto e borda aplicam em ambos os temas via variável */
        [style*="color: #004B8D"]      { color: var(--accent) !important; }
        [style*="border-color: #004B8D"], [style*="border-bottom-color: #004B8D"] {
            border-color: var(--accent) !important;
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
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside style="width: 240px; border-right: 1px solid #E5E7EB; display: flex; flex-direction: column; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40;">

        {{-- Logo --}}
        <div style="padding: 24px 20px 20px; border-bottom: 1px solid #F3F4F6;">
            @php
                $roleDashboardMap = ['admin' => 'secretaria.dashboard', 'coordenador' => 'secretaria.dashboard', 'orientador' => 'secretaria.dashboard', 'professor' => 'professor.dashboard'];
                $dashboardRoute = auth()->check() ? ($roleDashboardMap[auth()->user()->getRoleNames()->first()] ?? 'secretaria.dashboard') : 'secretaria.dashboard';
            @endphp
            <a href="{{ route($dashboardRoute) }}"
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

        <nav style="flex: 1; padding: 16px 12px; overflow-y: auto;">
            @auth
                @php
                    $school     = auth()->user()->school;
                    $hasModule  = fn(string $k) => !$school || $school->hasModule($k);
                    $pendCacheKey = 'pendentes_count_' . session('school_id');
                    $pendentesCount = 0;
                @endphp

                @hasanyrole(['admin','coordenador','orientador'])
                    @php
                        $pendentesCount = \Illuminate\Support\Facades\Cache::remember(
                            $pendCacheKey, now()->addMinutes(5),
                            fn() => \App\Models\Student::where('is_atypical', true)
                                ->with(['documents' => fn($q) => $q->where('year', date('Y'))->select('id','student_id','type')])
                                ->get()
                                ->filter(fn($a) => count(array_diff(['estudo_caso','pei','paee'], $a->documents->pluck('type')->toArray())) > 0)
                                ->count()
                        );
                        $isAdmin = auth()->user()->hasRole('admin');
                        $items = [
                            ['route' => 'secretaria.dashboard',                  'icon' => 'home',    'label' => 'Início'],
                            ['route' => 'secretaria.painel',                     'icon' => 'grid',    'label' => 'Painel de Acompanhamento', 'module' => 'painel'],
                            ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos', 'badge' => $pendentesCount ?: null],
                            ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos'],
                            ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),        'module' => 'turmas'],
                            ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                            ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',            'module' => 'usuarios'],
                            [
                                'route'    => 'secretaria.config.index',
                                'icon'     => 'config',
                                'label'    => 'Configurações',
                                'active'   => 'secretaria.config.*',
                                'module'   => 'configuracoes',
                            ],
                            ['route' => 'secretaria.logs.index', 'icon' => 'log', 'label' => 'Registro de Acessos', 'admin_only' => true],
                        ];
                        // Coordenador/orientador não vê Configurações nem Logs
                        if (!$isAdmin) {
                            $items = array_filter($items, fn($i) =>
                                ($i['module'] ?? '') !== 'configuracoes' && empty($i['admin_only'])
                            );
                        }
                    @endphp
                @endhasanyrole

                @hasrole('professor')
                    @php $items = [
                        ['route' => 'professor.dashboard',    'icon' => 'home',     'label' => 'Início'],
                        ['route' => 'professor.painel',       'icon' => 'grid',     'label' => 'Painel de Acompanhamento'],
                        ['route' => 'professor.turmas.index', 'icon' => 'academic', 'label' => 'Turmas'],
                    ]; @endphp
                @endhasrole

                @php
                    if (!isset($items) && auth()->check()) {
                        $schoolId = session('school_id');
                        if (auth()->user()->roles()->where('name', 'like', "s{$schoolId}_%")->exists()) {
                            $items = [
                                ['route' => 'secretaria.dashboard',                  'icon' => 'home',    'label' => 'Início'],
                                ['route' => 'secretaria.painel',                     'icon' => 'grid',    'label' => 'Painel de Acompanhamento', 'module' => 'painel'],
                                ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos'],
                                ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos'],
                                ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),       'module' => 'turmas'],
                                ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                                ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',           'module' => 'usuarios'],
                            ];
                        }
                    }
                @endphp

                @foreach($items ?? [] as $item)
                    @php
                        if (isset($item['module']) && !$hasModule($item['module'])) continue;
                        $activePattern = $item['active'] ?? ($item['route'] . '.*');
                        $children      = $item['children'] ?? [];
                        $childActive   = collect($children)->contains(fn($c) => request()->routeIs($c['route']) || request()->routeIs($c['active'] ?? ($c['route'] . '.*')));
                        $isActive      = request()->routeIs($item['route']) || request()->routeIs($activePattern) || $childActive;
                        $badge         = $item['badge'] ?? null;
                        $hasChildren   = !empty($children);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                       style="display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; border-radius: 8px; margin-bottom: 2px; font-size: 14px; font-weight: 500; text-decoration: none;
                              {{ $isActive ? 'background: var(--accent-bg,#E8F0F9); color: var(--accent,#004B8D);' : 'color: #6B7280;' }}">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @include('layouts.partials.icon', ['icon' => $item['icon'], 'active' => $isActive])
                            {{ $item['label'] }}
                        </div>
                        @if($badge)
                            <span style="background: #EF4444; color: white; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 20px;">{{ $badge }}</span>
                        @elseif($hasChildren)
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="{{ $isActive ? 'transform:rotate(90deg);' : '' }} transition: transform 0.15s;">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        @endif
                    </a>

                    @if($hasChildren && $isActive)
                        @foreach($children as $child)
                            @php
                                if (isset($child['module']) && !$hasModule($child['module'])) continue;
                                $childActivePattern = $child['active'] ?? ($child['route'] . '.*');
                                $childIsActive = request()->routeIs($child['route']) || request()->routeIs($childActivePattern);
                                $childHref = route($child['route'], $child['params'] ?? []);
                            @endphp
                            <a href="{{ $childHref }}"
                               style="display: flex; align-items: center; gap: 10px; padding: 8px 12px 8px 36px; border-radius: 8px; margin-bottom: 2px; font-size: 13px; font-weight: 500; text-decoration: none;
                                      {{ $childIsActive ? 'background: var(--accent-bg,#E8F0F9); color: var(--accent,#004B8D);' : 'color: #9CA3AF;' }}">
                                @include('layouts.partials.icon', ['icon' => $child['icon'], 'active' => $childIsActive])
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    @endif
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
                                'admin'       => 'Administrador',
                                'coordenador' => 'Coordenação',
                                'orientador'  => 'Orientação Pedagógica',
                                'professor'   => 'Professor',
                            ];
                            $sidebarRole = auth()->user()->getRoleNames()->first();
                            $sidebarRoleLabel = $roleLabels[$sidebarRole] ?? null;
                            if (!$sidebarRoleLabel && $sidebarRole && str_starts_with($sidebarRole, 's')) {
                                $sidebarRoleLabel = \App\Models\SchoolRole::where('spatie_role', $sidebarRole)->value('name');
                            }
                            echo $sidebarRoleLabel ?? $sidebarRole;
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

{{-- ── MODAL DE CONFIRMAÇÃO GLOBAL ── --}}
<div id="confirm-modal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
    {{-- Overlay --}}
    <div id="confirm-overlay"
         onclick="closeConfirm()"
         style="position:absolute;inset:0;background:rgba(0,0,0,0.45);backdrop-filter:blur(2px);"></div>

    {{-- Card --}}
    <div style="position:relative;background:var(--bg-card);border:1px solid var(--border);border-radius:16px;
                padding:32px 28px;width:100%;max-width:380px;margin:0 16px;
                box-shadow:0 20px 60px rgba(0,0,0,0.2);z-index:1;">

        {{-- Ícone --}}
        <div style="width:48px;height:48px;border-radius:14px;background:#FEF2F2;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
            </svg>
        </div>

        <p id="confirm-title"
           style="font-size:16px;font-weight:700;color:var(--text-1);text-align:center;margin:0 0 8px;"></p>
        <p id="confirm-desc"
           style="font-size:13px;color:var(--text-3);text-align:center;margin:0 0 28px;line-height:1.5;">
            Esta ação não pode ser desfeita.
        </p>

        <div style="display:flex;gap:10px;">
            <button onclick="closeConfirm()"
                    style="flex:1;padding:11px;border-radius:8px;border:1px solid var(--border);
                           background:transparent;color:var(--text-2);font-size:13px;font-weight:600;cursor:pointer;">
                Cancelar
            </button>
            <button id="confirm-ok"
                    style="flex:1;padding:11px;border-radius:8px;border:none;
                           background:#EF4444;color:white;font-size:13px;font-weight:600;cursor:pointer;">
                Remover
            </button>
        </div>
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
                bg:   el.style.background,
            });
            el.setAttribute('data-hover-stored', '1');
            el.removeAttribute('onmouseover');
            el.removeAttribute('onmouseout');
        });
    } else {
        // Restaura handlers e backgrounds ao voltar para o tema claro
        document.querySelectorAll('[data-hover-stored]').forEach(el => {
            const stored = _hoverStore.get(el);
            if (stored) {
                if (stored.over) el.setAttribute('onmouseover', stored.over);
                if (stored.out)  el.setAttribute('onmouseout',  stored.out);
                el.style.background = stored.bg;
                _hoverStore.delete(el);
            }
            el.removeAttribute('data-hover-stored');
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

// ── FOCUS DARK MODE — intercepta onfocus/onblur hardcoded ──
document.addEventListener('focusin', function(e) {
    const el = e.target;
    if (!['INPUT','TEXTAREA','SELECT'].includes(el.tagName)) return;
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--accent').trim();
    }
});
document.addEventListener('focusout', function(e) {
    const el = e.target;
    if (!['INPUT','TEXTAREA','SELECT'].includes(el.tagName)) return;
    if (document.documentElement.getAttribute('data-theme') === 'dark') {
        el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--border').trim();
    }
});

// ── MODAL DE CONFIRMAÇÃO ──
function openConfirm(title, desc, onOk) {
    const modal = document.getElementById('confirm-modal');
    document.getElementById('confirm-title').textContent = title;
    const descEl = document.getElementById('confirm-desc');
    descEl.textContent = desc || 'Esta ação não pode ser desfeita.';
    const okBtn = document.getElementById('confirm-ok');
    okBtn.onclick = function () { closeConfirm(); onOk(); };
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeConfirm() {
    document.getElementById('confirm-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Intercepta todos os botões com data-confirm
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-confirm]');
        if (!btn) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        const title = btn.getAttribute('data-confirm') || 'Confirmar remoção';
        const form  = btn.closest('form');
        openConfirm(title, 'Esta ação não pode ser desfeita.', function () {
            if (form) form.submit();
        });
    }, true);
});

// Fecha com ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeConfirm();
});

// ── INICIALIZA ──
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