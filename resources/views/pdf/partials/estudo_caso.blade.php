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

    $responsaveis = collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])
        ->filter()->implode(' / ');

    $idade = $aluno->birth_date ? $aluno->birth_date->age . ' anos' : '';

    $val = fn($key) => $c[$key] ?? '';

    // Cores
    $hdr = '#A8BAD0';   // cabeçalho de seção
    $sub = '#D0DCE8';   // sub-seção
    $lbl = '#E8EEF4';   // célula de rótulo
    $brd = '#999';      // borda
@endphp

<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #111; padding: 28px 32px; }
    .doc-title { text-align: center; font-size: 11px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 14px; }
    table { width: 100%; border-collapse: collapse; }
    td, th { border: 1px solid {{ $brd }}; vertical-align: top; padding: 4px 7px; }
    .hdr { background: {{ $hdr }}; font-weight: bold; text-align: center; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; padding: 5px 7px; }
    .sub { background: {{ $sub }}; font-weight: bold; font-size: 9px; padding: 4px 7px; }
    .lbl { background: {{ $lbl }}; font-weight: bold; font-size: 9px; white-space: nowrap; width: 1%; }
    .val { font-size: 9.5px; min-height: 18px; }
    .tall { min-height: 40px; }
    .sig-line { border-bottom: 1px solid #333; min-width: 180px; display: inline-block; margin-right: 8px; }
    .page-break { page-break-after: always; }
    .footer { font-size: 8px; color: #666; text-align: center; margin-top: 8px; }
</style>

<p class="doc-title">{{ $aluno->registration_number ?? '000000' }} — ESTUDO DE CASO</p>

<table>
    {{-- Unidade --}}
    <tr>
        <td class="lbl" style="width: 100px;">Unidade:</td>
        <td class="val">{{ $school?->name }}</td>
    </tr>

    {{-- DADOS DE IDENTIFICAÇÃO --}}
    <tr><td class="hdr" colspan="2">Dados de Identificação</td></tr>
    <tr>
        <td class="lbl">Estudante:</td>
        <td class="val">{{ $aluno->name }}</td>
    </tr>
    <tr>
        <td class="lbl">Data nascimento:</td>
        <td class="val">
            {{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '' }}
            &nbsp;&nbsp;&nbsp; Idade: {{ $idade }}
            &nbsp;&nbsp;&nbsp; Matrícula: {{ $aluno->registration_number }}
        </td>
    </tr>
    <tr>
        <td class="lbl">Diagnóstico:</td>
        <td class="val">{{ $diagnostico }}</td>
    </tr>
    <tr>
        <td class="lbl">Responsáveis:</td>
        <td class="val">{{ $responsaveis }}</td>
    </tr>
    <tr>
        <td class="lbl">Ano letivo:</td>
        <td class="val">
            {{ $documento->year }}
            @if($turma)
                &nbsp;&nbsp;&nbsp; Turma: {{ $turma->name }}
                &nbsp;&nbsp;&nbsp; Turno: {{ $turma->shift }}
            @endif
        </td>
    </tr>

    {{-- Equipe do Colégio --}}
    <tr>
        <td class="lbl" rowspan="4" style="vertical-align: middle; text-align: center;">Equipe do<br>Colégio</td>
        <td class="val">Professor Titular/Conselheiro: {{ $val('professor_titular') }}</td>
    </tr>
    <tr><td class="val">SOE: {{ $val('soe') }}</td></tr>
    <tr><td class="val">SCP: {{ $val('scp') }}</td></tr>
    <tr><td class="val">SAEE: {{ $val('saee') }}</td></tr>

    {{-- Equipe Multidisciplinar --}}
    <tr>
        <td class="lbl" rowspan="5" style="vertical-align: middle; text-align: center;">Equipe<br>Multidisciplinar</td>
        <td class="val">Psicóloga: {{ $val('psicologa') }}</td>
    </tr>
    <tr><td class="val">Psiquiatra: {{ $val('psiquiatra') }}</td></tr>
    <tr><td class="val">Psicopedagoga: {{ $val('psicopedagoga') }}</td></tr>
    <tr><td class="val">Fisioterapeuta-Educador Físico: {{ $val('fisioterapeuta') }}</td></tr>
    <tr><td class="val">AT: {{ $val('at') }}</td></tr>

    {{-- HISTÓRICO --}}
    <tr><td class="hdr" colspan="2">Histórico de Vida e Trajetória Escolar do(a) Estudante</td></tr>
    <tr>
        <td class="lbl" style="vertical-align: middle; text-align: center; width: 110px;">Trajetória escolar<br>do(a) estudante</td>
        <td class="val tall" style="min-height: 60px;">{{ $val('historico') }}</td>
    </tr>

    {{-- CARACTERIZAÇÃO --}}
    <tr><td class="hdr" colspan="2">Caracterização do(a) Estudante</td></tr>

    {{-- A) Cognitivos --}}
    <tr><td class="sub" colspan="2">A) Aspectos Cognitivos e Acadêmicos</td></tr>
    @foreach(['atencao' => 'Atenção', 'memoria' => 'Memória', 'raciocinio_logico' => 'Raciocínio lógico', 'leitura_escrita' => 'Leitura e Escrita', 'matematica' => 'Matemática', 'organizacao' => 'Organização'] as $k => $l)
    <tr>
        <td class="lbl" style="width: 130px;">{{ $l }}</td>
        <td class="val">{{ $val($k) }}</td>
    </tr>
    @endforeach

    {{-- B) Comunicacionais --}}
    <tr><td class="sub" colspan="2">B) Aspectos Comunicacionais</td></tr>
    @foreach(['linguagem_expressiva' => 'Linguagem expressiva (fala)', 'linguagem_receptiva' => 'Linguagem receptiva (compreensão)', 'comunicacao_alternativa' => 'Comunicação alternativa'] as $k => $l)
    <tr>
        <td class="lbl">{{ $l }}</td>
        <td class="val">{{ $val($k) }}</td>
    </tr>
    @endforeach

    {{-- C) Socioemocionais --}}
    <tr><td class="sub" colspan="2">C) Aspectos Socioemocionais e Comportamentais</td></tr>
    @foreach(['interacao_social' => 'Interação social', 'autonomia' => 'Autonomia', 'regulacao_emocional' => 'Regulação emocional', 'interesses_motivacao' => 'Interesses e motivação'] as $k => $l)
    <tr>
        <td class="lbl">{{ $l }}</td>
        <td class="val">{{ $val($k) }}</td>
    </tr>
    @endforeach

    {{-- D) Sensoriais --}}
    <tr><td class="sub" colspan="2">D) Aspectos Sensoriais e Motores</td></tr>
    @foreach(['motricidade_grossa' => 'Motricidade grossa', 'motricidade_fina' => 'Motricidade fina', 'processamento_sensorial' => 'Processamento sensorial'] as $k => $l)
    <tr>
        <td class="lbl">{{ $l }}</td>
        <td class="val">{{ $val($k) }}</td>
    </tr>
    @endforeach
