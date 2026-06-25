@extends('layouts.app')
@section('title', 'Painel')

@section('content')
<div style="margin-bottom: 28px;">
    <a href="{{ route('professor.dashboard') }}"
       style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Início
    </a>
    <h1 style="font-size: 24px; font-weight: 700; color: var(--text-1); margin: 0 0 6px;">
        Painel
    </h1>
    <div style="display: flex; align-items: center; gap: 10px;">
        <p style="font-size: 14px; color: var(--text-4); margin: 0;">Resumo das suas turmas e alunos inclusivos</p>
        @if($subject)
            <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 12px; font-weight: 700; padding: 3px 12px; border-radius: 20px;">
                {{ $subject->name }}
            </span>
        @endif
    </div>
</div>

{{-- A mensagem de sucesso já é exibida globalmente pelo layout (app.blade.php) --}}

@if($pendentes->isNotEmpty())
    <div style="background: var(--warning-bg); border: 1px solid var(--warning-border); border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <p style="font-size: 13px; font-weight: 600; color: var(--warning); margin: 0;">Documentos pendentes</p>
        </div>
        <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach($pendentes as $item)
                <div style="background: var(--bg-card); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--warning-border);">
                    <div>
                        <span style="font-size: 13px; font-weight: 600; color: var(--text-1);">{{ $item['aluno']->name }}</span>
                        <span style="font-size: 12px; color: var(--text-4); margin-left: 8px;">{{ $item['turma']->name }}</span>
                    </div>
                    <div style="display: flex; gap: 4px;">
                        <a href="{{ route('professor.alunos.pei.edit', $item['aluno']) }}"
                           style="background: #92400E; color: #fff; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 20px; text-decoration: none;">
                            Preencher PEI
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Filtro por diagnóstico --}}
@php
    $filtroCidLabel = $filtroCid ? ($transtornos[$filtroCid][0] . ' — ' . $transtornos[$filtroCid][1]) : '';
@endphp
<form method="GET" action="{{ route('professor.painel') }}" id="formFiltroCid"
      style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 16px 20px; margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">
    <input type="hidden" name="filtro_cid" id="filtro_cid_value" value="{{ $filtroCid ?? '' }}">
    <div style="flex: 1; min-width: 200px; position: relative;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Filtrar por diagnóstico</label>
        <input type="text" id="filtro_cid_input" placeholder="Digite sigla ou nome..." autocomplete="off"
               value="{{ $filtroCidLabel }}"
               oninput="filtrarSugestoes(this.value)"
               onfocus="filtrarSugestoes(this.value)"
               style="width: 100%; border: 1px solid var(--border, var(--border)); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-2, var(--text-2)); outline: none; background: var(--bg-card, var(--bg-card)); box-sizing: border-box;">
        <div id="sugestoes_cid"
             style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--bg-card, var(--bg-card)); border: 1px solid var(--border, var(--border)); border-radius: 8px; margin-top: 4px; max-height: 220px; overflow-y: auto; z-index: 100; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        </div>
    </div>
    <div style="display: flex; gap: 8px;">
        <button type="submit"
                style="background: var(--accent); color: white; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Filtrar
        </button>
        @if($filtroCid)
            <a href="{{ route('professor.painel') }}"
               style="padding: 9px 14px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                Limpar
            </a>
        @endif
    </div>
</form>

@php
    $transtornosJson = json_encode(
        collect($transtornos)->map(fn($v, $k) => ['field' => $k, 'sigla' => $v[0], 'nome' => $v[1]])->values()
    );
@endphp
<script>
const transtornosData = {!! $transtornosJson !!};
let matchesAtuais = [];

function filtrarSugestoes(query) {
    const box = document.getElementById('sugestoes_cid');
    const q = query.toLowerCase().trim();
    document.getElementById('filtro_cid_value').value = '';

    matchesAtuais = q === ''
        ? transtornosData
        : transtornosData.filter(t =>
            t.sigla.toLowerCase().includes(q) || t.nome.toLowerCase().includes(q)
          );

    if (matchesAtuais.length === 0) { box.style.display = 'none'; return; }

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const bgCard    = isDark ? '#243352' : '#fff';
    const bgHover   = isDark ? '#2A3B58' : '#F9FAFB';
    const textColor = isDark ? '#C8D8EE' : '#374151';
    const borderColor = isDark ? '#384F6E' : '#F3F4F6';

    box.innerHTML = matchesAtuais.map(t => `
        <div onclick="selecionarCid('${t.field}', '${t.sigla} — ${t.nome}')"
             style="padding: 9px 14px; font-size: 13px; color: ${textColor}; cursor: pointer; border-bottom: 1px solid ${borderColor}; display: flex; align-items: center; gap: 8px; background: ${bgCard};"
             onmouseover="this.style.background='${bgHover}'" onmouseout="this.style.background='${bgCard}'">
            <span style="font-size: 11px; font-weight: 700; background: #F5EDE6; color: #7C3700; padding: 2px 7px; border-radius: 20px; flex-shrink: 0;">${t.sigla}</span>
            <span>${t.nome}</span>
        </div>
    `).join('');
    box.style.display = 'block';
}

