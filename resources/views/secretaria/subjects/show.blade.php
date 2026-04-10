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

{{-- Legenda de categorias --}}
<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach([
        'academica'      => ['Objetivos Curriculares',      '#004B8D', '#E8F0F9'],
        'socioemocional' => ['Desenvolvimento Socioemocional',  '#007A6E', '#E6F5F4'],
        'global'         => ['Desenvolvimento Global',       '#6D28D9', '#F0EBF8'],
    ] as $key => [$label, $cor, $bg])
        <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $bg }};color:{{ $cor }};">
            <span style="width:8px;height:8px;border-radius:50%;background:{{ $cor }};flex-shrink:0;"></span>
            {{ $label }}
        </span>
    @endforeach
</div>

{{-- Metas do inventário --}}
<div style="background:#fff;border-radius:12px;border:1px solid #F3F4F6;padding:24px;">
    <div style="margin-bottom:20px;">
        <h3 style="font-size:15px;font-weight:700;color:#111827;margin:0 0 4px;">Metas do Inventário</h3>
        <p style="font-size:12px;color:#9CA3AF;margin:0;">
            Defina as metas que aparecerão no PEI para esta matéria e classifique cada uma em uma categoria.
        </p>
    </div>

    <form method="POST" action="{{ route('secretaria.subjects.saveItems', $subject) }}" id="formMetas">
        @csrf

        {{-- Cabeçalho da tabela --}}
        <div style="display:grid;grid-template-columns:28px 1fr 220px 32px;gap:8px;padding:0 4px 8px;border-bottom:1px solid #F3F4F6;margin-bottom:8px;">
            <div></div>
            <div style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;">Meta / Objetivo</div>
            <div style="font-size:11px;font-weight:600;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;">Categoria</div>
            <div></div>
        </div>

        <div id="metas-list" style="display:flex;flex-direction:column;gap:6px;margin-bottom:20px;">
            @foreach($subject->inventoryItems as $item)
                <div class="meta-row" style="display:grid;grid-template-columns:28px 1fr 220px 32px;gap:8px;align-items:center;">
                    <div style="cursor:grab;color:#D1D5DB;flex-shrink:0;" title="Arrastar">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/>
                            <circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/>
                            <circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="15" cy="19" r="1" fill="currentColor"/>
                        </svg>
                    </div>
                    <input type="text" name="metas[{{ $loop->index }}][texto]" value="{{ $item->meta }}" required
                           placeholder="Descreva a meta..."
                           style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;"
                           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    <select name="metas[{{ $loop->index }}][categoria]"
                            style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:9px 10px;font-size:12px;color:#374151;outline:none;background:#fff;cursor:pointer;"
                            onchange="colorirSelect(this)">
                        <option value="academica"      {{ $item->categoria === 'academica'      ? 'selected' : '' }}>Objetivos Curriculares</option>
                        <option value="socioemocional" {{ $item->categoria === 'socioemocional' ? 'selected' : '' }}>Desenvolvimento Socioemocional</option>
                        <option value="global"         {{ $item->categoria === 'global'         ? 'selected' : '' }}>Desenvolvimento Global</option>
                    </select>
                    <button type="button" onclick="removerLinha(this)"
                            style="color:#EF4444;background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
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

        <button type="submit"
                style="background:#004B8D;color:white;border:none;padding:11px 24px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
            Salvar metas
        </button>
    </form>
</div>

<script>
let metaCount = {{ $subject->inventoryItems->count() }};

const categoriaCores = {
    academica:      { border: '#004B8D', bg: '#E8F0F9' },
    socioemocional: { border: '#007A6E', bg: '#E6F5F4' },
    global:         { border: '#6D28D9', bg: '#F0EBF8' },
};

function colorirSelect(sel) {
    const c = categoriaCores[sel.value] || {};
    sel.style.borderColor = c.border || '#E5E7EB';
    sel.style.background  = c.bg || '#fff';
}

// Aplicar cores iniciais
document.querySelectorAll('select[name*="categoria"]').forEach(colorirSelect);

function adicionarMeta() {
    const idx = metaCount++;
    const row = document.createElement('div');
    row.className = 'meta-row';
    row.style.cssText = 'display:grid;grid-template-columns:28px 1fr 220px 32px;gap:8px;align-items:center;';
    row.innerHTML = `
        <div style="cursor:grab;color:#D1D5DB;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="5" r="1" fill="currentColor"/><circle cx="15" cy="5" r="1" fill="currentColor"/>
                <circle cx="9" cy="12" r="1" fill="currentColor"/><circle cx="15" cy="12" r="1" fill="currentColor"/>
                <circle cx="9" cy="19" r="1" fill="currentColor"/><circle cx="15" cy="19" r="1" fill="currentColor"/>
            </svg>
        </div>
        <input type="text" name="metas[${idx}][texto]" required placeholder="Descreva a meta..."
               style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;"
               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        <select name="metas[${idx}][categoria]"
                style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:9px 10px;font-size:12px;color:#374151;outline:none;background:#fff;cursor:pointer;"
                onchange="colorirSelect(this)">
            <option value="academica">Objetivos Curriculares</option>
            <option value="socioemocional">Desenvolvimento Socioemocional</option>
            <option value="global">Desenvolvimento Global</option>
        </select>
        <button type="button" onclick="removerLinha(this)"
                style="color:#EF4444;background:none;border:none;cursor:pointer;padding:4px;">
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
