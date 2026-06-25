@extends('layouts.app')
@section('title', 'Metas de Habilidades — ' . $aluno->name)

@php
    // Renderiza uma linha de meta (input + remover)
    $metaRow = fn(string $field, string $value = '', string $ph = 'Descreva a meta...') =>
        '<div class="meta-row" style="display:flex;align-items:center;gap:8px;">
            <input type="text" name="' . $field . '[]" value="' . e($value) . '" placeholder="' . e($ph) . '"
                   style="flex:1;border:1px solid var(--border);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text-2);outline:none;box-sizing:border-box;">
            <button type="button" onclick="removerMeta(this)" style="color:var(--danger);background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>';
@endphp

@section('content')
<div style="max-width: 860px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o PEI de {{ $aluno->name }}
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">Metas de Habilidades — {{ $ano }}</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">
            Cadastre as metas customizadas para <strong>{{ $aluno->name }}</strong>. Todas — acadêmicas,
            socioemocionais e funcionais — aparecem no PEI para o professor avaliar.
        </p>
    </div>

    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.alunos.metas-academicas.update', $aluno) }}">
        @csrf @method('PUT')

        {{-- ─── Metas Acadêmicas (por matéria) ─── --}}
        <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Metas Acadêmicas</p>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($subjects as $subject)
                @php $metas = $metasPorMateria->get($subject->id, collect()); @endphp
                <div class="metas-card" style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow: hidden;">
                    <div style="padding: 14px 20px; background: var(--bg-subtle); border-bottom: 1px solid var(--border-sub); display: flex; align-items: center; gap: 10px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent); flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 700; color: var(--text-1);">{{ $subject->name }}</span>
                    </div>
                    <div style="padding: 18px 20px;">
                        <div class="metas-list" data-field="metas[{{ $subject->id }}]" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                            @forelse($metas as $meta)
                                {!! $metaRow('metas[' . $subject->id . ']', $meta->meta, 'Descreva a meta acadêmica...') !!}
                            @empty
                                {!! $metaRow('metas[' . $subject->id . ']', '', 'Descreva a meta acadêmica...') !!}
                            @endforelse
                        </div>
                        <button type="button" onclick="adicionarMeta(this)"
                                style="background: var(--bg-subtle); color: var(--text-2); border: 1px dashed var(--border); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                            Adicionar meta
                        </button>
                    </div>
                </div>
            @empty
                <div style="background: var(--warning-bg); border: 1px solid var(--warning-border); border-radius: 10px; padding: 16px 20px;">
                    <p style="font-size: 13px; color: var(--warning); margin: 0;">
                        Nenhuma matéria cadastrada. Cadastre a grade curricular em
                        <a href="{{ route('secretaria.config.index', ['tab' => 'materias']) }}" style="color: var(--warning); font-weight: 600;">Configurações → Matérias</a>.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- ─── Metas Socioemocionais ─── --}}
        <p style="font-size: 11px; font-weight: 700; color: #007A6E; letter-spacing: 1px; text-transform: uppercase; margin: 24px 0 12px;">Metas Socioemocionais</p>
        <div class="metas-card" style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 18px 20px;">
            <div class="metas-list" data-field="metas_socioemocional" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                @forelse($metasSocio as $meta)
                    {!! $metaRow('metas_socioemocional', $meta->meta, 'Ex.: Regular emoções em situações de frustração...') !!}
                @empty
                    {!! $metaRow('metas_socioemocional', '', 'Ex.: Regular emoções em situações de frustração...') !!}
                @endforelse
            </div>
            <button type="button" onclick="adicionarMeta(this)"
                    style="background: var(--bg-subtle); color: var(--text-2); border: 1px dashed var(--border); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Adicionar meta
            </button>
        </div>

        {{-- ─── Metas Funcionais ─── --}}
        <p style="font-size: 11px; font-weight: 700; color: var(--purple); letter-spacing: 1px; text-transform: uppercase; margin: 24px 0 12px;">Metas Funcionais</p>
        <div class="metas-card" style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 18px 20px;">
            <div class="metas-list" data-field="metas_funcional" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                @forelse($metasFuncionais as $meta)
                    {!! $metaRow('metas_funcional', $meta->meta, 'Ex.: Realizar a higiene pessoal com autonomia...') !!}
                @empty
                    {!! $metaRow('metas_funcional', '', 'Ex.: Realizar a higiene pessoal com autonomia...') !!}
                @endforelse
            </div>
            <button type="button" onclick="adicionarMeta(this)"
                    style="background: var(--bg-subtle); color: var(--text-2); border: 1px dashed var(--border); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Adicionar meta
            </button>
        </div>

        <p style="font-size: 11px; color: var(--text-4); margin: 14px 0 0;">
            As metas socioemocionais e funcionais são avaliadas pelo professor regente no PEI.
        </p>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                Cancelar
            </a>
            <button type="submit"
                    style="background: var(--accent); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Salvar metas
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
               style="flex:1;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;">
        <button type="button" onclick="removerMeta(this)"
                style="color:#EF4444;background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
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
