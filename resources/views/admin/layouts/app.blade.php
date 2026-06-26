<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Átrio Admin — @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --adm-bg:#F4F6FB; --adm-card:#FFFFFF; --adm-border:#E6EAF2; --adm-border-2:#EEF1F7;
            --adm-text:#0F172A; --adm-text-2:#475569; --adm-text-3:#94A3B8;
            --adm-side:#0E1A2F; --adm-side-2:#16223B; --adm-accent:#3B82F6; --adm-accent-2:#1D4ED8;
            --adm-green:#0F9D58; --adm-green-bg:#E7F6EF; --adm-amber:#B45309; --adm-amber-bg:#FBF1DD;
            --adm-red:#DC2626; --adm-red-bg:#FDECEC;
        }
        body { background:var(--adm-bg); min-height:100vh; margin:0; font-family:'Inter',system-ui,sans-serif; color:var(--adm-text); }
        .adm-nav-item { display:flex; align-items:center; gap:11px; padding:10px 12px; border-radius:9px; margin-bottom:2px;
            font-size:13.5px; font-weight:500; text-decoration:none; color:rgba(255,255,255,0.58); transition:background .12s,color .12s; }
        .adm-nav-item:hover { background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.85); }
        .adm-nav-item.active { background:var(--adm-accent); color:#fff; box-shadow:0 4px 12px rgba(59,130,246,.35); }
        .adm-nav-item svg { flex-shrink:0; }
        .adm-card { background:var(--adm-card); border:1px solid var(--adm-border); border-radius:14px; }
        .adm-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:9px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; border:1px solid transparent; }
        .adm-btn-primary { background:var(--adm-accent); color:#fff; }
        .adm-btn-primary:hover { background:var(--adm-accent-2); }
        .adm-btn-ghost { background:transparent; border-color:var(--adm-border); color:var(--adm-text-2); }
        .adm-btn-ghost:hover { background:var(--adm-border-2); }

        /* ── ESCURO ── */
        [data-theme="dark"] {
            --adm-bg:#0E1626; --adm-card:#18233A; --adm-border:#2B3A55; --adm-border-2:#222F45;
            --adm-text:#EAF0FA; --adm-text-2:#AEBCD2; --adm-text-3:#7388A5;
            --adm-side:#0A1322; --adm-side-2:#13203A; --adm-accent:#4D9FFF; --adm-accent-2:#2F86F0;
            --adm-green:#34D399; --adm-green-bg:rgba(15,157,88,0.20); --adm-amber:#FBBF24; --adm-amber-bg:rgba(180,83,9,0.22);
            --adm-red:#F87171; --adm-red-bg:rgba(220,38,38,0.20);
        }
        /* ── ESCURO SUAVE (slate) ── */
        [data-theme="slate"] {
            --adm-bg:#1E222B; --adm-card:#272C37; --adm-border:#3B4250; --adm-border-2:#333A47;
            --adm-text:#ECEFF4; --adm-text-2:#C7CDD9; --adm-text-3:#9AA3B2;
            --adm-side:#191D25; --adm-side-2:#272C37; --adm-accent:#6CA8FF; --adm-accent-2:#4A7FBE;
            --adm-green:#5FD1A8; --adm-green-bg:rgba(15,157,88,0.18); --adm-amber:#EAC15B; --adm-amber-bg:rgba(180,103,0,0.20);
            --adm-red:#F4A6A0; --adm-red-bg:rgba(180,35,24,0.18);
        }
        /* ── ALTO CONTRASTE ── */
        [data-theme="contrast"] {
            --adm-bg:#000000; --adm-card:#0B0B0B; --adm-border:#5C5C5C; --adm-border-2:#1A1A1A;
            --adm-text:#FFFFFF; --adm-text-2:#ECECEC; --adm-text-3:#CFCFCF;
            --adm-side:#000000; --adm-side-2:#141414; --adm-accent:#6CB6FF; --adm-accent-2:#2E7FD6;
            --adm-green:#5DF0A0; --adm-green-bg:rgba(93,240,160,0.16); --adm-amber:#FFE066; --adm-amber-bg:rgba(255,224,102,0.14);
            --adm-red:#FF8585; --adm-red-bg:rgba(255,133,133,0.16);
        }
        /* telas de escola usam classes Tailwind — remapear na família escura */
        @php $admDark = ':is([data-theme=dark],[data-theme=slate],[data-theme=contrast])'; @endphp
        {{ $admDark }} .bg-white { background:var(--adm-card) !important; }
        {{ $admDark }} .bg-gray-50, {{ $admDark }} .bg-gray-100 { background:var(--adm-border-2) !important; }
        {{ $admDark }} .hover\:bg-gray-50:hover { background:var(--adm-border-2) !important; }
        {{ $admDark }} tbody tr:hover { background:var(--adm-border-2) !important; }
        {{ $admDark }} .text-gray-800, {{ $admDark }} .text-gray-900, {{ $admDark }} .text-gray-700 { color:var(--adm-text) !important; }
        {{ $admDark }} .text-gray-600, {{ $admDark }} .text-gray-500 { color:var(--adm-text-2) !important; }
        {{ $admDark }} .text-gray-400, {{ $admDark }} .text-gray-300 { color:var(--adm-text-3) !important; }
        {{ $admDark }} .border-gray-300, {{ $admDark }} .border-gray-200, {{ $admDark }} .border-gray-100 { border-color:var(--adm-border) !important; }
        {{ $admDark }} .bg-red-50 { background:var(--adm-red-bg) !important; }
        {{ $admDark }} .bg-green-50 { background:var(--adm-green-bg) !important; }
        {{ $admDark }} .bg-amber-50 { background:var(--adm-amber-bg) !important; }
        /* cores hex inline (telas antigas) — remapeadas na familia escura */
        {{ $admDark }} [style*="color: #111827"],{{ $admDark }} [style*="color:#111827"],
        {{ $admDark }} [style*="color: #0F172A"],{{ $admDark }} [style*="color:#0F172A"],
        {{ $admDark }} [style*="color: #1F2937"],{{ $admDark }} [style*="color:#1F2937"],
        {{ $admDark }} [style*="color: #1a1a1a"],{{ $admDark }} [style*="color:#1a1a1a"] { color:var(--adm-text) !important; }
        {{ $admDark }} [style*="color: #374151"],{{ $admDark }} [style*="color:#374151"],
        {{ $admDark }} [style*="color: #4B5563"],{{ $admDark }} [style*="color:#4B5563"],
        {{ $admDark }} [style*="color: #6B7280"],{{ $admDark }} [style*="color:#6B7280"] { color:var(--adm-text-2) !important; }
        {{ $admDark }} [style*="color: #9CA3AF"],{{ $admDark }} [style*="color:#9CA3AF"],
        {{ $admDark }} [style*="color: #D1D5DB"],{{ $admDark }} [style*="color:#D1D5DB"] { color:var(--adm-text-3) !important; }
        {{ $admDark }} [style*="color: #065F46"],{{ $admDark }} [style*="color:#065F46"],
        {{ $admDark }} [style*="color: #A7E0C7"],{{ $admDark }} [style*="color:#A7E0C7"] { color:var(--adm-green) !important; }
        {{ $admDark }} [style*="color: #991B1B"],{{ $admDark }} [style*="color:#991B1B"],
        {{ $admDark }} [style*="color: #B42318"],{{ $admDark }} [style*="color:#B42318"],
        {{ $admDark }} [style*="color: #DC2626"],{{ $admDark }} [style*="color:#DC2626"],
        {{ $admDark }} [style*="color: #EF4444"],{{ $admDark }} [style*="color:#EF4444"] { color:var(--adm-red) !important; }
        {{ $admDark }} [style*="color: #92400E"],{{ $admDark }} [style*="color:#92400E"],
        {{ $admDark }} [style*="color: #B45309"],{{ $admDark }} [style*="color:#B45309"] { color:var(--adm-amber) !important; }
        {{ $admDark }} [style*="color: #004B8D"],{{ $admDark }} [style*="color:#004B8D"],
        {{ $admDark }} [style*="color: #1E40AF"],{{ $admDark }} [style*="color:#1E40AF"] { color:var(--adm-accent) !important; }
        /* fundos claros inline */
        {{ $admDark }} [style*="background: #fff"],{{ $admDark }} [style*="background:#fff"],
        {{ $admDark }} [style*="background: #FFFFFF"],{{ $admDark }} [style*="background:#FFFFFF"],
        {{ $admDark }} [style*="background: white"],{{ $admDark }} [style*="background:white"] { background:var(--adm-card) !important; }
        {{ $admDark }} [style*="background: #F3F4F6"],{{ $admDark }} [style*="background:#F3F4F6"],
        {{ $admDark }} [style*="background: #F9FAFB"],{{ $admDark }} [style*="background:#F9FAFB"],
        {{ $admDark }} [style*="background: #FAFAFA"],{{ $admDark }} [style*="background:#FAFAFA"],
        {{ $admDark }} [style*="background: #F8FAFC"],{{ $admDark }} [style*="background:#F8FAFC"] { background:var(--adm-border-2) !important; }
        {{ $admDark }} [style*="background: #ECFDF5"],{{ $admDark }} [style*="background:#ECFDF5"] { background:var(--adm-green-bg) !important; }
        {{ $admDark }} [style*="background: #FEF2F2"],{{ $admDark }} [style*="background:#FEF2F2"],
        {{ $admDark }} [style*="background: #FEE2E2"],{{ $admDark }} [style*="background:#FEE2E2"],
        {{ $admDark }} [style*="background: #FDECEC"],{{ $admDark }} [style*="background:#FDECEC"] { background:var(--adm-red-bg) !important; }
        {{ $admDark }} [style*="background: #FFFBEB"],{{ $admDark }} [style*="background:#FFFBEB"],
        {{ $admDark }} [style*="background: #FBF1DD"],{{ $admDark }} [style*="background:#FBF1DD"] { background:var(--adm-amber-bg) !important; }
        /* bordas claras inline */
        {{ $admDark }} [style*="#E5E7EB"],{{ $admDark }} [style*="#D1D5DB"] { border-color:var(--adm-border) !important; }
        {{ $admDark }} [style*="solid #F3F4F6"],{{ $admDark }} [style*="solid #f3f4f6"] { border-color:var(--adm-border-2) !important; }
        /* icone SVG com cor fixa */
        {{ $admDark }} [stroke="#DC2626"],{{ $admDark }} [stroke="#dc2626"] { stroke:var(--adm-red) !important; }
        {{ $admDark }} input, {{ $admDark }} select, {{ $admDark }} textarea { background:var(--adm-border-2); color:var(--adm-text); border-color:var(--adm-border); }
        {{ $admDark }} table thead tr { background:var(--adm-border-2) !important; }
        /* botão + menu de tema */
        #adm-theme-toggle { width:34px; height:34px; border-radius:9px; border:1px solid var(--adm-border); background:var(--adm-bg); cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--adm-text-2); }
        #adm-theme-toggle:hover { background:var(--adm-border-2); }
        .adm-theme-opt { width:100%; display:flex; align-items:center; gap:10px; padding:9px 10px; border:none; background:transparent; border-radius:8px; cursor:pointer; font-size:13px; color:var(--adm-text-2); text-align:left; }
        .adm-theme-opt:hover { background:var(--adm-border-2); }
        .adm-theme-opt.active { background:var(--adm-accent); color:#fff; font-weight:600; }
        .adm-theme-opt.active .adm-theme-check { display:block !important; }
        .adm-theme-sw { width:16px; height:16px; border-radius:5px; flex-shrink:0; border:1px solid rgba(128,128,128,.4); }
        .adm-theme-sw[data-sw=light]    { background:linear-gradient(135deg,#F4F6FB 50%,#3B82F6 50%); }
        .adm-theme-sw[data-sw=dark]     { background:linear-gradient(135deg,#0E1626 50%,#4D9FFF 50%); }
        .adm-theme-sw[data-sw=slate]    { background:linear-gradient(135deg,#272C37 50%,#6CA8FF 50%); }
        .adm-theme-sw[data-sw=contrast] { background:linear-gradient(135deg,#000 50%,#6CB6FF 50%); }
    </style>
    <script>
        (function () {
            var t = localStorage.getItem('admin-theme');
            if (t && t !== 'light') document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>

@php
    $admNav = [
        ['key'=>'dashboard',       'route'=>'admin.dashboard',          'label'=>'Dashboard',          'icon'=>'<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>'],
        ['key'=>'escolas',         'route'=>'admin.schools.index',      'label'=>'Cadastro de Escolas', 'icon'=>'<path d="M3 21h18M5 21V8l7-4 7 4v13M9 21v-5h6v5"/><path d="M9 10h.01M15 10h.01"/>'],
        ['key'=>'financeiro',      'route'=>'admin.invoices.index',     'label'=>'Financeiro',          'icon'=>'<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
        ['key'=>'comunicados',     'route'=>'admin.announcements.index','label'=>'Comunicados',         'icon'=>'<path d="M3 11l15-5v13L3 15v-4z"/><path d="M11.5 17.5a3 3 0 01-5.7-1.3"/>'],
        ['key'=>'relatorios',      'route'=>'admin.reports.index',      'label'=>'Relatórios',          'icon'=>'<path d="M3 3v18h18"/><path d="M7 15l3-4 3 3 5-7"/>'],
        ['key'=>'administradores', 'route'=>'admin.admins.index',       'label'=>'Administradores',     'icon'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 11l2 2 4-4"/>'],
        ['key'=>'logs',            'route'=>'admin.logs.index',         'label'=>'Logs / Auditoria',    'icon'=>'<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>'],
    ];
    $admUser = auth('admin')->user();
@endphp

<div style="display:flex; min-height:100vh;">

    {{-- Sidebar --}}
    <aside style="width:248px; background:var(--adm-side); display:flex; flex-direction:column; min-height:100vh; position:fixed; top:0; left:0; z-index:40;">
        <div style="padding:22px 20px 18px; border-bottom:1px solid rgba(255,255,255,0.07);">
            <div style="display:flex; align-items:center; gap:11px;">
                <div style="width:38px; height:38px; background:var(--adm-accent); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/><path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/></svg>
                </div>
                <div>
                    <div style="font-size:15px; font-weight:800; color:#fff; letter-spacing:.4px;">ÁTRIO</div>
                    <div style="font-size:10px; color:rgba(255,255,255,0.38); letter-spacing:1.5px; text-transform:uppercase;">Gestão SaaS</div>
                </div>
            </div>
        </div>

        <nav style="flex:1; padding:14px 12px; overflow-y:auto;">
            @foreach($admNav as $item)
                @continue(! \Illuminate\Support\Facades\Route::has($item['route']))
                @continue($admUser && ! $admUser->canAccess($item['key']))
                @php $active = request()->routeIs($item['route']) || request()->routeIs(str_replace('.index','',$item['route']).'.*'); @endphp
                <a href="{{ route($item['route']) }}" class="adm-nav-item {{ $active ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div style="padding:14px 12px; border-top:1px solid rgba(255,255,255,0.07);">
            <div style="padding:6px 12px; margin-bottom:4px;">
                <p style="font-size:13px; font-weight:600; color:rgba(255,255,255,0.82); margin:0;">{{ auth('admin')->user()->name }}</p>
                <p style="font-size:11px; color:rgba(255,255,255,0.32); margin:0;">Super Admin</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        style="width:100%; display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:9px; border:none; background:none; cursor:pointer; font-size:13px; color:#F87171; text-align:left;"
                        onmouseover="this.style.background='rgba(248,113,113,0.1)'" onmouseout="this.style.background='transparent'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <div style="margin-left:248px; flex:1; display:flex; flex-direction:column;">
        <header style="background:var(--adm-card); border-bottom:1px solid var(--adm-border); padding:0 32px; height:62px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:30;">
            <span style="font-size:16px; font-weight:700; color:var(--adm-text);">@yield('title')</span>
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="position:relative;">
                    <button id="adm-theme-toggle" type="button" onclick="toggleAdmThemeMenu(event)" title="Tema" aria-haspopup="true" aria-expanded="false">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5L19 19M19 5l-1.5 1.5M6.5 17.5L5 19"/>
                        </svg>
                    </button>
                    <div id="adm-theme-menu" role="menu" style="display:none; position:absolute; right:0; top:42px; background:var(--adm-card); border:1px solid var(--adm-border); border-radius:12px; box-shadow:0 12px 32px rgba(0,0,0,.28); padding:6px; min-width:188px; z-index:60;">
                        <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--adm-text-3); padding:6px 10px 8px;">Tema</div>
                        @foreach(['light'=>'Claro','dark'=>'Escuro','slate'=>'Escuro suave','contrast'=>'Alto contraste'] as $val => $label)
                        <button type="button" class="adm-theme-opt" data-theme-val="{{ $val }}" onclick="setAdminTheme('{{ $val }}')">
                            <span class="adm-theme-sw" data-sw="{{ $val }}"></span>
                            <span style="flex:1;">{{ $label }}</span>
                            <svg class="adm-theme-check" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="display:none;"><path d="M20 6L9 17l-5-5"/></svg>
                        </button>
                        @endforeach
                    </div>
                </div>
                <span style="font-size:11px; font-weight:600; color:var(--adm-text-3); background:var(--adm-border-2); padding:5px 12px; border-radius:20px; letter-spacing:.3px;">PAINEL ADMINISTRATIVO</span>
            </div>
        </header>

        <main style="flex:1; padding:28px 32px;">
            @if(session('success'))
                <div style="background:var(--adm-green-bg); border:1px solid #A7E0C7; color:var(--adm-green); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background:var(--adm-red-bg); border:1px solid #F4B5AE; color:var(--adm-red); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:20px;">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background:var(--adm-red-bg); border:1px solid #F4B5AE; color:var(--adm-red); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:20px;">
                    <strong style="display:block; margin-bottom:4px;">Não foi possível salvar:</strong>
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@include('layouts.partials.image-cropper')
<script>
var ADM_THEMES = ['light', 'dark', 'slate', 'contrast'];
function admCurrentTheme() { return document.documentElement.getAttribute('data-theme') || 'light'; }
function markActiveAdmTheme() {
    var t = admCurrentTheme();
    document.querySelectorAll('.adm-theme-opt').forEach(function (o) {
        o.classList.toggle('active', o.getAttribute('data-theme-val') === t);
    });
}
function toggleAdmThemeMenu(e) {
    e.stopPropagation();
    var m = document.getElementById('adm-theme-menu');
    var open = m.style.display === 'block';
    m.style.display = open ? 'none' : 'block';
    document.getElementById('adm-theme-toggle').setAttribute('aria-expanded', String(!open));
    if (!open) markActiveAdmTheme();
}
function setAdminTheme(theme) {
    if (ADM_THEMES.indexOf(theme) === -1) theme = 'light';
    if (theme === 'light') document.documentElement.removeAttribute('data-theme');
    else document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('admin-theme', theme);
    markActiveAdmTheme();
    document.getElementById('adm-theme-menu').style.display = 'none';
}
document.addEventListener('click', function (e) {
    var m = document.getElementById('adm-theme-menu'), b = document.getElementById('adm-theme-toggle');
    if (!m || m.style.display !== 'block') return;
    if (!m.contains(e.target) && !b.contains(e.target)) m.style.display = 'none';
});
document.addEventListener('DOMContentLoaded', markActiveAdmTheme);
</script>
</body>
</html>
