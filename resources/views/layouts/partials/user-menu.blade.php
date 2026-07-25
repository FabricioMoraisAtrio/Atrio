@auth
@php
    $roleLabels = [
        'admin'       => 'Administrador',
        'coordenador' => 'Coordenação',
        'orientador'  => 'Orientação Pedagógica',
        'professor'   => 'Professor',
    ];
    $uRole      = auth()->user()->getRoleNames()->first();
    $uRoleLabel = $roleLabels[$uRole] ?? null;
    if (! $uRoleLabel && $uRole && str_starts_with($uRole, 's')) {
        $uRoleLabel = \App\Models\SchoolRole::where('spatie_role', $uRole)->value('name');
    }
    $uRoleLabel = $uRoleLabel ?? $uRole;
@endphp

<div style="position: relative;" id="user-menu">
    <button type="button" onclick="toggleUserMenu(event)"
            style="display: flex; align-items: center; gap: 8px; background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 5px 8px 5px 6px; cursor: pointer;">
        @if(auth()->user()->avatar)
            <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->avatar) }}"
                 style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
        @else
            <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--accent); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <span style="font-size: 13px; font-weight: 500; color: var(--text-1); max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            {{ auth()->user()->name }}
        </span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-4)" stroke-width="2" style="flex-shrink: 0;"><path d="M6 9l6 6 6-6"/></svg>
    </button>

    <div id="user-menu-dropdown"
         style="display: none; position: absolute; right: 0; top: calc(100% + 8px); width: 230px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 12px 32px rgba(0,0,0,0.14); padding: 8px; z-index: 60;">
        <div style="padding: 10px 12px 12px; border-bottom: 1px solid var(--border-sub); margin-bottom: 6px;">
            <div style="font-size: 13px; font-weight: 600; color: var(--text-1); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->name }}</div>
            <div style="font-size: 11px; color: var(--text-4); margin-top: 1px;">{{ $uRoleLabel }}</div>
        </div>
        <a href="{{ route('profile.edit') }}"
           style="display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; text-decoration: none; color: var(--text-2); font-size: 13px;"
           onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Meu Perfil
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; border: none; background: none; cursor: pointer; font-size: 13px; color: var(--danger); text-align: left;"
                    onmouseover="this.style.background='var(--danger-bg)'" onmouseout="this.style.background='transparent'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Sair
            </button>
        </form>
    </div>
</div>

<script>
    function toggleUserMenu(e) {
        e.stopPropagation();
        const d = document.getElementById('user-menu-dropdown');
        d.style.display = d.style.display === 'block' ? 'none' : 'block';
    }
    document.addEventListener('click', function (e) {
        const m = document.getElementById('user-menu');
        const d = document.getElementById('user-menu-dropdown');
        if (m && d && !m.contains(e.target)) d.style.display = 'none';
    });
</script>
@endauth
