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
    </style>
</head>
<body>

@php
    $admNav = [
        ['route'=>'admin.dashboard',          'label'=>'Dashboard',          'icon'=>'<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>'],
        ['route'=>'admin.schools.index',      'label'=>'Cadastro de Escolas', 'icon'=>'<path d="M3 21h18M5 21V8l7-4 7 4v13M9 21v-5h6v5"/><path d="M9 10h.01M15 10h.01"/>'],
        ['route'=>'admin.invoices.index',     'label'=>'Financeiro',          'icon'=>'<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>'],
        ['route'=>'admin.announcements.index','label'=>'Comunicados',         'icon'=>'<path d="M3 11l15-5v13L3 15v-4z"/><path d="M11.5 17.5a3 3 0 01-5.7-1.3"/>'],
        ['route'=>'admin.reports.index',      'label'=>'Relatórios',          'icon'=>'<path d="M3 3v18h18"/><path d="M7 15l3-4 3 3 5-7"/>'],
        ['route'=>'admin.admins.index',       'label'=>'Administradores',     'icon'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 11l2 2 4-4"/>'],
        ['route'=>'admin.logs.index',         'label'=>'Logs / Auditoria',    'icon'=>'<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>'],
    ];
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
            <span style="font-size:11px; font-weight:600; color:var(--adm-text-3); background:var(--adm-border-2); padding:5px 12px; border-radius:20px; letter-spacing:.3px;">PAINEL ADMINISTRATIVO</span>
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
</body>
</html>
