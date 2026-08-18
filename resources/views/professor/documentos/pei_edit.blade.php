@extends('layouts.app')
@section('title', 'PEI — ' . $aluno->name)

@php
$opcoes = [
    'autonomia'    => 'Executa com autonomia',
    'suporte'      => 'Executa com suporte',
    'nao_executa'  => 'Ainda não executa',
    'nao_observado'=> 'Ainda não observado',
];

$metasSalvas = $minha_secao['metas'] ?? [];

$ec = $estudo_caso ?? [];
$accent = '#004B8D';
@endphp

@section('content')
<div style="max-width: 1200px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.show', $aluno) }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o estudante
        </a>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: var(--accent-bg); color: var(--accent-text);">
                PEI {{ date('Y') }}
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $aluno->name }}</h1>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <p style="font-size: 13px; color: var(--danger); margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
            <p style="font-size: 13px; color: var(--success); margin: 0;">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
    @php $turma = $aluno->schoolClasses->first(); @endphp
    <div style="background: var(--accent-bg); border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Identificação — preenchido automaticamente</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; color: var(--text-2);">
            <div><span style="color:var(--accent-text); font-weight:600;">Estudante(a):</span> {{ $aluno->name }}</div>
            <div><span style="color:var(--accent-text); font-weight:600;">Matrícula:</span> {{ $aluno->registration_number }}</div>
            <div>
                <span style="color:var(--accent-text); font-weight:600;">Data de Nascimento:</span>
                {{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '—' }}
            </div>
            <div><span style="color:var(--accent-text); font-weight:600;">Turma:</span> {{ $turma?->name ?? '—' }}</div>
        </div>
    </div>

    {{-- ═══ LEITURA: Objetivos de Aprendizagem (do PEI) ═══ --}}
    @php $peiGlobal = $pei->content['global'] ?? []; @endphp
    @if(!empty($peiGlobal['objetivos_curto_prazo']) || !empty($peiGlobal['objetivos_medio_prazo']) || !empty($peiGlobal['objetivos_longo_prazo']))
    <div style="background: var(--info-bg); border: 1px solid var(--info-border); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Objetivos de Aprendizagem</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            @foreach([
                'objetivos_curto_prazo' => 'Curto Prazo',
                'objetivos_medio_prazo' => 'Médio Prazo',
                'objetivos_longo_prazo' => 'Longo Prazo',
            ] as $key => $label)
            @if(!empty($peiGlobal[$key]))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); margin: 0 0 6px;">{{ $label }}</p>
                <p style="font-size: 13px; color: var(--text-2); white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal[$key] }}</p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('professor.alunos.pei.update', $aluno) }}">
        @csrf @method('PUT')

        <input type="hidden" name="subject_slug" value="{{ $subjectSlug }}">
        <input type="hidden" name="subject_name" value="{{ $subject?->name ?? $subjectSlug }}">
        <input type="hidden" name="subject_tipo" value="{{ $subject?->tipo ?? 'disciplina' }}">

        {{-- ═══ LEITURA: Estratégias Pedagógicas e Avaliação (do PEI) ═══ --}}
    @if(!empty($peiGlobal['estrategias_pedagogicas']) || !empty($peiGlobal['criterios_avaliacao']))
    <div style="background: var(--info-bg); border: 1px solid var(--info-border); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Estratégias e Avaliação</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @if(!empty($peiGlobal['estrategias_pedagogicas']))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); margin: 0 0 6px;">Estratégias Pedagógicas</p>
                <p style="font-size: 13px; color: var(--text-2); white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal['estrategias_pedagogicas'] }}</p>
            </div>
            @endif
            @if(!empty($peiGlobal['criterios_avaliacao']))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); margin: 0 0 6px;">Avaliação</p>
                <p style="font-size: 13px; color: var(--text-2); white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal['criterios_avaliacao'] }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

        {{-- ═══ METAS DE HABILIDADES ═══ --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; overflow: hidden; margin-bottom: 20px;">

            {{-- Cabeçalho da matéria --}}
            <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-sub); background: var(--bg-subtle); display: flex; align-items: center; gap: 12px;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $isRegente ? '#007A6E' : 'var(--accent)' }}; flex-shrink: 0;"></div>
                <div>
                    <p style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0;">
                        Metas de Habilidades — {{ $subject?->name ?? $subjectSlug ?? 'Minha Matéria' }}
                    </p>
                    @if($minha_secao)
                        <p style="font-size: 11px; color: var(--text-4); margin: 2px 0 0;">
                            Última atualização: {{ \Carbon\Carbon::parse($minha_secao['updated_at'])->format('d/m/Y H:i') }}
                        </p>
                    @else
                        <p style="font-size: 11px; color: var(--text-4); margin: 2px 0 0;">Ainda não preenchido</p>
                    @endif
                </div>
            </div>

            <div style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">

                @if($isRegente)
                    {{-- ─── Regente: avalia metas socioemocionais e funcionais ─── --}}
                    @include('professor.documentos.partials.metas-flag-table', [
                        'metas' => $metasSocio, 'accent' => '#007A6E', 'bg' => '#E6F5F4',
                        'label' => 'Metas Socioemocionais', 'cat' => 'socioemocional', 'metasSalvas' => $metasSalvas,
                    ])
                    @include('professor.documentos.partials.metas-flag-table', [
                        'metas' => $metasFuncionais, 'accent' => '#6D28D9', 'bg' => '#F0EBF8',
                        'label' => 'Metas Funcionais', 'cat' => 'funcional', 'metasSalvas' => $metasSalvas,
                    ])
                @else
                    {{-- ─── Disciplina: avalia metas acadêmicas ─── --}}
                    @include('professor.documentos.partials.metas-flag-table', [
                        'metas' => $metasAcademicas, 'accent' => '#004B8D', 'bg' => '#E8F0F9',
                        'label' => 'Metas Acadêmicas', 'cat' => 'academica', 'metasSalvas' => $metasSalvas,
                    ])
                @endif

                {{-- Observações livres --}}
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                        Observações adicionais (opcional)
                    </label>
                    <textarea name="observacoes_livres" rows="3"
                        placeholder="Outras observações relevantes sobre o estudante nesta {{ $isRegente ? 'área' : 'disciplina' }}..."
                        style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                        onfocus="this.style.borderColor='var(--text-4)'" onblur="this.style.borderColor='var(--border)'">{{ old('observacoes_livres', $minha_secao['observacoes_livres'] ?? '') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Rodapé --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 4px;">
            <a href="{{ route('secretaria.alunos.show', $aluno) }}"
               style="padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid var(--border); color: var(--text-3);">
                Cancelar
            </a>
            <button type="submit"
                    style="padding: 10px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; background: var(--accent); color: #fff; cursor: pointer;">
                Salvar
            </button>
        </div>

    </form>
</div>
<script>
function atualizarLinha(radio, cor) {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const emptyBorder = isDark
        ? getComputedStyle(document.documentElement).getPropertyValue('--border').trim()
        : '#D1D5DB';
    const emptyBg = isDark
        ? getComputedStyle(document.documentElement).getPropertyValue('--bg-card').trim()
        : '#fff';

    const tr = radio.closest('tr');
    tr.querySelectorAll('input[type=radio]').forEach(inp => {
        const label = inp.closest('label');
        const isChecked = inp === radio;
        label.style.borderColor = isChecked ? cor : emptyBorder;
        label.style.background  = isChecked ? cor : emptyBg;
        label.innerHTML = `<input type="radio" name="${inp.name}" value="${inp.value}" ${isChecked ? 'checked' : ''} style="display:none;" onchange="atualizarLinha(this,'${cor}')">` +
            (isChecked ? `<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5"><polyline points="20 6 9 17 4 12"/></svg>` : '');
    });
}
</script>
@endsection
