@extends('layouts.app')
@section('title', 'Adaptações de Prova')

@section('content')
@php
    $activeFilters = array_filter([
        $filtroTurma, $filtroPublico, $filtroGrupo, $filtroDoc, $filtroLaudo,
        ...array_values($filtrosAdap)
    ]);

    $sel = 'width:100%;border:1px solid #E5E7EB;border-radius:5px;padding:3px 4px;font-size:10px;color:#6B7280;background:#F9FAFB;outline:none;';
    $selOn = fn($cor, $bg) => "width:100%;border:1px solid {$cor};border-radius:5px;padding:3px 4px;font-size:10px;color:{$cor};background:{$bg};outline:none;font-weight:700;";
@endphp

<style>
.rot-wrap { background:#fff; border-radius:12px; border:1px solid #F3F4F6; overflow:hidden; }
.rot-table { width:100%; border-collapse:collapse; table-layout:fixed; }
.rot-table col, .rot-table td, .rot-table th { overflow:hidden; }
.rot-table td { max-width:0; }
.rot-table select { display:block; }
.rbadge { display:inline-block; font-size:9px; font-weight:600; padding:2px 6px; border-radius:20px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:100%; }
.rbadge-col { display:flex; flex-direction:column; gap:2px; }

/* ── DARK MODE ── */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-wrap {
    background: var(--bg-card) !important;
    border-color: var(--border-sub) !important;
}
/* Fundos de filtro e cabeçalho */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="background:#FAFAFA"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="background: #FAFAFA"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="background:#F9FAFB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="background: #F9FAFB"] {
    background: var(--bg-subtle) !important;
}
/* Bordas de separação entre colunas */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-right:1px solid #F3F4F6"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-right: 1px solid #F3F4F6"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-right:1px solid #F9FAFB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-right: 1px solid #F9FAFB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-bottom:1px solid #F3F4F6"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-bottom: 1px solid #F3F4F6"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-top:1px solid #F9FAFB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-top: 1px solid #F9FAFB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-bottom:2px solid #F0F0F0"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-bottom: 2px solid #F0F0F0"] {
    border-color: var(--border-sub) !important;
}
/* Bordas coloridas de grupo (azul, verde, laranja, roxo, amarelo) */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left:2px solid #BFDBFE"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left: 2px solid #BFDBFE"] { border-left-color: rgba(59,130,246,0.35) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left:2px solid #BBF7D0"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left: 2px solid #BBF7D0"] { border-left-color: rgba(74,222,128,0.35) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left:2px solid #FED7AA"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left: 2px solid #FED7AA"] { border-left-color: rgba(251,146,60,0.35) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left:2px solid #E9D5FF"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left: 2px solid #E9D5FF"] { border-left-color: rgba(167,139,250,0.35) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left:2px solid #FDE68A"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table [style*="border-left: 2px solid #FDE68A"] { border-left-color: rgba(251,191,36,0.35) !important; }
/* Selects */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) .rot-table select {
    background: var(--bg-subtle) !important;
    color: var(--text-2) !important;
    border-color: var(--border) !important;
}
/* Badges de categoria */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background:#EFF6FF"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background: #EFF6FF"] { background: rgba(59,130,246,0.18) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color:#1D4ED8"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color: #1D4ED8"] { color: #93C5FD !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background:#F0FDF4"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background: #F0FDF4"] { background: rgba(74,222,128,0.15) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color:#166534"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color: #166534"] { color: #86EFAC !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background:#FFF7ED"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background: #FFF7ED"] { background: rgba(251,146,60,0.15) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color:#9A3412"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color: #9A3412"] { color: #FDBA74 !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background:#FDF4FF"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="background: #FDF4FF"] { background: rgba(167,139,250,0.15) !important; }
/* Traço vazio */
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color:#E5E7EB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color: #E5E7EB"] { color: var(--border) !important; }
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color:#D1D5DB"],
:is([data-theme="dark"],[data-theme="slate"],[data-theme="contrast"]) [style*="color: #D1D5DB"] { color: var(--text-4) !important; }
</style>

