@php
    $content  = $documento->content ?? [];
    $anoLetivo = $documento->year ?? date('Y');
    if (isset($documento)) $aluno = $documento->student;
    $aluno->loadMissing(['schoolClasses' => fn($q) => $q->where('year', $anoLetivo)]);
    $turma    = $aluno->schoolClasses->first();

    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $accent  = '#7C3700';
    $fn = fn($k) => old($k, $content[$k] ?? '');
@endphp

@php
$section = fn(string $title, string $subtitle = '') =>
    '<div style="border-left: 3px solid ' . $accent . '; padding: 4px 0 4px 14px; margin-bottom: 16px;">
        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">' . $title . '</p>'
        . ($subtitle ? '<p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">' . $subtitle . '</p>' : '') .
    '</div>';

$textarea = fn(string $name, string $label, int $rows = 3) =>
    '<div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">' . $label . '</label>
        <textarea name="' . $name . '" rows="' . $rows . '"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor=\'' . $accent . '\'" onblur="this.style.borderColor=\'#E5E7EB\'">'
            . e(old($name, $content[$name] ?? '')) .
        '</textarea>
    </div>';
@endphp

{{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
<div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Identificação — preenchido automaticamente</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; color: #374151;">
        <div><span style="color:#9CA3AF; font-weight:600;">Escola:</span> {{ $aluno->school?->name }}</div>
        <div><span style="color:#9CA3AF; font-weight:600;">Matrícula:</span> {{ $aluno->registration_number }}</div>
        <div><span style="color:#9CA3AF; font-weight:600;">Aluno(a):</span> {{ $aluno->name }}</div>
        <div>
            <span style="color:#9CA3AF; font-weight:600;">Data de Nascimento:</span>
            {{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '—' }}
            @if($aluno->birth_date)
                <span style="color:#9CA3AF;"> · </span>{{ $aluno->birth_date->age }} anos
            @endif
        </div>
        <div>
            <span style="color:#9CA3AF; font-weight:600;">Turma/Turno:</span>
            {{ $turma ? $turma->name . ' · ' . $turma->shift : '—' }}
        </div>
        <div><span style="color:#9CA3AF; font-weight:600;">Ano Letivo:</span> {{ $anoLetivo }}</div>
        @if($diagnostico)
        <div style="grid-column: 1 / -1;">
            <span style="color:#9CA3AF; font-weight:600;">Diagnóstico / Laudo:</span> {{ $diagnostico }}
        </div>
        @endif
    </div>
</div>

{{-- ═══ IDENTIFICAÇÃO — Contexto Familiar ═══ --}}
{!! $section('Identificação', 'Dados do estudante e contexto escolar.') !!}

<div style="margin-bottom: 28px;">
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Breve descrição do contexto familiar</label>
        <textarea name="contexto_familiar" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn('contexto_familiar') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ HISTÓRICO ESCOLAR ═══ --}}
{!! $section('Histórico Escolar', 'Trajetória e experiências anteriores.') !!}

<div style="margin-bottom: 28px;">
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Resumo da vida escolar (escolas anteriores, retenções ou avanços)</label>
        <textarea name="historico_escolar" rows="4"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn('historico_escolar') }}</textarea>
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Frequência e assiduidade</label>
        <textarea name="frequencia_assiduidade" rows="2"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn('frequencia_assiduidade') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ OBSERVAÇÕES PEDAGÓGICAS ═══ --}}
{!! $section('Observações Pedagógicas', 'Comportamento, aprendizagem e interação.') !!}

<div style="margin-bottom: 28px;">
    @foreach([
        'nivel_desenvolvimento' => ['Nível de desenvolvimento e aprendizagem atual', 3],
        'comportamento_sala'    => ['Comportamento em sala de aula e rotina', 3],
        'interacao_colegas'     => ['Como interage com colegas, professores e equipe', 3],
    ] as $field => [$label, $rows])
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="{{ $rows }}"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn($field) }}</textarea>
    </div>
    @endforeach
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ BARREIRAS IDENTIFICADAS ═══ --}}
{!! $section('Barreiras Identificadas', 'Dificuldades e limitações encontradas.') !!}

<div style="margin-bottom: 28px;">
    @foreach([
        'desafios_conteudo' => ['Desafios na assimilação de conteúdos', 3],
        'barreiras_fisicas' => ['Barreiras físicas, de comunicação ou atitudinais', 3],
    ] as $field => [$label, $rows])
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="{{ $rows }}"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn($field) }}</textarea>
    </div>
    @endforeach
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ POTENCIALIDADES ═══ --}}
{!! $section('Potencialidades', 'Habilidades e pontos fortes.') !!}

<div style="margin-bottom: 28px;">
    @foreach([
        'interesses_motivacao' => ['Áreas de maior interesse e motivação', 3],
        'habilidades_destaque' => ['Habilidades cognitivas, motoras e sociais de destaque', 3],
    ] as $field => [$label, $rows])
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="{{ $rows }}"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn($field) }}</textarea>
    </div>
    @endforeach
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ ENCAMINHAMENTOS ═══ --}}
{!! $section('Encaminhamentos', 'Sugestões e ações pedagógicas.') !!}

<div style="margin-bottom: 28px;">
    @foreach([
        'estrategias_sala'       => ['Estratégias e metodologias a serem adotadas em sala de aula', 4],
        'adaptacoes_necessarias' => ['Necessidade de adaptações curriculares ou materiais específicos', 3],
        'encaminhamentos_rede'   => ['Encaminhamentos para redes de apoio (psicologia, fonoaudiologia, etc.)', 3],
    ] as $field => [$label, $rows])
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="{{ $rows }}"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn($field) }}</textarea>
    </div>
    @endforeach
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ ELABORAÇÃO ═══ --}}
{!! $section('Elaborado por') !!}

<div style="margin-bottom: 24px;">
    <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Nome do(a) profissional responsável</label>
    <input type="text" name="elaborado_por" value="{{ $fn('elaborado_por') }}"
           placeholder="Nome e cargo"
           style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
           onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">
</div>
