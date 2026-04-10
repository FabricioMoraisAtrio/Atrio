@php
    $content = $documento->content ?? [];
    $global  = $content['global'] ?? [];
    $ec      = $estudo_caso_content ?? [];
    $accent  = '#004B8D';

    $fn = fn(string $k) => old($k, $global[$k] ?? '');

    $readonlyBlock = fn(string $title, string $subtitle, string $text) =>
        '<div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
            <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 10px;">' . e($title) . '</p>
            <p style="font-size: 12px; color: #6B7280; font-style: italic; margin: 0 0 10px;">' . e($subtitle) . '</p>
            <p style="font-size: 13px; color: #374151; white-space: pre-wrap; margin: 0; line-height: 1.6;">' . e($text) . '</p>
        </div>';

    $emptyReadonly = fn(string $title, string $subtitle) =>
        '<div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px;">
            <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">' . e($title) . '</p>
            <p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">' . e($subtitle) . '</p>
        </div>';
@endphp

{{-- ═══ DIAGNÓSTICO PEDAGÓGICO — somente leitura (vem do Estudo de Caso) ═══ --}}
@if(!empty($ec['diagnostico_pedagogico']))
    {!! $readonlyBlock(
        'Diagnóstico Pedagógico — extraído do Estudo de Caso',
        'Descrição das necessidades educacionais.',
        $ec['diagnostico_pedagogico']
    ) !!}
@else
    {!! $emptyReadonly('Diagnóstico Pedagógico', 'Será preenchido automaticamente a partir do Estudo de Caso.') !!}
@endif

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ OBJETIVOS DE APRENDIZAGEM — preenchido pelo responsável ═══ --}}
<div style="margin-bottom: 28px;">
    <div style="border-left: 3px solid {{ $accent }}; padding: 4px 0 4px 14px; margin-bottom: 20px;">
        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">Objetivos de Aprendizagem</p>
        <p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">Metas estabelecidas para o período letivo.</p>
    </div>

    @foreach([
        'objetivos_curto_prazo'  => ['Curto prazo',  'Objetivos a serem alcançados nas próximas semanas / bimestre'],
        'objetivos_medio_prazo'  => ['Médio prazo',  'Objetivos para o semestre letivo'],
        'objetivos_longo_prazo'  => ['Longo prazo',  'Objetivos para o ano letivo completo'],
    ] as $field => [$badge, $label])
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px;">
            <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: #F3F4F6; color: #374151; margin-right: 6px;">{{ $badge }}</span>
            {{ $label }}
        </label>
        <textarea name="{{ $field }}" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn($field) }}</textarea>
    </div>
    @endforeach
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ ESTRATÉGIAS PEDAGÓGICAS — preenchido pelo responsável ═══ --}}
<div style="margin-bottom: 28px;">
    <div style="border-left: 3px solid {{ $accent }}; padding: 4px 0 4px 14px; margin-bottom: 16px;">
        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">Estratégias Pedagógicas</p>
        <p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">Metodologias e adaptações a serem adotadas.</p>
    </div>
    <textarea name="estrategias_pedagogicas" rows="5"
              style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
              onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn('estrategias_pedagogicas') }}</textarea>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ ADAPTAÇÕES CURRICULARES — somente leitura (vem do Estudo de Caso) ═══ --}}
@if(!empty($ec['adaptacoes_necessarias']))
    {!! $readonlyBlock(
        'Adaptações Curriculares — extraído do Estudo de Caso',
        'Necessidades de adaptações curriculares ou materiais específicos.',
        $ec['adaptacoes_necessarias']
    ) !!}
@else
    {!! $emptyReadonly('Adaptações Curriculares', 'Será preenchido automaticamente a partir do Estudo de Caso.') !!}
@endif

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ AVALIAÇÃO — preenchido pelo responsável ═══ --}}
<div style="margin-bottom: 28px;">
    <div style="border-left: 3px solid {{ $accent }}; padding: 4px 0 4px 14px; margin-bottom: 16px;">
        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">Avaliação</p>
        <p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">Como será acompanhado o progresso do aluno.</p>
    </div>
    <textarea name="criterios_avaliacao" rows="4"
              style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
              onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">{{ $fn('criterios_avaliacao') }}</textarea>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ EQUIPE RESPONSÁVEL — somente leitura (vem do Estudo de Caso) ═══ --}}
@php
$equipeFields = [
    'equipe_titular'        => 'Professor(a) Titular / Regente',
    'equipe_soe'            => 'Orientador(a) Educacional (SOE)',
    'equipe_scp'            => 'Psicólogo(a) Escolar (SCP)',
    'equipe_saee'           => 'Coordenador(a) do SAEE',
    'equipe_psicologo'      => 'Psicólogo(a) / Terapeuta Externo',
    'equipe_psicopedagogo'  => 'Psicopedagogo(a)',
    'equipe_fisioterapeuta' => 'Fisioterapeuta / Ed. Físico',
    'equipe_at'             => 'Acompanhante Terapêutico (AT)',
];
$equipePreenchida = collect($equipeFields)->keys()->filter(fn($k) => !empty($ec[$k]))->isNotEmpty();
@endphp

@if($equipePreenchida)
<div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Equipe Responsável — extraído do Estudo de Caso (Termo de Ciência)</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        @foreach($equipeFields as $field => $label)
            @if(!empty($ec[$field]))
            <div>
                <p style="font-size: 11px; font-weight: 600; color: #6B7280; margin: 0 0 2px;">{{ $label }}</p>
                <p style="font-size: 13px; color: #374151; margin: 0;">{{ $ec[$field] }}</p>
            </div>
            @endif
        @endforeach
    </div>
</div>
@else
    {!! $emptyReadonly('Equipe Responsável', 'Será preenchida automaticamente a partir do Estudo de Caso (Termo de Ciência).') !!}
@endif