<div style="margin-bottom:14px;">
    <h1 style="font-size:20px;font-weight:700;color:var(--text-1);margin:0 0 2px;">Adaptações de Prova</h1>
    <p style="font-size:12px;color:var(--text-4);margin:0;">
        {{ $alunos->count() }} estudante(s)
        @if(count($activeFilters))
            — <span style="color:var(--accent-text);font-weight:600;">{{ count($activeFilters) }} filtro(s) ativo(s)</span>
            · <a href="{{ route('secretaria.rotinas.adaptacoes') }}" style="color:var(--danger);font-size:12px;font-weight:500;text-decoration:none;">Limpar</a>
        @endif
    </p>
</div>

<form method="GET" action="{{ route('secretaria.rotinas.adaptacoes') }}">
<div class="rot-wrap">
<table class="rot-table">
    <colgroup>
        <col style="width:8%;">
        <col style="width:14%;">
        <col style="width:6%;">
        <col style="width:9%;">
        <col style="width:10%;">
        <col style="width:7%;">
        <col style="width:5%;">
        <col style="width:13%;">
        <col style="width:10%;">
        <col style="width:9%;">
        <col style="width:9%;">
    </colgroup>
    <thead>
        {{-- LINHA DE FILTROS --}}
        <tr style="background:var(--bg-subtle);border-bottom:1px solid var(--border-sub);">
            <td colspan="11" style="padding:8px 12px 4px;max-width:none;">
                <div style="display:flex;align-items:center;gap:5px;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    <span style="font-size:9px;font-weight:700;color:var(--text-4);text-transform:uppercase;letter-spacing:0.5px;">Filtros</span>
                </div>
            </td>
        </tr>
        <tr style="background:var(--bg-subtle);border-bottom:2px solid #F0F0F0;">
            {{-- TURMA --}}
            <td style="padding:4px 8px 8px;max-width:none;border-right:1px solid var(--border-sub);">
                <select name="turma" onchange="this.form.submit()" style="{{ $filtroTurma ? $selOn('#004B8D','#EFF6FF') : $sel }}">
                    <option value="">Todas</option>
                    @foreach($turmas as $t)
                        <option value="{{ $t->id }}" {{ $filtroTurma == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </td>
            {{-- ESTUDANTE — sem filtro --}}
            <td style="padding:4px 8px 8px;max-width:none;border-right:1px solid var(--border-sub);"></td>
            {{-- PÚBLICO --}}
            <td style="padding:4px 4px 8px;max-width:none;border-right:1px solid var(--border-sub);">
                <select name="publico" onchange="this.form.submit()" style="{{ $filtroPublico ? $selOn('#7E22CE','#F3E8FF') : $sel }}">
                    <option value="">Todos</option>
                    <option value="sim" {{ $filtroPublico === 'sim' ? 'selected' : '' }}>Sim</option>
                    <option value="nao" {{ $filtroPublico === 'nao' ? 'selected' : '' }}>Não</option>
                </select>
            </td>
            {{-- GRUPO --}}
            <td style="padding:4px 6px 8px;max-width:none;border-right:1px solid var(--border-sub);">
                <select name="grupo" onchange="this.form.submit()" style="{{ $filtroGrupo ? $selOn('#7C3700','#F5EDE6') : $sel }}">
                    <option value="">Todos</option>
                    @foreach($transtornos as $field => [$sigla, $nome])
                        <option value="{{ $field }}" {{ $filtroGrupo === $field ? 'selected' : '' }}>{{ $sigla }}</option>
                    @endforeach
                </select>
            </td>
            {{-- SUPORTE --}}
            <td style="padding:4px 6px 8px;max-width:none;border-right:1px solid var(--border-sub);border-left:2px solid var(--info-border);">
                <select name="suporte_mediacao" onchange="this.form.submit()" style="{{ isset($filtrosAdap['suporte_mediacao']) ? $selOn('#1D4ED8','#EFF6FF') : $sel }}">
                    <option value="">Todos</option>
                    @foreach($categorias['suporte_mediacao']['itens'] as $item)
                        <option value="{{ $item }}" {{ ($filtrosAdap['suporte_mediacao'] ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </td>
            {{-- DOCS --}}
            <td style="padding:4px 4px 8px;max-width:none;border-right:1px solid var(--border-sub);">
                <select name="doc" onchange="this.form.submit()" style="{{ $filtroDoc ? $selOn('#004B8D','#EFF6FF') : $sel }}">
                    <option value="">Todos</option>
                    <option value="pei"  {{ $filtroDoc === 'pei'  ? 'selected' : '' }}>PEI</option>
                    <option value="ec"   {{ $filtroDoc === 'ec'   ? 'selected' : '' }}>EC</option>
                    <option value="paee" {{ $filtroDoc === 'paee' ? 'selected' : '' }}>PAEE</option>
                </select>
            </td>
            {{-- LAUDO --}}
            <td style="padding:4px 4px 8px;max-width:none;border-right:1px solid var(--border-sub);">
                <select name="laudo" onchange="this.form.submit()" style="{{ $filtroLaudo ? $selOn('#065F46','#ECFDF5') : $sel }}">
                    <option value="">Todos</option>
                    <option value="sim" {{ $filtroLaudo === 'sim' ? 'selected' : '' }}>Com</option>
                    <option value="nao" {{ $filtroLaudo === 'nao' ? 'selected' : '' }}>Sem</option>
                </select>
            </td>
            {{-- PROVA --}}
            <td style="padding:4px 6px 8px;max-width:none;border-right:1px solid var(--border-sub);border-left:2px solid var(--success-border);">
                <select name="prova_adaptada" onchange="this.form.submit()" style="{{ isset($filtrosAdap['prova_adaptada']) ? $selOn('#166534','#F0FDF4') : $sel }}">
                    <option value="">Todas</option>
                    @foreach($categorias['prova_adaptada']['itens'] as $item)
                        <option value="{{ $item }}" {{ ($filtrosAdap['prova_adaptada'] ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </td>
            {{-- FORMATAÇÃO --}}
            <td style="padding:4px 6px 8px;max-width:none;border-right:1px solid var(--border-sub);border-left:2px solid #FED7AA;">
                <select name="formatacao_diferenciada" onchange="this.form.submit()" style="{{ isset($filtrosAdap['formatacao_diferenciada']) ? $selOn('#9A3412','#FFF7ED') : $sel }}">
                    <option value="">Todas</option>
                    @foreach($categorias['formatacao_diferenciada']['itens'] as $item)
                        <option value="{{ $item }}" {{ ($filtrosAdap['formatacao_diferenciada'] ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </td>
            {{-- APLICAÇÃO --}}
            <td style="padding:4px 6px 8px;max-width:none;border-right:1px solid var(--border-sub);border-left:2px solid var(--purple);">
                <select name="aplicacao_diferenciada" onchange="this.form.submit()" style="{{ isset($filtrosAdap['aplicacao_diferenciada']) ? $selOn('#7E22CE','#FDF4FF') : $sel }}">
                    <option value="">Todas</option>
                    @foreach($categorias['aplicacao_diferenciada']['itens'] as $item)
                        <option value="{{ $item }}" {{ ($filtrosAdap['aplicacao_diferenciada'] ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </td>
            {{-- MATERIAL --}}
            <td style="padding:4px 6px 8px;max-width:none;border-left:2px solid var(--warning-border);">
                <select name="material_adaptado" onchange="this.form.submit()" style="{{ isset($filtrosAdap['material_adaptado']) ? $selOn('#92400E','#FFFBEB') : $sel }}">
                    <option value="">Todos</option>
                    @foreach($categorias['material_adaptado']['itens'] as $item)
                        <option value="{{ $item }}" {{ ($filtrosAdap['material_adaptado'] ?? '') === $item ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        {{-- LINHA DE CABEÇALHOS --}}
        <tr style="background:var(--bg-subtle);border-bottom:1px solid var(--border-sub);">
            <th style="text-align:left;padding:10px 10px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">Turma</th>
            <th style="text-align:left;padding:10px 10px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">Estudante</th>
            <th style="text-align:center;padding:10px 4px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">{{ term('publico_alvo') }}</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">Grupo</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:var(--info);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);border-left:2px solid var(--info-border);">Suporte e Mediação</th>
            <th style="text-align:center;padding:10px 4px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">PEI/EC/PAEE</th>
            <th style="text-align:center;padding:10px 4px;font-size:9px;font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);">Laudo</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);border-left:2px solid var(--success-border);">Prova Adaptada</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:#9A3412;text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);border-left:2px solid #FED7AA;">Formatação</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:var(--purple);text-transform:uppercase;letter-spacing:0.4px;border-right:1px solid var(--border-sub);border-left:2px solid var(--purple);">Aplicação</th>
            <th style="text-align:left;padding:10px 8px;font-size:9px;font-weight:700;color:var(--warning);text-transform:uppercase;letter-spacing:0.4px;border-left:2px solid var(--warning-border);">Material</th>
        </tr>
    </thead>
    <tbody>
        @forelse($alunos as $aluno)
            @php
                $adaptacoes = [];
                foreach ($aluno->documents->where('type', 'pei') as $doc) {
                    $content = $doc->content ?? [];
                    $itens   = $content['adaptacoes'] ?? [];
                    if (is_string($itens)) {
                        $itens = array_filter(array_map('trim', explode(',', $itens)));
                    }
                    $adaptacoes = array_merge($adaptacoes, (array) $itens);
                }
                $adaptacoes = array_unique($adaptacoes);

                $turma   = $aluno->schoolClasses->first();
                $peiDoc  = $aluno->documents->firstWhere('type', 'pei');
                $ecDoc   = $aluno->documents->firstWhere('type', 'estudo_caso');
                $paeeDoc = $aluno->documents->firstWhere('type', 'paee');

                $diagnosticos = array_filter(
                    config('transtornos'),
                    fn($v, $k) => $aluno->$k,
                    ARRAY_FILTER_USE_BOTH
                );
            @endphp
            <tr style="border-top:1px solid var(--border-sub);" onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">

                <td style="padding:10px 10px;font-size:12px;color:var(--text-2);border-right:1px solid var(--border-sub);white-space:nowrap;text-overflow:ellipsis;">
                    {{ $turma?->name ?? '—' }}
                </td>

                <td style="padding:10px 10px;border-right:1px solid var(--border-sub);white-space:nowrap;text-overflow:ellipsis;">
                    <a href="{{ route('secretaria.alunos.show', $aluno) }}"
                       style="font-size:13px;font-weight:600;color:var(--text-1);text-decoration:none;">
                        {{ $aluno->name }}
                    </a>
                </td>

                <td style="padding:10px 4px;text-align:center;border-right:1px solid var(--border-sub);">
                    @if($aluno->is_atypical)
                        <span class="rbadge" style="background:var(--purple-bg);color:var(--purple);">Sim</span>
                    @else
                        <span class="rbadge" style="background:var(--bg-subtle);color:var(--text-4);">Não</span>
                    @endif
                </td>

                <td style="padding:10px 8px;border-right:1px solid var(--border-sub);">
                    <div style="display:flex;flex-wrap:wrap;gap:2px;">
                        @forelse($diagnosticos as $field => [$sigla, $nome])
                            <span class="rbadge" style="background:var(--brown-bg);color:var(--brown);" title="{{ $nome }}">{{ $sigla }}</span>
                            @if($field === 'cid_autismo' && $aluno->tea_nivel_suporte)
                                <span class="rbadge" style="background:var(--warning-bg);color:var(--warning);">N{{ $aluno->tea_nivel_suporte }}</span>
                            @endif
                        @empty
                            <span style="color:var(--text-4);font-size:12px;">—</span>
                        @endforelse
                    </div>
                </td>

                <td style="padding:10px 8px;border-right:1px solid var(--border-sub);border-left:2px solid var(--info-border);">
                    @php $itens = array_intersect($adaptacoes, $categorias['suporte_mediacao']['itens']); @endphp
                    @if(count($itens))
                        <div class="rbadge-col">
                            @foreach($itens as $item)
                                <span class="rbadge" style="background:var(--info-bg);color:var(--info);" title="{{ $item }}">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 4px;text-align:center;border-right:1px solid var(--border-sub);">
                    <div style="display:flex;flex-wrap:wrap;gap:2px;justify-content:center;">
                        @if($peiDoc)
                            <a href="{{ route('secretaria.documentos.show', $peiDoc) }}" style="font-size:9px;font-weight:700;padding:2px 4px;border-radius:4px;background:var(--accent-bg);color:var(--accent-text);text-decoration:none;">PEI</a>
                        @else
                            <span style="font-size:9px;padding:2px 4px;border-radius:4px;background:var(--bg-subtle);color:var(--text-4);">PEI</span>
                        @endif
                        @if($ecDoc)
                            <a href="{{ route('secretaria.documentos.show', $ecDoc) }}" style="font-size:9px;font-weight:700;padding:2px 4px;border-radius:4px;background:var(--brown-bg);color:var(--brown);text-decoration:none;">EC</a>
                        @else
                            <span style="font-size:9px;padding:2px 4px;border-radius:4px;background:var(--bg-subtle);color:var(--text-4);">EC</span>
                        @endif
                        @if($paeeDoc)
                            <a href="{{ route('secretaria.documentos.show', $paeeDoc) }}" style="font-size:9px;font-weight:700;padding:2px 4px;border-radius:4px;background:var(--teal-bg);color:var(--teal);text-decoration:none;">PAEE</a>
                        @else
                            <span style="font-size:9px;padding:2px 4px;border-radius:4px;background:var(--bg-subtle);color:var(--text-4);">PAEE</span>
                        @endif
                    </div>
                </td>

                <td style="padding:10px 4px;text-align:center;border-right:1px solid var(--border-sub);">
                    @if($aluno->laudos->count())
                        <span class="rbadge" style="background:var(--success-bg);color:var(--success);">✓</span>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 8px;border-right:1px solid var(--border-sub);border-left:2px solid var(--success-border);">
                    @php $itens = array_intersect($adaptacoes, $categorias['prova_adaptada']['itens']); @endphp
                    @if(count($itens))
                        <div class="rbadge-col">
                            @foreach($itens as $item)
                                <span class="rbadge" style="background:var(--success-bg);color:#166534;" title="{{ $item }}">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 8px;border-right:1px solid var(--border-sub);border-left:2px solid #FED7AA;">
                    @php $itens = array_intersect($adaptacoes, $categorias['formatacao_diferenciada']['itens']); @endphp
                    @if(count($itens))
                        <div class="rbadge-col">
                            @foreach($itens as $item)
                                <span class="rbadge" style="background:var(--brown-bg);color:#9A3412;" title="{{ $item }}">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 8px;border-right:1px solid var(--border-sub);border-left:2px solid var(--purple);">
                    @php $itens = array_intersect($adaptacoes, $categorias['aplicacao_diferenciada']['itens']); @endphp
                    @if(count($itens))
                        <div class="rbadge-col">
                            @foreach($itens as $item)
                                <span class="rbadge" style="background:#FDF4FF;color:var(--purple);" title="{{ $item }}">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>

                <td style="padding:10px 8px;border-left:2px solid var(--warning-border);">
                    @php $itens = array_intersect($adaptacoes, $categorias['material_adaptado']['itens']); @endphp
                    @if(count($itens))
                        <div class="rbadge-col">
                            @foreach($itens as $item)
                                <span class="rbadge" style="background:var(--warning-bg);color:var(--warning);" title="{{ $item }}">{{ $item }}</span>
                            @endforeach
                        </div>
                    @else
                        <span style="color:#E5E7EB;">—</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="padding:48px;text-align:center;color:var(--text-4);font-size:14px;">
                    Nenhum estudante encontrado com os filtros selecionados.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
</form>
@endsection
