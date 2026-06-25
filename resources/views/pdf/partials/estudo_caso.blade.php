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

    $dataElab = $val('data_elaboracao')
        ? \Carbon\Carbon::parse($val('data_elaboracao'))->format('d/m/Y')
        : date('d/m/Y');

    // Imagens em base64 para DomPDF
    $logoB64  = null;
    $photoB64 = null;
    if ($school?->logo) {
        $logoPath = storage_path('app/public/' . $school->logo);
        if (file_exists($logoPath)) {
            $mime = mime_content_type($logoPath);
            $logoB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    }
    if ($aluno->photo) {
        $photoPath = storage_path('app/public/' . $aluno->photo);
        if (file_exists($photoPath)) {
            $mime = mime_content_type($photoPath);
            $photoB64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
        }
    }

    $accent = '#7C3700';
    $accentBg = '#F8F0E8';

    // Carregar PEI e inventário de metas
    $pei = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'pei')
        ->first();
    $peiSubjects = $pei ? ($pei->content['subjects'] ?? []) : [];

    $allMetaIds = collect($peiSubjects)
        ->flatMap(fn($s) => array_keys($s['metas'] ?? []))
        ->map(fn($id) => (int) $id)->unique()->values()->all();

    $inventoryItems = $allMetaIds
        ? \App\Models\SubjectInventoryItem::withoutGlobalScopes()
            ->whereIn('id', $allMetaIds)->get()->keyBy('id')
        : collect();

    $categorias_inv = [
        'academica'      => 'Objetivos Curriculares',
        'socioemocional' => 'Desenvolvimento Socioemocional',
        'global'         => 'Desenvolvimento Global',
    ];

    // Helper: renderiza array de itens como lista separada por linhas
    $renderBoxes = function(array $itens): string {
        if (empty($itens)) return '<span style="color:#bbb;font-style:italic;font-size:9px;">(não preenchido)</span>';
        $out = '';
        foreach ($itens as $i => $item) {
            $border = $i > 0 ? 'border-top:1px solid #F0E8DF;' : '';
            $out .= '<div style="' . $border . 'padding:5px 4px;font-size:9px;color:#1a1a1a;">' . e($item) . '</div>';
        }
        return $out;
    };

    // Helper: renderiza textarea como lista de itens (uma linha = um bullet)
    $asList = function(string $text): string {
        $lines = array_filter(array_map('trim', explode("\n", $text)));
        if (empty($lines)) return '';
        $out = '';
        foreach ($lines as $line) {
            $out .= '<table style="width:100%; border-collapse:collapse; margin-bottom:2px;"><tr>'
                  . '<td style="width:10px; font-size:9px; vertical-align:top;">•</td>'
                  . '<td style="font-size:9px; color:#1a1a1a; vertical-align:top;">' . e($line) . '</td>'
                  . '</tr></table>';
        }
        return $out;
    };
