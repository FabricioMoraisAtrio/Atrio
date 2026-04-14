@php
    $aluno  = $documento->student;
    $c      = $documento->content ?? [];
    $turma  = $aluno->schoolClasses->first();
    $school = $aluno->school;

    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $idade = $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') . ' (' . $aluno->birth_date->age . ' anos)' : '—';

    $val = fn($key) => $c[$key] ?? '';

    $accent   = '#009C8C';
    $accentBg = '#E6F5F4';

    // Imagens em base64
    $logoB64  = null;
    $photoB64 = null;
    if ($school?->logo) {
        $p = storage_path('app/public/' . $school->logo);
        if (file_exists($p)) $logoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
    }
    if ($aluno->photo) {
        $p = storage_path('app/public/' . $aluno->photo);
        if (file_exists($p)) $photoB64 = 'data:' . mime_content_type($p) . ';base64,' . base64_encode(file_get_contents($p));
    }

    // Helper: texto em bullet list
    $asList = function(string $text) use ($accent): string {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        if (empty($lines)) return '<span style="color:#bbb; font-style:italic; font-size:9px;">(não preenchido)</span>';
        $out = '';
        foreach ($lines as $line) {
            $out .= '<div style="display:table;width:100%;margin-bottom:3px;">'
                  . '<span style="display:table-cell;width:12px;font-size:9px;color:' . $accent . ';">•</span>'
                  . '<span style="display:table-cell;font-size:9px;color:#1a1a1a;">' . e($line) . '</span>'
                  . '</div>';
        }
        return $out;
    };

    // Helper: campo vazio
    $empty = fn($v) => !trim($v ?? '')
        ? '<span style="color:#bbb; font-style:italic;">não preenchido</span>'
        : e($v);
@endphp

<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #1a1a1a;
        line-height: 1.5;
        background: #fff;
    }

    /* Container principal que respeita o fluxo do dompdf */
    .page {
        width: 100%;
    }

    /* ── Cabeçalho principal ── */
    .doc-header { 
        display: table; 
        width: 100%; 
        table-layout: fixed; 
        border-bottom: 3px solid {{ $accent }}; 
        padding-bottom: 12px; 
        margin-bottom: 18px; 
    }
    
    .doc-header-left  { display: table-cell; vertical-align: middle; width: 60px; }
    .doc-header-mid   { display: table-cell; vertical-align: middle; padding: 0 12px; }
    .doc-header-right { display: table-cell; vertical-align: middle; text-align: right; width: 96px; }

    /* Forçar tabelas a não estourarem a largura da página */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed; /* Essencial no dompdf */
        margin-bottom: 15px;
    }

    .id-table td { 
        border: 1px solid #ddd; 
        padding: 6px 8px; 
        font-size: 9px; 
        word-wrap: break-word; 
    }

    .id-label { 
        background: {{ $accentBg }}; 
        font-weight: bold; 
        width: 120px; 
        color: {{ $accent }}; 
    }

    /* Seções e Quebras */
    .section { 
        margin-bottom: 14px; 
        width: 100%;
        page-break-inside: avoid; 
    }

    .section-header { 
        border-left: 3px solid {{ $accent }}; 
        padding: 3px 0 3px 8px; 
        margin-bottom: 8px; 
    }

    .field-value {
        font-size: 9.5px; 
        color: #1a1a1a; 
        border-bottom: 1px solid #ddd;
        padding: 5px 2px; 
    }

    hr.div { 
        border: none; 
        border-top: 1px solid #efefef; 
        margin: 15px 0; 
        width: 100%;
    }

    /* Ajuste de Imagens */
    .school-logo { max-width: 52px; max-height: 52px; }
    .student-photo { width: 80px; height: 80px; border-radius: 4px; border: 1px solid #ccc; }
</style>

{{-- Documento --}}
<div class="page">

{{-- Cabeçalho --}}
<div class="doc-header">
    <div class="doc-header-left">
        @if($logoB64)
            <img src="{{ $logoB64 }}" class="school-logo" alt="Logo">
        @else
            <div class="photo-placeholder" style="width:52px;height:52px;"><span style="font-size:7px;color:#ccc;">Logo</span></div>
        @endif
    </div>
    <div class="doc-header-mid">
        <div class="doc-school">{{ $school?->name }}</div>
        <div class="doc-subtitle">Plano de Atendimento Educacional Especializado &nbsp;·&nbsp; Ano Letivo {{ $documento->year }}</div>
        <div class="doc-title">PAEE — Plano de Atendimento Educacional Especializado</div>
    </div>
    <div class="doc-header-right">
        @if($photoB64)
            <img src="{{ $photoB64 }}" class="student-photo" alt="Foto">
        @else
            <div class="photo-placeholder"><span style="font-size:7px;color:#ccc;">Foto</span></div>
        @endif
    </div>
</div>

{{-- Identificação --}}
<table class="id-table">
    <tr>
        <td class="id-label">Escola</td>
        <td colspan="3">{{ $school?->name }}</td>
    </tr>
    <tr>
        <td class="id-label">Aluno(a)</td>
        <td colspan="3" style="font-weight: bold;">{{ $aluno->name }}</td>
    </tr>
    <tr>
        <td class="id-label">Data de Nascimento</td>
        <td>{{ $idade }}</td>
        <td class="id-label" style="width: 90px;">Matrícula</td>
        <td>{{ $aluno->registration_number }}</td>
    </tr>
    <tr>
        <td class="id-label">Turma / Turno</td>
        <td>{{ $turma ? $turma->name . ' · ' . $turma->shift : '—' }}</td>
        <td class="id-label">Ano Letivo</td>
        <td>{{ $documento->year }}</td>
    </tr>
    @if($aluno->responsavel_nome || $aluno->responsavel_2_nome)
    <tr>
        <td class="id-label">Responsável</td>
        <td colspan="3">{{ collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])->filter()->implode(' / ') }}</td>
    </tr>
    @endif
    @if($diagnostico)
    <tr>
        <td class="id-label">Diagnóstico / Laudo</td>
        <td colspan="3">{{ $diagnostico }}</td>
    </tr>
    @endif
