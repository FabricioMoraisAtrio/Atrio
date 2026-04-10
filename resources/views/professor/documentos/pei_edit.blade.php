@extends('layouts.app')
@section('title', 'PEI — ' . $aluno->name)

@php
$categorias = [
    'academica'      => ['label' => 'Objetivos Curriculares',        'cor' => '#004B8D', 'bg' => '#E8F0F9'],
    'socioemocional' => ['label' => 'Desenvolvimento Socioemocional', 'cor' => '#007A6E', 'bg' => '#E6F5F4'],
    'global'         => ['label' => 'Desenvolvimento Global',         'cor' => '#6D28D9', 'bg' => '#F0EBF8'],
];

$opcoes = [
    'autonomia'    => 'Executa com autonomia',
    'suporte'      => 'Executa com suporte',
    'nao_executa'  => 'Ainda não executa',
    'nao_observado'=> 'Ainda não observado',
];

$metasPorCategoria = $subject
    ? $subject->inventoryItems->groupBy('categoria')
    : collect();

$metasSalvas = $minha_secao['metas'] ?? [];

$ec = $estudo_caso ?? [];
$accent = '#004B8D';
@endphp

@section('content')
<div style="max-width: 1200px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('professor.alunos.show', $aluno) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o aluno
        </a>
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="padding: 5px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: #E8F0F9; color: #004B8D;">
                PEI {{ date('Y') }}
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $aluno->name }}</h1>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FCA5A5; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <p style="font-size: 13px; color: #DC2626; margin: 0;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div style="background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px;">
            <p style="font-size: 13px; color: #16A34A; margin: 0;">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
    @php $turma = $aluno->schoolClasses->first(); @endphp
    <div style="background: #E8F0F9; border: 1px solid #C5D8F0; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Identificação — preenchido automaticamente</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; color: #374151;">
            <div><span style="color:#004B8D; font-weight:600;">Aluno(a):</span> {{ $aluno->name }}</div>
            <div><span style="color:#004B8D; font-weight:600;">Matrícula:</span> {{ $aluno->registration_number }}</div>
            <div>
                <span style="color:#004B8D; font-weight:600;">Data de Nascimento:</span>
                {{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '—' }}
            </div>
            <div><span style="color:#004B8D; font-weight:600;">Turma:</span> {{ $turma?->name ?? '—' }}</div>
        </div>
    </div>

    {{-- ═══ LEITURA: Diagnóstico Pedagógico (do Estudo de Caso) ═══ --}}
    @if(!empty($ec['diagnostico_pedagogico']))
    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 10px;">Diagnóstico Pedagógico — extraído do Estudo de Caso</p>
        <p style="font-size: 13px; color: #374151; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $ec['diagnostico_pedagogico'] }}</p>
    </div>
    @endif

    {{-- ═══ LEITURA: Objetivos de Aprendizagem (do PEI) ═══ --}}
    @php $peiGlobal = $pei->content['global'] ?? []; @endphp
    @if(!empty($peiGlobal['objetivos_curto_prazo']) || !empty($peiGlobal['objetivos_medio_prazo']) || !empty($peiGlobal['objetivos_longo_prazo']))
    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Objetivos de Aprendizagem</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            @foreach([
                'objetivos_curto_prazo' => 'Curto Prazo',
                'objetivos_medio_prazo' => 'Médio Prazo',
                'objetivos_longo_prazo' => 'Longo Prazo',
            ] as $key => $label)
            @if(!empty($peiGlobal[$key]))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: #004B8D; margin: 0 0 6px;">{{ $label }}</p>
                <p style="font-size: 13px; color: #374151; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal[$key] }}</p>
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

        {{-- ═══ LEITURA: Estratégias Pedagógicas e Avaliação (do PEI) ═══ --}}
    @if(!empty($peiGlobal['estrategias_pedagogicas']) || !empty($peiGlobal['criterios_avaliacao']))
    <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Estratégias e Avaliação</p>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @if(!empty($peiGlobal['estrategias_pedagogicas']))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: #004B8D; margin: 0 0 6px;">Estratégias Pedagógicas</p>
                <p style="font-size: 13px; color: #374151; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal['estrategias_pedagogicas'] }}</p>
            </div>
            @endif
            @if(!empty($peiGlobal['criterios_avaliacao']))
            <div>
                <p style="font-size: 11px; font-weight: 700; color: #004B8D; margin: 0 0 6px;">Avaliação</p>
                <p style="font-size: 13px; color: #374151; white-space: pre-wrap; margin: 0; line-height: 1.6;">{{ $peiGlobal['criterios_avaliacao'] }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

        {{-- ═══ INVENTÁRIO DE HABILIDADES (metas por categoria) ═══ --}}
        @if(! $subject || $subject->inventoryItems->isEmpty())
            <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 16px 20px; margin-bottom: 20px;">
                <p style="font-size: 13px; color: #92400E; margin: 0;">
                    <strong>Atenção:</strong> Esta matéria ainda não tem metas cadastradas.
                    Peça ao administrador para configurar as metas em <em>Configurações → Matérias → {{ $subject?->name ?? 'sua matéria' }}</em>.
                </p>
            </div>
        @else
        <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; overflow: hidden; margin-bottom: 20px;">

            {{-- Cabeçalho da matéria --}}
            <div style="padding: 18px 24px; border-bottom: 1px solid #F3F4F6; background: #F9FAFB; display: flex; align-items: center; gap: 12px;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #004B8D; flex-shrink: 0;"></div>
                <div>
                    <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0;">
                        Inventário de Habilidades — {{ $subject?->name ?? $subjectSlug ?? 'Minha Matéria' }}
                    </p>
                    @if($minha_secao)
                        <p style="font-size: 11px; color: #9CA3AF; margin: 2px 0 0;">
                            Última atualização: {{ \Carbon\Carbon::parse($minha_secao['updated_at'])->format('d/m/Y H:i') }}
                        </p>
                    @else
                        <p style="font-size: 11px; color: #9CA3AF; margin: 2px 0 0;">Ainda não preenchido</p>
                    @endif
                </div>
            </div>

            <div style="padding: 24px; display: flex; flex-direction: column; gap: 20px;">

                @foreach($categorias as $catKey => $cat)
                    @php $itens = $metasPorCategoria->get($catKey, collect()); @endphp

                    <div style="border: 1px solid {{ $cat['bg'] }}; border-radius: 10px; overflow: hidden;">
                        <div style="padding: 12px 18px; background: {{ $cat['bg'] }};">
                            <p style="font-size: 11px; font-weight: 700; color: {{ $cat['cor'] }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">{{ $cat['label'] }}</p>
                        </div>

                        @if($itens->isEmpty())
                            <div style="padding: 16px 18px;">
                                <p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">Nenhuma meta cadastrada nesta categoria.</p>
                            </div>
                        @else
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                                    <thead>
                                        <tr style="background: #F9FAFB; border-bottom: 1px solid #F0F0F0;">
                                            <th style="text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600; color: #6B7280; width: 35%;">Meta / Objetivo</th>
                                            @foreach($opcoes as $rotulo)
                                                <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: #6B7280; white-space: nowrap;">{{ $rotulo }}</th>
                                            @endforeach
                                            <th style="text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600; color: #6B7280; width: 28%;">Observações <span style="font-weight: 400; color: #9CA3AF;">(opcional)</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($itens as $item)
                                            @php
                                                $salvos    = old("metas.{$item->id}", $metasSalvas[$item->id] ?? []);
                                                $flagSalva = is_array($salvos) ? ($salvos['flag'] ?? null) : $salvos;
                                                $obsSalva  = is_array($salvos) ? ($salvos['obs']  ?? '') : '';
                                            @endphp
                                            <tr style="border-top: 1px solid #F3F4F6; {{ $loop->even ? 'background:#FAFAFA;' : '' }}">
                                                <td style="padding: 12px 16px; color: #374151; font-size: 13px; font-weight: 500; line-height: 1.4;">
                                                    {{ $item->meta }}
                                                </td>
                                                @foreach($opcoes as $valor => $rotulo)
                                                    <td style="text-align: center; padding: 12px 8px;">
                                                        <label style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; border: 2px solid {{ $flagSalva === $valor ? $cat['cor'] : '#D1D5DB' }}; background: {{ $flagSalva === $valor ? $cat['cor'] : 'transparent' }}; cursor: pointer; transition: all 0.15s;">
                                                            <input type="radio"
                                                                   name="metas[{{ $item->id }}][flag]"
                                                                   value="{{ $valor }}"
                                                                   {{ $flagSalva === $valor ? 'checked' : '' }}
                                                                   style="display: none;"
                                                                   onchange="atualizarLinha(this, '{{ $cat['cor'] }}')">
                                                            @if($flagSalva === $valor)
                                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                            @endif
                                                        </label>
                                                    </td>
                                                @endforeach
                                                <td style="padding: 10px 16px; vertical-align: top;">
                                                    <textarea name="metas[{{ $item->id }}][obs]"
                                                              rows="3"
                                                              placeholder="Observação..."
                                                              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 6px; padding: 7px 10px; font-size: 12px; color: #374151; outline: none; background: transparent; box-sizing: border-box; resize: vertical; font-family: inherit; line-height: 1.5;"
                                                              onfocus="this.style.borderColor='{{ $cat['cor'] }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $obsSalva }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Observações livres --}}
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                        Observações adicionais (opcional)
                    </label>
                    <textarea name="observacoes_livres" rows="3"
                        placeholder="Outras observações relevantes sobre o aluno nesta disciplina..."
                        style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                        onfocus="this.style.borderColor='#6B7280'" onblur="this.style.borderColor='#E5E7EB'">{{ old('observacoes_livres', $minha_secao['observacoes_livres'] ?? '') }}</textarea>
                </div>

            </div>
        </div>
        @endif

        {{-- Rodapé --}}
        <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 4px;">
            <a href="{{ route('professor.alunos.show', $aluno) }}"
               style="padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #6B7280;">
                Cancelar
            </a>
            <button type="submit"
                    style="padding: 10px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; background: #004B8D; color: #fff; cursor: pointer;">
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
