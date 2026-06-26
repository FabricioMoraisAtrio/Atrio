@extends('admin.layouts.app')
@section('title', 'Comunicados')

@section('content')
@php
    $inp = 'width:100%; border:1px solid var(--adm-border); border-radius:8px; padding:8px 10px; font-size:13px; color:var(--adm-text); box-sizing:border-box;';
    $lbl = 'display:block; font-size:11px; font-weight:600; color:var(--adm-text-3); margin-bottom:5px;';
@endphp

<div style="display:grid; grid-template-columns:360px 1fr; gap:18px; align-items:start;">

    {{-- Form criar/editar --}}
    <div class="adm-card" style="padding:20px;">
        <p id="form-titulo" style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 16px;">Novo comunicado</p>
        <form id="comunicado-form" method="POST" action="{{ route('admin.announcements.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            <div style="margin-bottom:12px;">
                <label style="{{ $lbl }}">Título</label>
                <input type="text" name="title" id="f-title" required style="{{ $inp }}">
            </div>
            <div style="margin-bottom:12px;">
                <label style="{{ $lbl }}">Mensagem</label>
                <textarea name="body" id="f-body" rows="4" required style="{{ $inp }} resize:vertical;"></textarea>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                <div>
                    <label style="{{ $lbl }}">Tipo</label>
                    <select name="level" id="f-level" style="{{ $inp }}">
                        <option value="info">Informativo</option>
                        <option value="warning">Aviso</option>
                    </select>
                </div>
                <div>
                    <label style="{{ $lbl }}">Destino</label>
                    <select name="audience" id="f-audience" style="{{ $inp }}" onchange="document.getElementById('f-school-wrap').style.display = this.value==='school'?'block':'none'">
                        <option value="all">Todas as escolas</option>
                        <option value="school">Escola específica</option>
                    </select>
                </div>
            </div>
            <div id="f-school-wrap" style="display:none; margin-bottom:12px;">
                <label style="{{ $lbl }}">Escola</label>
                <select name="school_id" id="f-school" style="{{ $inp }}">
                    @foreach($schools as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                <div><label style="{{ $lbl }}">Início (opcional)</label><input type="date" name="starts_at" id="f-starts" style="{{ $inp }}"></div>
                <div><label style="{{ $lbl }}">Fim (opcional)</label><input type="date" name="ends_at" id="f-ends" style="{{ $inp }}"></div>
            </div>
            <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:var(--adm-text-2); margin-bottom:16px;">
                <input type="checkbox" name="active" id="f-active" value="1" checked> Ativo
            </label>
            <div style="display:flex; gap:8px;">
                <button type="submit" class="adm-btn adm-btn-primary" style="flex:1; justify-content:center;">Publicar</button>
                <button type="button" onclick="resetComunicadoForm()" class="adm-btn adm-btn-ghost" id="btn-cancelar" style="display:none;">Cancelar</button>
            </div>
        </form>
    </div>

    {{-- Lista --}}
    <div style="display:flex; flex-direction:column; gap:12px;">
        @forelse($announcements as $a)
        @php $isWarn = $a->level === 'warning'; @endphp
        <div class="adm-card" style="padding:16px 18px; border-left:4px solid {{ $isWarn ? 'var(--adm-amber)' : 'var(--adm-accent)' }};">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                <div style="min-width:0;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                        <span style="font-size:14px; font-weight:700; color:var(--adm-text);">{{ $a->title }}</span>
                        <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; color:{{ $isWarn ? 'var(--adm-amber)' : 'var(--adm-accent)' }}; background:{{ $isWarn ? 'var(--adm-amber-bg)' : '#E8F1FE' }};">{{ $isWarn ? 'AVISO' : 'INFO' }}</span>
                        @if(!$a->active)<span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:20px; color:var(--adm-text-3); background:var(--adm-border-2);">INATIVO</span>@endif
                    </div>
                    <p style="font-size:13px; color:var(--adm-text-2); margin:0 0 6px; white-space:pre-wrap;">{{ $a->body }}</p>
                    <p style="font-size:11px; color:var(--adm-text-3); margin:0;">
                        {{ $a->audience === 'all' ? 'Todas as escolas' : ('Escola: ' . ($a->school?->name ?? '—')) }}
                        @if($a->starts_at) · de {{ $a->starts_at->format('d/m/Y') }} @endif
                        @if($a->ends_at) até {{ $a->ends_at->format('d/m/Y') }} @endif
                    </p>
                </div>
                <div style="display:flex; gap:6px; flex-shrink:0;">
                    <button type="button" class="adm-btn adm-btn-ghost" style="padding:6px 10px;"
                            onclick='editComunicado(@json($a))'>Editar</button>
                    <form method="POST" action="{{ route('admin.announcements.toggle', $a) }}">@csrf
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 10px;">{{ $a->active ? 'Desativar' : 'Ativar' }}</button>
                    </form>
                    <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Remover este comunicado?')">@csrf @method('DELETE')
                        <button type="submit" class="adm-btn adm-btn-ghost" style="padding:6px 10px; color:var(--adm-red);">Excluir</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="adm-card" style="padding:40px; text-align:center; color:var(--adm-text-3); font-style:italic;">Nenhum comunicado ainda.</div>
        @endforelse
    </div>
</div>

<script>
function editComunicado(a) {
    document.getElementById('form-titulo').textContent = 'Editar comunicado';
    document.getElementById('comunicado-form').action = '{{ url('superadmin/comunicados') }}/' + a.id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('f-title').value = a.title;
    document.getElementById('f-body').value = a.body;
    document.getElementById('f-level').value = a.level;
    document.getElementById('f-audience').value = a.audience;
    document.getElementById('f-school-wrap').style.display = a.audience === 'school' ? 'block' : 'none';
    if (a.school_id) document.getElementById('f-school').value = a.school_id;
    document.getElementById('f-starts').value = a.starts_at ? a.starts_at.substring(0,10) : '';
    document.getElementById('f-ends').value = a.ends_at ? a.ends_at.substring(0,10) : '';
    document.getElementById('f-active').checked = !!a.active;
    document.getElementById('btn-cancelar').style.display = 'inline-flex';
    window.scrollTo({top:0, behavior:'smooth'});
}
function resetComunicadoForm() {
    var f = document.getElementById('comunicado-form');
    f.reset();
    f.action = '{{ route('admin.announcements.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('form-titulo').textContent = 'Novo comunicado';
    document.getElementById('f-school-wrap').style.display = 'none';
    document.getElementById('btn-cancelar').style.display = 'none';
}
</script>
@endsection