</table>

@php
$checkCols = function(array $itens): string {
    if (empty($itens)) return '<span style="color:#bbb;font-style:italic;font-size:9px;">(não preenchido)</span>';
    $out = '';
    foreach ($itens as $i => $item) {
        $border = $i > 0 ? 'border-top:1px solid #D5EDEB;' : '';
        $out .= '<div style="' . $border . 'padding:5px 4px;font-size:9px;color:#1a1a1a;">' . e($item) . '</div>';
    }
    return $out;
};
@endphp

{{-- Diagnóstico / Perfil --}}
@php $diagItens = (array)($c['diagnostico_perfil'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Diagnóstico / Perfil</div>
        <div class="section-sub">Necessidades educacionais e barreiras identificadas.</div>
    </div>
    {!! $checkCols($diagItens) !!}
    @if($val('diagnostico_perfil_obs'))
    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin: 6px 0 3px;">Observações</div>
    <div class="field-value" style="white-space: pre-wrap; font-size: 9px;">{{ $val('diagnostico_perfil_obs') }}</div>
    @endif
</div>

<hr class="div">

{{-- Objetivos do AEE --}}
@php $objItens = (array)($c['objetivos_aee'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Objetivos do AEE</div>
    </div>
    {!! $checkCols($objItens) !!}
    @if($val('objetivos_aee_obs'))
    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin: 6px 0 3px;">Observações</div>
    <div class="field-value" style="white-space: pre-wrap; font-size: 9px;">{{ $val('objetivos_aee_obs') }}</div>
    @endif
</div>

<hr class="div">

{{-- Recursos e Estratégias --}}
@php $recItens = (array)($c['recursos_estrategias'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Recursos e Estratégias</div>
    </div>
    {!! $checkCols($recItens) !!}
    @if($val('recursos_estrategias_obs'))
    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin: 6px 0 3px;">Observações</div>
    <div class="field-value" style="white-space: pre-wrap; font-size: 9px;">{{ $val('recursos_estrategias_obs') }}</div>
    @endif
</div>

<hr class="div">

{{-- Organização do Atendimento --}}
@php $orgItens = (array)($c['organizacao_atendimento'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Organização do Atendimento</div>
    </div>
    {!! $checkCols($orgItens) !!}
    @if($val('organizacao_atendimento_obs'))
    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin: 6px 0 3px;">Observações</div>
    <div class="field-value" style="white-space: pre-wrap; font-size: 9px;">{{ $val('organizacao_atendimento_obs') }}</div>
    @endif
</div>


<hr class="div">

{{-- Avaliação e Monitoramento --}}
@php $avalItens = (array)($c['avaliacao_monitoramento'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Avaliação e Monitoramento</div>
        <div class="section-sub">Critérios e periodicidade de avaliação do plano.</div>
    </div>
    {!! $checkCols($avalItens) !!}
    @if($val('avaliacao_monitoramento_obs'))
    <div style="font-size: 8.5px; font-weight: bold; color: #555; margin: 6px 0 3px;">Observações</div>
    <div class="field-value" style="white-space: pre-wrap; font-size: 9px;">{{ $val('avaliacao_monitoramento_obs') }}</div>
    @endif
</div>

<hr class="div">

{{-- Observações Livres --}}
@if(!empty($val('observacoes_livres')))
<div class="section">
    <div class="section-header">
        <div class="section-title">Observações</div>
    </div>
    <div class="field-value tall" style="white-space: pre-wrap;">{{ $val('observacoes_livres') }}</div>
</div>
<hr class="div">
@endif

{{-- Assinaturas --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Responsáveis pela Elaboração do PAEE</div>
    </div>
    @php
        $participantes = array_filter($c['equipe_participantes'] ?? [], fn($p) => !empty($p['nome']));
        $assinantes = [];
        foreach ($participantes as $p) {
            $assinantes[] = ['role' => $p['cargo'] ?? 'Participante', 'name' => $p['nome']];
        }
        if (empty($assinantes)) {
            $assinantes[] = ['role' => 'Profissional do AEE', 'name' => ''];
        }
        $assinantes[] = ['role' => 'Responsável pelo(a) estudante', 'name' => ''];
    @endphp
    <p style="font-size: 8px; color: #888; font-style: italic; margin-bottom: 8px;">
        Declaramos que este PAEE foi elaborado coletivamente, com base no Estudo de Caso, e que seu cumprimento será acompanhado ao longo do ano letivo.
    </p>
    <table style="width:100%;border-collapse:collapse;">
        @foreach($assinantes as $sig)
        <tr>
            <td style="width:40%;border:1px solid #ddd;padding:8px 10px;vertical-align:middle;">
                <div style="font-size:8.5px;font-weight:bold;color:#444;text-transform:uppercase;letter-spacing:0.3px;">{{ $sig['role'] }}</div>
                @if($sig['name'])<div style="font-size:9px;color:#1a1a1a;margin-top:3px;">{{ $sig['name'] }}</div>@endif
            </td>
            <td style="width:60%;border:1px solid #ddd;padding:8px 14px;vertical-align:bottom;">
                <div style="display:table;width:100%;">
                    <div style="display:table-cell;vertical-align:bottom;padding-right:16px;">
                        <div style="font-size:7.5px;color:#aaa;margin-bottom:2px;">Assinatura</div>
                        <div style="border-bottom:1px solid #888;min-height:18px;"></div>
                    </div>
                    <div style="display:table-cell;width:110px;vertical-align:bottom;">
                        <div style="font-size:7.5px;color:#aaa;margin-bottom:2px;">Data</div>
                        <div style="border-bottom:1px solid #888;font-size:8px;color:#bbb;padding-bottom:2px;">____/____/________</div>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

</div>{{-- /page (único) --}}
