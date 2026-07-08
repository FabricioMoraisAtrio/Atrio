@extends('layouts.app')
@section('title', 'Banco de Metas')

@php
    use App\Models\GoalTemplate;

    // Linha de meta (input + remover). $field = "metas[categoria]"
    $metaRow = fn(string $field, string $value = '', string $ph = 'Descreva a meta...') =>
        '<div class="meta-row" style="display:flex;align-items:center;gap:8px;">
            <input type="text" name="' . $field . '[]" value="' . e($value) . '" placeholder="' . e($ph) . '"
                   style="flex:1;border:1px solid var(--border);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text-2);outline:none;box-sizing:border-box;">
            <button type="button" onclick="removerMeta(this)" style="color:var(--danger);background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>';

    $cores = [
        'academica'      => 'var(--accent-text)',
        'socioemocional' => '#007A6E',
        'funcional'      => 'var(--purple)',
    ];
    $exemplos = [
        'academica'      => 'Ex.: Reconhecer os números de 1 a 20...',
        'socioemocional' => 'Ex.: Regular emoções em situações de frustração...',
        'funcional'      => 'Ex.: Realizar a higiene pessoal com autonomia...',
    ];
@endphp

@section('content')
<div style="max-width: 820px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.painel') }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Banco de Metas</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">
            Cadastre metas modelo por categoria. Elas aparecem como sugestões ao personalizar as metas de cada aluno.
        </p>
    </div>

    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.metas.banco.update') }}">
        @csrf @method('PUT')

        @foreach(GoalTemplate::CATEGORIES as $cat => $label)
            @php $lista = ($templates[$cat] ?? collect()); @endphp
            <p style="font-size: 11px; font-weight: 700; color: {{ $cores[$cat] }}; letter-spacing: 1px; text-transform: uppercase; margin: {{ $loop->first ? '0' : '24px' }} 0 12px;">{{ $label }}</p>
            <div class="metas-card" style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 18px 20px;">
                <div class="metas-list" data-field="metas[{{ $cat }}]" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                    @forelse($lista as $t)
                        {!! $metaRow('metas[' . $cat . ']', $t->texto, $exemplos[$cat]) !!}
                    @empty
                        {!! $metaRow('metas[' . $cat . ']', '', $exemplos[$cat]) !!}
                    @endforelse
                </div>
                <button type="button" onclick="adicionarMeta(this)"
                        style="background: var(--bg-subtle); color: var(--text-2); border: 1px dashed var(--border); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                    Adicionar meta
                </button>
            </div>
        @endforeach

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <button type="submit"
                    style="background: var(--accent); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Salvar banco
            </button>
        </div>
    </form>
</div>

<script>
function novaLinha(list) {
    const field = list.getAttribute('data-field');
    const row = document.createElement('div');
    row.className = 'meta-row';
    row.style.cssText = 'display:flex;align-items:center;gap:8px;';
    row.innerHTML = `
        <input type="text" name="${field}[]" value="" placeholder="Descreva a meta..."
               style="flex:1;border:1px solid var(--border);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text-2);outline:none;box-sizing:border-box;">
        <button type="button" onclick="removerMeta(this)"
                style="color:var(--danger);background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(row);
    row.querySelector('input').focus();
}

function adicionarMeta(btn) {
    novaLinha(btn.closest('.metas-card').querySelector('.metas-list'));
}

function removerMeta(btn) {
    const list = btn.closest('.metas-list');
    btn.closest('.meta-row').remove();
    if (list && list.querySelectorAll('.meta-row').length === 0) {
        novaLinha(list);
    }
}
</script>
@endsection
