{{-- Sino de avisos (comunicados do superadmin) — ao lado do botão de tema. --}}
@auth
@php
    $bellAvisos = \App\Models\Announcement::activeNow()
        ->forSchool((int) (auth()->user()->school_id ?? 0))
        ->latest()->take(12)->get();
@endphp
<div style="position:relative;">
    <button id="bell-toggle" type="button" onclick="toggleBell(event)" title="Avisos" aria-haspopup="true" aria-expanded="false"
            style="width:34px; height:34px; border-radius:8px; border:1px solid var(--border); background:var(--bg-subtle); cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--text-3); position:relative;">
        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
        @if($bellAvisos->count())
        <span style="position:absolute; top:-5px; right:-5px; min-width:16px; height:16px; padding:0 4px; border-radius:8px; background:var(--danger); color:#fff; font-size:10px; font-weight:700; line-height:16px; text-align:center; box-sizing:border-box;">{{ $bellAvisos->count() > 9 ? '9+' : $bellAvisos->count() }}</span>
        @endif
    </button>
    <div id="bell-menu" role="menu" style="display:none; position:absolute; right:0; top:44px; width:330px; max-width:88vw; max-height:62vh; overflow-y:auto; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.18); z-index:60;">
        <div style="padding:12px 16px; border-bottom:1px solid var(--border-sub); font-size:13px; font-weight:700; color:var(--text-1);">Avisos</div>
        @forelse($bellAvisos as $a)
        @php $warn = $a->level === 'warning'; @endphp
        <div style="padding:12px 16px; border-bottom:1px solid var(--border-sub); border-left:3px solid {{ $warn ? 'var(--warning)' : 'var(--accent)' }};">
            <div style="display:flex; align-items:center; gap:7px; margin-bottom:4px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $warn ? 'var(--warning)' : 'var(--accent-text)' }}" stroke-width="2" style="flex-shrink:0;">
                    @if($warn)<path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>@else<circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/>@endif
                </svg>
                <span style="font-size:13px; font-weight:700; color:var(--text-1);">{{ $a->title }}</span>
            </div>
            <div style="font-size:12.5px; color:var(--text-2); white-space:pre-wrap; line-height:1.45;">{{ $a->body }}</div>
            <div style="font-size:10.5px; color:var(--text-4); margin-top:5px;">{{ $a->created_at?->format('d/m/Y') }}</div>
        </div>
        @empty
        <div style="padding:26px 16px; text-align:center; font-size:13px; color:var(--text-4); font-style:italic;">Nenhum aviso no momento.</div>
        @endforelse
    </div>
</div>
<script>
function toggleBell(e) {
    e.stopPropagation();
    var m = document.getElementById('bell-menu');
    var open = m.style.display === 'block';
    m.style.display = open ? 'none' : 'block';
    document.getElementById('bell-toggle').setAttribute('aria-expanded', String(!open));
}
document.addEventListener('click', function (e) {
    var m = document.getElementById('bell-menu'), b = document.getElementById('bell-toggle');
    if (!m || m.style.display !== 'block') return;
    if (!m.contains(e.target) && !b.contains(e.target)) { m.style.display = 'none'; b.setAttribute('aria-expanded', 'false'); }
});
</script>
@endauth
