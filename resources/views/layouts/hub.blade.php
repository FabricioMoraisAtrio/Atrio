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

        @include('layouts.partials.theme-switcher')

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

@include('layouts.partials.theme-scripts')
</body>
</html>