</table>

<p class="footer">Página 1</p>
<div class="page-break"></div>

{{-- PÁGINA 2 --}}
<p class="doc-title">{{ $aluno->registration_number ?? '000000' }} — ESTUDO DE CASO</p>

<table>
    {{-- ANÁLISE DA PARTICIPAÇÃO --}}
    <tr><td class="hdr" colspan="2">Análise da Participação e da Rotina Escolar</td></tr>
    @foreach(['rotina' => 'Rotina', 'participacao_aulas' => 'Participação nas aulas', 'participacao_intervalos' => 'Participação nos intervalos', 'apoios_necessarios' => 'Apoios necessários'] as $k => $l)
    <tr>
        <td class="lbl" style="width: 150px;">{{ $l }}</td>
        <td class="val tall">{{ $val($k) }}</td>
    </tr>
    @endforeach

    {{-- SÍNTESE --}}
    <tr><td class="hdr" colspan="2">Síntese do Estudo de Caso</td></tr>
    <tr>
        <td class="lbl" style="width: 150px;">Principais potencialidades</td>
        <td class="val tall">{{ $val('potencialidades') }}</td>
    </tr>
    <tr>
        <td class="lbl">Principais barreiras para a aprendizagem e participação</td>
        <td class="val tall">{{ $val('barreiras') }}</td>
    </tr>
    <tr>
        <td class="lbl">Encaminhamentos necessários<br>(o que a escola pode fazer)</td>
        <td class="val" style="min-height: 80px;">{{ $val('encaminhamentos') }}</td>
    </tr>

    {{-- RESPONSÁVEIS PELA ELABORAÇÃO --}}
    <tr><td class="hdr" colspan="2">Responsáveis pela Elaboração do Estudo de Caso</td></tr>
    <tr>
        <td class="lbl" style="width: 150px;">Profissional do AEE</td>
        <td class="val">{{ $val('profissional_aee') }}</td>
    </tr>
    <tr>
        <td class="lbl">Orientador(a) Educacional</td>
        <td class="val">{{ $val('orientador_educacional') }}</td>
    </tr>
    <tr>
        <td class="lbl">Professor(a) Titular</td>
        <td class="val">{{ $val('professor_titular') }}</td>
    </tr>
    <tr>
        <td class="lbl">Contribuições</td>
        <td class="val tall">{{ $val('contribuicoes') }}</td>
    </tr>

    {{-- TERMO DE CIÊNCIA --}}
    <tr><td class="hdr" colspan="2">Termo de Ciência e Concordância por Parte dos Envolvidos</td></tr>
    <tr>
        <td colspan="2" style="font-size: 8.5px; text-align: center; padding: 4px; border: 1px solid {{ $brd }}; background: #f5f5f5;">
            Assinatura dos responsáveis pela elaboração do Estudo de Caso e Responsáveis pelo estudante
        </td>
    </tr>
    @foreach(['Profissional do AEE', 'Orientador(a) Educacional', 'Professor(a) Titular', 'Mãe/Responsável', 'Pai/Responsável', 'Terapeuta'] as $assinante)
    <tr>
        <td class="lbl" style="width: 150px; vertical-align: middle;">{{ $assinante }}</td>
        <td style="padding: 8px 14px;">
            <table style="width: 100%; border-collapse: collapse; border: none;">
                <tr>
                    <td style="border: none; width: 75%; padding: 0;">
                        <span style="border-bottom: 1px solid #333; display: block; width: 90%; height: 18px;"></span>
                    </td>
                    <td style="border: none; text-align: right; padding: 0; font-size: 8.5px; color: #555;">
                        ____/____/________
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endforeach
</table>

<p class="footer">Página 2</p>