function selecionarCid(field, label) {
    document.getElementById('filtro_cid_value').value = field;
    document.getElementById('filtro_cid_input').value = label;
    document.getElementById('sugestoes_cid').style.display = 'none';
}

function autoSelecionarEFiltrar() {
    const valorAtual = document.getElementById('filtro_cid_value').value;
    if (valorAtual) { document.getElementById('formFiltroCid').submit(); return; }
    if (matchesAtuais.length > 0) {
        const t = matchesAtuais[0];
        selecionarCid(t.field, `${t.sigla} — ${t.nome}`);
        document.getElementById('formFiltroCid').submit();
    }
}

document.getElementById('filtro_cid_input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); autoSelecionarEFiltrar(); }
});

document.getElementById('formFiltroCid').addEventListener('submit', function(e) {
    const valorAtual = document.getElementById('filtro_cid_value').value;
    if (!valorAtual && matchesAtuais.length > 0) {
        e.preventDefault();
        const t = matchesAtuais[0];
        selecionarCid(t.field, `${t.sigla} — ${t.nome}`);
        this.submit();
    }
});

document.addEventListener('click', function(e) {
    const box = document.getElementById('sugestoes_cid');
    if (!box.contains(e.target) && e.target.id !== 'filtro_cid_input') {
        box.style.display = 'none';
    }
});
</script>

@forelse($turmas as $turma)
    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 24px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--border-sub);">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 2px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $turma->name }}</h3>
                    @if($subject)
                        <span style="background: var(--accent-bg); color: var(--accent-text); font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 20px;">
                            {{ $subject->name }}
                        </span>
                    @endif
                </div>
                <p style="font-size: 12px; color: var(--text-4); margin: 0;">{{ $turma->shift }} · {{ $turma->year }}</p>
            </div>
            <span style="background: var(--teal-bg); color: var(--teal); font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                {{ $turma->students->count() }} aluno(s) {{ strtolower(term('publico_alvo')) }}
            </span>
        </div>

        @if($turma->students->isEmpty())
            <p style="font-size: 13px; color: var(--text-4);">Nenhum {{ strtolower(term('aluno')) }} {{ strtolower(term('publico_alvo')) }} nesta {{ strtolower(term('turma')) }}.</p>
        @else
            <div>
                @foreach($turma->students as $aluno)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--accent-bg); color: var(--accent-text); font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($aluno->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size: 14px; font-weight: 500; color: var(--text-1); margin: 0;">{{ $aluno->name }}</p>
                                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                                    @foreach(config('transtornos') as $field => [$sigla, $nome])
                                        @if($aluno->$field)
                                            <span style="font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: var(--brown-bg); color: var(--brown);" title="{{ $nome }}">{{ $sigla }}</span>
                                            @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                                                <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 20px; background: var(--warning-bg); color: var(--warning);">Nível {{ $aluno->tea_nivel_suporte }}</span>
                                            @endif
                                        @endif
                                    @endforeach
                                    @if($aluno->condition)
                                        <span style="font-size: 10px; color: var(--text-4); padding: 2px 6px;">{{ $aluno->condition }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="{{ route('professor.alunos.show', $aluno) }}"
                               style="font-size: 12px; color: var(--accent-text); text-decoration: none; font-weight: 600; padding: 5px 12px; border: 1px solid #E8F0F9; border-radius: 8px; background: var(--accent-bg);">
                                Ver perfil
                            </a>
                            @php
                                $pei              = $aluno->documents->firstWhere('type', 'pei');
                                $temEstudoCaso    = $aluno->documents->contains('type', 'estudo_caso');
                                $subjectSlugTurma = $turma->pivot->subject ?? null;
                                $preencheu        = $pei && isset(($pei->content['subjects'] ?? [])[$subjectSlugTurma]);
                            @endphp
                            @if($pei && $preencheu)
                                <a href="{{ route('professor.alunos.pei.edit', $aluno) }}"
                                   style="font-size: 11px; background: var(--success-bg); color: var(--success); font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                    PEI ✓
                                </a>
                            @elseif(! $temEstudoCaso)
                                <span style="font-size: 11px; background: var(--bg-subtle); color: var(--text-4); font-weight: 500; padding: 5px 10px; border-radius: 8px;" title="Aguardando Estudo de Caso">
                                    PEI bloqueado
                                </span>
                            @else
                                <a href="{{ route('professor.alunos.pei.edit', $aluno) }}"
                                   style="font-size: 11px; background: var(--accent); color: #fff; font-weight: 600; padding: 5px 10px; border-radius: 8px; text-decoration: none;">
                                    Preencher PEI
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@empty
    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 48px; text-align: center;">
        <p style="font-size: 14px; color: var(--text-4);">Você não está vinculado a nenhuma turma ainda.</p>
    </div>
@endforelse
@endsection
