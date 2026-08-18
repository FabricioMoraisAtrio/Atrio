<!DOCTYPE html>
<html lang="pt-BR"@auth @if(auth()->user()->theme) data-theme="{{ auth()->user()->theme }}"@endif @endauth>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Átrio — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.partials.theme-head')
    <link rel="icon" type="image/png" href="{{ asset('favicon-32x32.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
<body class="min-h-screen">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside style="width: clamp(256px, 16vw, 300px); border-right: 1px solid var(--border); display: flex; flex-direction: column; height: 100vh; max-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40;">

        {{-- Logo --}}
        <div style="padding: 24px 20px 20px; border-bottom: 1px solid var(--border-sub);">
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
                            <div style="font-size: 13px; font-weight: 700; color: var(--accent-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $school->name }}</div>
                            <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                        </div>
                    @else
                        <div style="width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                            </svg>
                        </div>
                        <div style="min-width: 0;">
                            <div style="font-size: 15px; font-weight: 700; color: var(--accent-text); letter-spacing: 0.5px;">ÁTRIO</div>
                            <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">
                                {{ $school?->name ?? 'Portal Institucional' }}
                            </div>
                        </div>
                    @endif
                @else
                    <div style="width: 36px; height: 36px; background: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                            <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 15px; font-weight: 700; color: var(--accent-text); letter-spacing: 0.5px;">ÁTRIO</div>
                        <div style="font-size: 10px; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase;">Portal Institucional</div>
                    </div>
                @endauth
            </a>
        </div>

        <nav style="flex: 1; padding: 16px 12px; overflow-y: auto; display: flex; flex-direction: column;">
            @auth
                @php
                    $school     = auth()->user()->school;
                    $hasModule  = fn(string $k) => !$school || $school->hasModule($k);
                    // Permissão de acesso à rotina (visibilidade no menu), por perfil.
                    $rotinaPermMap = [
                        'painel' => 'rotina.painel', 'turmas' => 'rotina.turmas', 'alunos' => 'rotina.alunos',
                        'documentos' => 'rotina.documentos', 'adaptacoes' => 'rotina.adaptacoes',
                        'reunioes' => 'rotina.reunioes', 'linha_do_tempo' => 'rotina.linha_do_tempo',
                        'seletividade' => 'rotina.seletividade', 'usuarios' => 'rotina.usuarios',
                    ];
                    // O filtro de rotina vale APENAS para perfis customizados (s{escola}_*)
                    // e só quando as permissões já existem (evita esconder tudo antes do migrate).
                    // Papéis internos (admin/coordenador/orientador/professor) veem tudo por módulo.
                    // O filtro de rotina vale para o professor e para perfis customizados
                    // (admin/coordenador/orientador veem tudo). Só quando as permissões existem.
                    $ehCustom      = auth()->user()->roles->contains(fn ($r) => str_starts_with($r->name, 's' . session('school_id') . '_'));
                    $filtrarRotina = (auth()->user()->hasRole('professor') || $ehCustom)
                        && \Spatie\Permission\Models\Permission::where('name', 'rotina.painel')->exists();
                    $podeRotina = function (array $item) use ($rotinaPermMap, $filtrarRotina) {
                        if (! $filtrarRotina) {
                            return true;
                        }
                        $p = $item['perm'] ?? ($rotinaPermMap[$item['module'] ?? ''] ?? null);
                        return ! $p || auth()->user()->can($p);
                    };
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
                            ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),        'module' => 'turmas'],
                            ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos'],
                            ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos', 'badge' => $pendentesCount ?: null],
                            ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                            ['route' => 'secretaria.rotinas.reunioes',           'icon' => 'rotina',  'label' => 'Reuniões / Atas', 'module' => 'reunioes'],
                            ['route' => 'secretaria.rotinas.linha-do-tempo',     'icon' => 'rotina',  'label' => 'Linha do Tempo', 'module' => 'linha_do_tempo'],
                            ['route' => 'secretaria.seletividade.index',         'icon' => 'food',    'label' => 'Jornada Alimentar', 'module' => 'seletividade'],
                        ];
                        $footerItems = [
                            ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',            'module' => 'usuarios'],
                            [
                                'route'    => 'secretaria.config.index',
                                'icon'     => 'config',
                                'label'    => 'Configurações',
                                'active'   => 'secretaria.config.*',
                                'module'   => 'configuracoes',
                            ],
                            ['route' => 'secretaria.logs.index', 'icon' => 'log', 'label' => 'Registro de Acessos', 'module' => 'configuracoes', 'admin_only' => true],
                        ];
                        // Coordenador/orientador não vê Configurações nem Logs
                        if (!$isAdmin) {
                            $footerItems = array_filter($footerItems, fn($i) =>
                                ($i['module'] ?? '') !== 'configuracoes' && empty($i['admin_only'])
                            );
                        }
                    @endphp
                @endhasanyrole

                @hasrole('professor')
                    @php
                        $items = [
                            ['route' => 'professor.dashboard',    'icon' => 'home',     'label' => 'Início'],
                            ['route' => 'professor.painel',       'icon' => 'grid',     'label' => 'Painel de Acompanhamento', 'perm' => 'rotina.painel'],
                            ['route' => 'professor.turmas.index', 'icon' => 'academic', 'label' => 'Turmas', 'perm' => 'rotina.turmas'],
                        ];
                        $footerItems = [];
                    @endphp
                @endhasrole

                @php
                    if (!isset($items) && auth()->check()) {
                        $schoolId = session('school_id');
                        if (auth()->user()->roles()->where('name', 'like', "s{$schoolId}_%")->exists()) {
                            $items = [
                                ['route' => 'secretaria.dashboard',                  'icon' => 'home',    'label' => 'Início'],
                                ['route' => 'secretaria.painel',                     'icon' => 'grid',    'label' => 'Painel de Acompanhamento', 'module' => 'painel'],
                                ['route' => 'secretaria.turmas.index',               'icon' => 'academic','label' => term('turmas'),       'module' => 'turmas'],
                                ['route' => 'secretaria.alunos.index',               'icon' => 'users',   'label' => 'Cadastro de ' . term('alunos'), 'module' => 'alunos'],
                                ['route' => 'secretaria.rotinas.documentos.index',   'icon' => 'rotina',  'label' => 'Documentos de Inclusão', 'module' => 'documentos'],
                                ['route' => 'secretaria.rotinas.adaptacoes',         'icon' => 'rotina',  'label' => 'Adaptações para Prova', 'module' => 'adaptacoes'],
                                ['route' => 'secretaria.rotinas.reunioes',           'icon' => 'rotina',  'label' => 'Reuniões / Atas', 'module' => 'reunioes'],
                                ['route' => 'secretaria.rotinas.linha-do-tempo',     'icon' => 'rotina',  'label' => 'Linha do Tempo', 'module' => 'linha_do_tempo'],
                                ['route' => 'secretaria.seletividade.index',         'icon' => 'food',    'label' => 'Jornada Alimentar', 'module' => 'seletividade'],
                            ];
                            $footerItems = [
                                ['route' => 'secretaria.usuarios.index',             'icon' => 'user',    'label' => 'Usuários',           'module' => 'usuarios'],
                            ];
                        }
                    }
                @endphp

                @foreach($items ?? [] as $item)
                    @continue(isset($item['module']) && !$hasModule($item['module']))
                    @continue(! $podeRotina($item))
                    @include('layouts.partials.sidebar-item', ['item' => $item, 'hasModule' => $hasModule])
                @endforeach

                @if(!empty($footerItems))
                    <div style="margin-top: auto;">
                        @foreach($footerItems as $item)
                            @continue(isset($item['module']) && !$hasModule($item['module']))
                            @continue(! $podeRotina($item))
                            @include('layouts.partials.sidebar-item', ['item' => $item, 'hasModule' => $hasModule])
                        @endforeach
                    </div>
                @endif
            @endauth
        </nav>
    </aside>

    <div style="margin-left: clamp(256px, 16vw, 300px); flex: 1; display: flex; flex-direction: column; min-height: 100vh;">

        <header style="border-bottom: 1px solid var(--border); padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                @hasSection('breadcrumb')
                    @yield('breadcrumb')
                @else
                    <span style="color: var(--text-1); font-weight: 500;">@yield('title')</span>
                @endif
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                @include('layouts.partials.notifications-bell')
                @include('layouts.partials.theme-switcher')
                @include('layouts.partials.user-menu')
            </div>
        </header>

        <main style="flex: 1; padding: 32px;">
            @if(session('success'))
                <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
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
        <div style="width:48px;height:48px;border-radius:14px;background:var(--danger-bg);
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
                           background:var(--danger-solid);color:white;font-size:13px;font-weight:600;cursor:pointer;">
                Remover
            </button>
        </div>
    </div>
</div>

<script>
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
</script>
@include('layouts.partials.theme-scripts')
@include('layouts.partials.image-cropper')
</body>
</html>