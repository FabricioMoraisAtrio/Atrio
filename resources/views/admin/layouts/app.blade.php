<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átrio Admin — @yield('title')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: #F8FAFC; min-height: 100vh; font-family: sans-serif;">

<div style="display: flex; min-height: 100vh;">
    

    {{-- Sidebar Admin --}}
    <aside style="width: 240px; background: #0F172A; display: flex; flex-direction: column; min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 40;">

        <div style="padding: 24px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; background: #004B8D; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        
                        <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.9"/>
                        <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size: 15px; font-weight: 700; color: #fff; letter-spacing: 0.5px;">ÁTRIO</div>
                    <div style="font-size: 10px; color: rgba(255,255,255,0.4); letter-spacing: 1px; text-transform: uppercase;">Admin</div>
                </div>
            </div>
        </div>

        <nav style="flex: 1; padding: 16px 12px;">
            @php
                $adminItems = [
                    ['route' => 'admin.dashboard',     'label' => 'Dashboard',  'icon' => 'grid'],
                    ['route' => 'admin.schools.index',  'label' => 'Escolas',    'icon' => 'academic'],
                ];
            @endphp
            @foreach($adminItems as $item)
                @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*'); @endphp
                <a href="{{ route($item['route']) }}"
                   style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; margin-bottom: 2px; font-size: 14px; font-weight: 500; text-decoration: none;
                       {{ $isActive ? 'background: rgba(255,255,255,0.1); color: #fff;' : 'color: rgba(255,255,255,0.5);' }}">
                    @if($item['icon'] === 'grid')
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                        </svg>
                    @else
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    @endif
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div style="padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.08);">
            <div style="padding: 8px 12px; margin-bottom: 4px;">
                <p style="font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.7); margin: 0;">{{ auth('admin')->user()->name }}</p>
                <p style="font-size: 11px; color: rgba(255,255,255,0.3); margin: 0;">Super Admin</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; border: none; background: none; cursor: pointer; font-size: 13px; color: #EF4444; text-align: left;"
                        onmouseover="this.style.background='rgba(239,68,68,0.1)'"
                        onmouseout="this.style.background='transparent'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <div style="margin-left: 240px; flex: 1; display: flex; flex-direction: column;">
        <header style="background: #fff; border-bottom: 1px solid #E5E7EB; padding: 0 32px; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 30;">
            <span style="font-size: 14px; font-weight: 500; color: #111827;">@yield('title')</span>
            <span style="font-size: 12px; color: #9CA3AF; background: #F3F4F6; padding: 4px 10px; border-radius: 20px;">
                Painel Administrativo
            </span>
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

</body>
</html>