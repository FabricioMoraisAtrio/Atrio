@php
    use App\Http\Controllers\Secretaria\PeiConsolidadoController;

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

    $responsaveis = collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])
        ->filter()->implode(' / ');

    $idade = $aluno->birth_date ? $aluno->birth_date->age . ' anos' : '';

    // Carrega todos os PEIs individuais dos professores
    $peis = \App\Models\Document::where('student_id', $aluno->id)
        ->where('year', $documento->year)
        ->where('type', 'pei')
        ->with('author:id,name')
        ->get();

    $inventario = PeiConsolidadoController::consolidarInventario($peis);

    $hdr = '#A8BAD0';
    $sub = '#D0DCE8';
    $lbl = '#E8EEF4';
    $brd = '#999';

    $colunas = [
        'realiza_sem_suporte' => 'Realiza sem suporte',
        'realiza_com_apoio'   => 'Realiza com apoio',
        'ainda_nao_realiza'   => 'Ainda não realiza',
        'nao_observado'       => 'Não observado',
    ];

    $val = fn($key) => $c[$key] ?? '';
@endphp

<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #111; padding: 28px 32px; }
    .doc-title { text-align: center; font-size: 10.5px; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { border: 1px solid {{ $brd }}; vertical-align: top; padding: 3px 6px; }
    .hdr { background: {{ $hdr }}; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 8.5px; letter-spacing: 0.5px; padding: 5px 6px; }
    .sub { background: {{ $sub }}; font-weight: bold; text-align: center; font-size: 8.5px; padding: 4px 6px; }
    .lbl { background: {{ $lbl }}; font-weight: bold; font-size: 8.5px; white-space: nowrap; width: 1%; }
    .val { font-size: 9px; min-height: 16px; }
    .tall { min-height: 36px; }
    .inv-th { background: #F5F5F5; font-weight: bold; font-size: 8px; text-align: center; padding: 4px 3px; }
    .inv-meta { font-size: 8px; padding: 4px 6px; text-align: center; }
    .inv-chk { text-align: center; padding: 4px 3px; font-size: 10px; }
    .page-break { page-break-after: always; }
    .footer-bar { margin-top: 16px; border-top: 1px solid #aaa; padding-top: 6px; display: table; width: 100%; }
    .footer-bar td { border: none; font-size: 8px; color: #555; padding: 0; }
</style>

<p class="doc-title">{{ $aluno->registration_number ?? '000000' }} PLANO EDUCACIONAL INDIVIDUALIZADO - PEI</p>

{{-- PÁGINA 1: Cabeçalho + Necessidades --}}
<table>
    <tr>
        <td class="lbl" style="width: 90px;">Unidade:</td>
        <td class="val" colspan="5">{{ $school?->name }}</td>
    </tr>
    <tr><td class="hdr" colspan="6">Dados de Identificação</td></tr>
    <tr>
        <td class="lbl">Estudante:</td>
        <td class="val" colspan="5">{{ $aluno->name }}</td>
    </tr>
    <tr>
        <td class="lbl">Data nascimento:</td>
        <td class="val" colspan="2">{{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '' }}</td>
        <td class="lbl" style="width: 60px;">Idade:</td>
        <td class="val" colspan="2">{{ $idade }}</td>
    </tr>
    <tr>
        <td class="lbl">Diagnóstico:</td>
        <td class="val" colspan="5">{{ $diagnostico }}</td>
    </tr>
    <tr>
        <td class="lbl">Responsáveis:</td>
        <td class="val" colspan="5">{{ $responsaveis }}</td>
    </tr>
    <tr>
        <td class="lbl">Ano letivo:</td>
        <td class="val">{{ $documento->year }}</td>
        <td class="lbl" style="width: 40px;">Ano:</td>
        <td class="val">{{ $turma ? explode('º', $turma->name)[0] . 'º' : '' }}</td>
        <td class="lbl" style="width: 50px;">Turma:</td>
        <td class="val">{{ $turma?->name }}</td>
    </tr>

    {{-- Equipe do Colégio --}}
    @php
        $ec = \App\Models\Document::where('student_id', $aluno->id)
            ->where('year', $documento->year)
            ->where('type', 'estudo_caso')
            ->value('content') ?? [];
    @endphp
    <tr>
        <td class="lbl" rowspan="4" style="vertical-align: middle; text-align: center;">Equipe do<br>Colégio</td>
        <td class="val" colspan="5">Professor Titular/Conselheiro: {{ $ec['professor_titular'] ?? '' }}</td>
    </tr>
    <tr><td class="val" colspan="5">SOE: {{ $ec['soe'] ?? '' }}</td></tr>
    <tr><td class="val" colspan="5">SCP: {{ $ec['scp'] ?? '' }}</td></tr>
    <tr><td class="val" colspan="5">SAEE: {{ $ec['saee'] ?? '' }}</td></tr>
    <tr>
        <td class="lbl" rowspan="5" style="vertical-align: middle; text-align: center;">Equipe<br>Multidisciplinar</td>
        <td class="val" colspan="5">Psicóloga: {{ $ec['psicologa'] ?? '' }}</td>
    </tr>
    <tr><td class="val" colspan="5">Psiquiatra: {{ $ec['psiquiatra'] ?? '' }}</td></tr>
    <tr><td class="val" colspan="5">Psicopedagoga: {{ $ec['psicopedagoga'] ?? '' }}</td></tr>
    <tr><td class="val" colspan="5">Fisioterapeuta-Educador Físico: {{ $ec['fisioterapeuta'] ?? '' }}</td></tr>
    <tr><td class="val" colspan="5">AT: {{ $ec['at'] ?? '' }}</td></tr>

    {{-- Trajetória --}}
    <tr><td class="hdr" colspan="6">Trajetória Escolar do(a) Estudante</td></tr>
    <tr>
        <td class="val tall" colspan="6" style="min-height: 50px; white-space: pre-wrap;">{{ $ec['historico'] ?? '' }}</td>
    </tr>

    {{-- Necessidades Educacionais --}}
    <tr><td class="hdr" colspan="6">Necessidades Educacionais Especiais do(a) Estudante</td></tr>
    <tr>
        <td class="val tall" colspan="6" style="min-height: 80px; white-space: pre-wrap;">{{ $val('necessidades_educacionais') }}</td>
    </tr>
</table>

<table style="margin-top: 16px; border: none;">
    <tr style="border: none;">
        <td style="border: none; font-size: 8px; color: #555;">Modelo PEI {{ $documento->year }}</td>
        <td style="border: none; text-align: center; font-size: 8px; color: #555;">Página 1 de</td>
        <td style="border: none; text-align: right; font-size: 8px; color: #555;">{{ now()->format('d/m/Y') }}</td>
    </tr>
</table>

<div class="page-break"></div>

{{-- PÁGINA 2: Inventário de Habilidades --}}
<p class="doc-title">{{ $aluno->registration_number ?? '000000' }} PLANO EDUCACIONAL INDIVIDUALIZADO - PEI</p>

<table>
    <tr><td class="hdr" colspan="7">Inventário de Habilidades Escolares</td></tr>

    @php
        $grupos = [
            'habilidades_academicas'      => 'Habilidades Acadêmicas',
            'habilidades_socioemocionais' => 'Habilidades Socioemocionais',
            'habilidades_funcionais'      => 'Habilidades Funcionais',
        ];
    @endphp

    @foreach($grupos as $catKey => $catLabel)
        @php $itens = $inventario[$catKey] ?? []; @endphp
        @if(count($itens))
        <tr><td class="sub" colspan="7">{{ $catLabel }}</td></tr>
        <tr>
            <td class="inv-th" style="width: 30%;">Metas/Objetivos</td>
            <td class="inv-th" style="width: 9%;">Realiza sem suporte</td>
            <td class="inv-th" style="width: 9%;">Realiza com apoio</td>
            <td class="inv-th" style="width: 9%;">Ainda não realiza</td>
            <td class="inv-th" style="width: 9%;">Não observado</td>
            <td class="inv-th" style="width: 17%;">Responsável</td>
            <td class="inv-th">Observações</td>
        </tr>
        @foreach($itens as $item)
        <tr>
            <td class="inv-meta" style="text-align: left;">{{ $item['meta'] }}</td>
            @foreach(array_keys($colunas) as $col)
            <td class="inv-chk">{{ !empty($item[$col]) ? 'X' : '' }}</td>
            @endforeach
            <td style="font-size: 8px; padding: 3px 5px;">{{ $item['responsavel'] ?? $item['_autor'] ?? '' }}</td>
            <td style="font-size: 8px; padding: 3px 5px;">{{ $item['observacoes'] ?? '' }}</td>
        </tr>
        @endforeach
        @endif
    @endforeach
</table>

{{-- Adaptações --}}
<table style="margin-top: 8px;">
    <tr><td class="hdr" colspan="1">Adaptações e/ou Adequações no Processo de Avaliação</td></tr>
    <tr>
        <td class="val" style="min-height: 40px; white-space: pre-wrap; font-size: 9px;">{{ $val('adaptacoes_avaliacao') }}</td>
    </tr>
    <tr><td class="hdr">Observações não contempladas ao longo do PEI</td></tr>
    <tr>
        <td class="val" style="min-height: 50px; white-space: pre-wrap; font-size: 9px;">{{ $val('observacoes') }}</td>
    </tr>
    <tr>
        <td style="font-size: 8px; font-style: italic; padding: 6px; background: #f9f9f9; border: 1px solid {{ $brd }};">
            <strong>Observação:</strong> Este Plano Educacional Individualizado (PEI) encontra-se EM CONSTRUÇÃO, por se configurar como um documento dinâmico e processual, sujeito a revisões sistemáticas, análise contínua dos processos e das práticas pedagógicas e ajustes ao longo do semestre. As informações, estratégias e encaminhamentos poderão ser atualizados com base em observações periódicas, registros pedagógicos e avaliações contínuas, assegurando sua efetividade e alinhamento às necessidades educacionais do(a) estudante.
        </td>
    </tr>
</table>

{{-- Termo de Ciência --}}
<table style="margin-top: 8px;">
    <tr><td class="hdr" colspan="2">Termo de Ciência e Concordância por Parte dos Envolvidos</td></tr>
    <tr>
        <td colspan="2" style="font-size: 8px; text-align: center; padding: 4px; background: #f5f5f5; border: 1px solid {{ $brd }};">
            Assinatura dos responsáveis pela elaboração do PEI e Responsáveis pelo estudante
        </td>
    </tr>
    @foreach(['Orientador(a) Educacional', 'Profissional do AEE', 'Professor(a) Titular', 'Mãe/Responsável', 'Pai/Responsável', 'Terapeuta'] as $assinante)
    <tr>
        <td class="lbl" style="width: 160px; vertical-align: middle;">{{ $assinante }}</td>
        <td style="padding: 7px 12px;">
            <table style="width: 100%; border-collapse: collapse; border: none;">
                <tr>
                    <td style="border: none; width: 75%; padding: 0;">
                        <span style="border-bottom: 1px solid #333; display: block; width: 92%; height: 16px;"></span>
                    </td>
                    <td style="border: none; text-align: right; padding: 0; font-size: 8px; color: #555;">
                        ____/____/________
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
</table>

<table style="margin-top: 16px; border: none;">
    <tr style="border: none;">
        <td style="border: none; font-size: 8px; color: #555;">Modelo PEI {{ $documento->year }}</td>
        <td style="border: none; text-align: center; font-size: 8px; color: #555;">Página 2 de</td>
        <td style="border: none; text-align: right; font-size: 8px; color: #555;">{{ now()->format('d/m/Y') }}</td>
    </tr>
</table>