@endphp

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9.5px;
        color: #1a1a1a;
        padding: 0;
        line-height: 1.5;
    }

    /* ── Layout de página ── */
    .page { width: 100%; }

    /* ── Cabeçalho ── */
    .doc-header {
        width: 100%; table-layout: fixed; border-collapse: collapse;
        border-bottom: 3px solid {{ $accent }};
        margin-bottom: 18px;
    }
    .doc-header-left  { vertical-align: middle; width: 60px; padding-bottom: 12px; }
    .doc-header-mid   { vertical-align: middle; padding: 0 12px 12px; }
    .doc-header-right { vertical-align: middle; text-align: right; width: 96px; padding-bottom: 12px; }
    .doc-school { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #1a1a1a; }
    .doc-subtitle { font-size: 8.5px; color: #666; margin-top: 2px; }
    .doc-title {
        font-size: 13px; font-weight: bold; text-align: center;
        text-transform: uppercase; letter-spacing: 1.5px;
        color: {{ $accent }}; margin-top: 6px;
    }
    .school-logo { width: 52px; height: 52px; object-fit: contain; }
    .student-photo { width: 90px; height: 90px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc; }
    .photo-placeholder {
        width: 90px; height: 90px; border-radius: 6px; border: 1px dashed #ccc;
        text-align: center; vertical-align: middle;
    }

    /* ── Cabeçalho de continuação (páginas 2+) ── */
    .doc-header-sm {
        width: 100%; table-layout: fixed; border-collapse: collapse;
        border-bottom: 1.5px solid {{ $accent }};
        margin-bottom: 16px;
    }
    .doc-header-sm-l { font-size: 9px; font-weight: bold; color: #333; text-transform: uppercase; padding-bottom: 6px; }
    .doc-header-sm-r { text-align: right; font-size: 8px; color: #888; padding-bottom: 6px; }

    /* ── Identificação ── */
    .id-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .id-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; }
    .id-label { background: #f5f0ea; font-weight: bold; width: 120px; color: #444; white-space: nowrap; }

    /* ── Seção ── */
    .section { margin-bottom: 18px; page-break-inside: avoid; }
    .section-header { border-left: 3px solid {{ $accent }}; padding: 3px 0 3px 8px; margin-bottom: 10px; }
    .section-title { font-size: 9.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: {{ $accent }}; }
    .section-sub { font-size: 8px; color: #888; font-style: italic; margin-top: 1px; }

    /* ── Campo ── */
    .field { margin-bottom: 12px; }
    .field-label { font-size: 8.5px; font-weight: bold; color: #555; margin-bottom: 3px; }
    .field-value {
        font-size: 9.5px; color: #1a1a1a;
        border-bottom: 1px solid #ddd;
        min-height: 26px; padding: 3px 2px 6px;
        white-space: pre-wrap; word-break: break-word;
    }
    .field-value.tall { min-height: 48px; }
    .field-value.taller { min-height: 64px; }
    .field-value.empty { color: #ccc; font-style: italic; }

    /* ── Grid ── */
    .grid-3 { display: table; width: 100%; }
    .grid-3 .col { display: table-cell; width: 33.3%; vertical-align: top; padding-right: 14px; }
    .grid-3 .col:last-child { padding-right: 0; }
    .grid-2 { display: table; width: 100%; }
    .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 14px; }
    .grid-2 .col:last-child { padding-right: 0; }

    /* ── Equipe ── */
    .equipe-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .equipe-table td { border: 1px solid #ddd; padding: 4px 8px; font-size: 9px; }
    .equipe-label { background: #f5f0ea; font-weight: bold; width: 160px; color: #444; }

    /* ── Assinaturas ── */
    .sig-table { width: 100%; border-collapse: collapse; }
    .sig-table td { border: 1px solid #ddd; padding: 6px 10px; vertical-align: top; }
    .sig-role { font-size: 8.5px; font-weight: bold; color: #444; }
    .sig-name { font-size: 9px; color: #1a1a1a; margin-top: 2px; }
    .sig-line { border-bottom: 1px solid #888; display: block; margin-top: 14px; width: 100%; }
    .sig-date { font-size: 7.5px; color: #888; text-align: right; margin-top: 3px; }

    hr.div { border: none; border-top: 1px solid #efefef; margin: 14px 0; }
</style>

{{-- ══════════════════════════════════════════════ --}}
{{-- PÁGINA 1 --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="page">

{{-- Cabeçalho --}}
<table class="doc-header"><tr>
    <td class="doc-header-left">
        @if($logoB64)
            <img src="{{ $logoB64 }}" class="school-logo" alt="Logo">
        @else
            <div class="photo-placeholder"><span style="font-size:7px; color:#ccc;">Logo</span></div>
        @endif
    </td>
    <td class="doc-header-mid">
        <div class="doc-school">{{ $school?->name }}</div>
        <div class="doc-subtitle">Atendimento Educacional Especializado &nbsp;·&nbsp; Ano Letivo {{ $documento->year }}</div>
        <div class="doc-title">Estudo de Caso</div>
    </td>
    <td class="doc-header-right">
        @if($photoB64)
            <img src="{{ $photoB64 }}" class="student-photo" alt="Foto">
        @else
            <div class="photo-placeholder"><span style="font-size:7px; color:#ccc;">Foto</span></div>
        @endif
    </td>
</tr></table>

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
    @if($val('contexto_familiar'))
    <tr>
        <td class="id-label">Contexto Familiar</td>
        <td colspan="3">{{ $val('contexto_familiar') }}</td>
    </tr>
    @endif
</table>

{{-- Histórico Escolar --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Histórico Escolar</div>
        <div class="section-sub">Trajetória e experiências anteriores.</div>
    </div>
    <div class="field">
        <div class="field-label">Resumo da vida escolar</div>
        <div class="field-value tall {{ !$val('historico_escolar') ? 'empty' : '' }}">{{ $val('historico_escolar') ?: ' ' }}</div>
    </div>
    <div class="field">
        <div class="field-label">Frequência e assiduidade</div>
        <div class="field-value {{ !$val('frequencia_assiduidade') ? 'empty' : '' }}">{{ $val('frequencia_assiduidade') ?: ' ' }}</div>
    </div>
</div>

<hr class="div">

{{-- Observações Pedagógicas --}}
@php $obsItens = (array)($c['obs_pedagogicas'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Observações Pedagógicas</div>
        <div class="section-sub">Comportamento, aprendizagem e interação.</div>
    </div>
    {!! $renderBoxes($obsItens) !!}
    @if($val('obs_pedagogicas_obs'))
    <div class="field">
        <div class="field-label">Observações complementares</div>
        <div class="field-value" style="white-space: pre-wrap;">{{ $val('obs_pedagogicas_obs') }}</div>
    </div>
    @endif
    @if(!$obsItens && !$val('obs_pedagogicas_obs'))
    <div class="field-value empty"> </div>
    @endif
</div>


<hr class="div">

{{-- Barreiras Identificadas --}}
@php $barreiraItens = (array)($c['barreiras'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Barreiras Identificadas</div>
        <div class="section-sub">Dificuldades e limitações encontradas.</div>
    </div>
    {!! $renderBoxes($barreiraItens) !!}
    @if($val('barreiras_obs'))
    <div class="field">
        <div class="field-label">Observações complementares</div>
        <div class="field-value" style="white-space: pre-wrap;">{{ $val('barreiras_obs') }}</div>
    </div>
    @endif
    @if(!$barreiraItens && !$val('barreiras_obs'))
    <div class="field-value empty"> </div>
    @endif
</div>

<hr class="div">

{{-- Potencialidades --}}
@php $potItens = (array)($c['potencialidades'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Potencialidades</div>
        <div class="section-sub">Habilidades e pontos fortes.</div>
    </div>
    {!! $renderBoxes($potItens) !!}
    @if($val('potencialidades_obs'))
    <div class="field">
        <div class="field-label">Observações complementares</div>
        <div class="field-value" style="white-space: pre-wrap;">{{ $val('potencialidades_obs') }}</div>
    </div>
    @endif
    @if(!$potItens && !$val('potencialidades_obs'))
    <div class="field-value empty"> </div>
    @endif
</div>

<hr class="div">

{{-- Encaminhamentos --}}
@php $encItens = (array)($c['encaminhamentos'] ?? []); @endphp
<div class="section">
    <div class="section-header">
        <div class="section-title">Encaminhamentos</div>
        <div class="section-sub">Sugestões e ações pedagógicas.</div>
    </div>
    {!! $renderBoxes($encItens) !!}
    @if($val('encaminhamentos_obs'))
    <div class="field">
        <div class="field-label">Observações complementares</div>
        <div class="field-value" style="white-space: pre-wrap;">{{ $val('encaminhamentos_obs') }}</div>
    </div>
    @endif
    @if(!$encItens && !$val('encaminhamentos_obs'))
    <div class="field-value empty"> </div>
    @endif
</div>


<hr class="div">

{{-- Equipe Responsável --}}
@php
    $participantes = array_filter($c['equipe_participantes'] ?? [], fn($p) => !empty($p['nome']));
    // Sempre inclui "Elaborado por" como primeiro assinante
    $assinantes = [['cargo' => 'Elaborado por', 'nome' => $val('elaborado_por')]];
    foreach ($participantes as $p) {
        $assinantes[] = ['cargo' => $p['cargo'] ?? '', 'nome' => $p['nome'] ?? ''];
    }
    // Responsáveis sempre ao final
    $assinantes[] = ['cargo' => 'Responsável pelo(a) estudante', 'nome' => ''];
    $assinantes[] = ['cargo' => 'Responsável 2', 'nome' => ''];
    $rows = array_chunk($assinantes, 2);
@endphp

@if(count($participantes) > 0)
<div class="section">
    <div class="section-header">
        <div class="section-title">Equipe Responsável</div>
        <div class="section-sub">Profissionais envolvidos na formulação deste documento.</div>
    </div>
    <table class="equipe-table">
        @foreach($participantes as $p)
        <tr>
            <td class="equipe-label">{{ $p['cargo'] ?? '—' }}</td>
            <td>{{ $p['nome'] ?? '—' }}</td>
        </tr>
        @endforeach
    </table>
</div>

<hr class="div">
@endif

{{-- Assinaturas --}}
<div class="section">
    <div class="section-header">
        <div class="section-title">Termo de Ciência e Concordância</div>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        @foreach($assinantes as $sig)
        <tr>
            <td style="width:40%;border:1px solid #ddd;padding:8px 10px;vertical-align:middle;">
                <div style="font-size:8.5px;font-weight:bold;color:#444;text-transform:uppercase;letter-spacing:0.3px;">{{ $sig['cargo'] }}</div>
                @if($sig['nome'])<div style="font-size:9px;color:#1a1a1a;margin-top:3px;">{{ $sig['nome'] }}</div>@endif
            </td>
            <td style="width:60%;border:1px solid #ddd;padding:10px 14px 12px;vertical-align:bottom;">
                <table style="width:100%; border-collapse:collapse;"><tr>
                    <td style="vertical-align:bottom; padding-right:16px;">
                        <div style="font-size:8.5px;color:#aaa;margin-bottom:2px;">Assinatura</div>
                        <div style="border-bottom:1px solid #888;min-height:34px;"></div>
                    </td>
                    <td style="width:130px; vertical-align:bottom;">
                        <div style="font-size:8.5px;color:#aaa;margin-bottom:2px;">Data</div>
                        <div style="border-bottom:1px solid #888;font-size:10px;color:#bbb;padding-bottom:4px;">____/____/________</div>
                    </td>
                </tr></table>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div style="margin-top: 10px; padding: 8px 10px; border: 1px solid #e0d0c0; border-radius: 4px; background: #fdf8f3; font-size: 8px; color: #888; line-height: 1.5;">
    <strong>Nota:</strong> Este documento é confidencial e destina-se exclusivamente ao planejamento educacional do(a) estudante, em conformidade com a LGPD (Lei 13.709/2018).
    Elaborado em {{ $dataElab }} &nbsp;·&nbsp; Gerado em {{ now()->format('d/m/Y H:i') }}
</div>

</div>{{-- /page (único) --}}

