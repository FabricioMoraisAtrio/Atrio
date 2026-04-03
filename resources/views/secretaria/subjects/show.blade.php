@extends('layouts.app')
@section('title', $subject->name)

@section('content')
<div style="margin-bottom:24px;">
    <a href="{{ route('secretaria.subjects.index') }}"
       style="font-size:13px;color:#9CA3AF;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para matérias
    </a>
    <div style="display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:#111827;margin:0 0 4px;">{{ $subject->name }}</h1>
            <p style="font-size:13px;color:#9CA3AF;margin:0;">
                {{ $subject->label_responsavel }}
                · <span style="{{ $subject->tipo === 'regente' ? 'color:#009C8C' : 'color:#004B8D' }}; font-weight:600;">{{ $subject->tipo === 'regente' ? 'Regente' : 'Disciplina' }}</span>
            </p>
        </div>
        <a href="{{ route('secretaria.subjects.edit', $subject) }}"
           style="font-size:13px;color:#6B7280;text-decoration:none;border:1px solid #E5E7EB;padding:8px 16px;border-radius:8px;">
            Editar matéria
        </a>
    </div>
</div>

@if(session('success'))
    <div style="background:#ECFDF5;border:1px solid #6EE7B7;color:#065F46;font-size:13px;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

{{-- Metas do inventário --}}
<div style="background:#fff;border-radius:12px;border:1px solid #F3F4F6;padding:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <h3 style="font-size:15px;font-weight:700;color:#111827;margin:0 0 4px;">Metas do Inventário</h3>
            <p style="font-size:12px;color:#9CA3AF;margin:0;">
                Defina as metas/objetivos que aparecerão no PEI para esta matéria.<br>
                Cada linha é uma meta. Arraste para reordenar.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('secretaria.subjects.saveItems', $subject) }}" id="formMetas">
        @csrf

        <div id="metas-list" style="display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
            @foreach($subject->inventoryItems as $item)
                <div class="meta-row" style="display:flex;align-items:center;gap:8px;">
                    <div style="cursor:grab;color:#D1D5DB;flex-shrink:0;" title="Arrastar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/>
                            <circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/>
                            <circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="15" cy="19" r="1" fill="currentColor"/>
                        </svg>
                    </div>
                    <input type="text" name="metas[]" value="{{ $item->meta }}" required
                           style="flex:1;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;"
                           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    <button type="button" onclick="removerLinha(this)"
                            style="color:#EF4444;background:none;border:none;cursor:pointer;flex-shrink:0;padding:4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>

        <button type="button" onclick="adicionarMeta()"
                style="background:#F9FAFB;color:#374151;border:1px dashed #D1D5DB;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;margin-bottom:20px;display:flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Adicionar meta
        </button>

        <div style="display:flex;gap:12px;">
            <button type="submit"
                    style="background:#004B8D;color:white;border:none;padding:11px 24px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                Salvar metas
            </button>
        </div>
    </form>
</div>

<script>
function adicionarMeta() {
    const row = document.createElement('div');
    row.className = 'meta-row';
    row.style.cssText = 'display:flex;align-items:center;gap:8px;';
    row.innerHTML = `
        <div style="cursor:grab;color:#D1D5DB;flex-shrink:0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/>
                <circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/>
                <circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="15" cy="19" r="1" fill="currentColor"/>
            </svg>
        </div>
        <input type="text" name="metas[]" required placeholder="Nova meta..."
               style="flex:1;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;"
               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        <button type="button" onclick="removerLinha(this)"
                style="color:#EF4444;background:none;border:none;cursor:pointer;flex-shrink:0;padding:4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    `;
    document.getElementById('metas-list').appendChild(row);
    row.querySelector('input').focus();
}

function removerLinha(btn) {
    btn.closest('.meta-row').remove();
}
</script>
@endsection
