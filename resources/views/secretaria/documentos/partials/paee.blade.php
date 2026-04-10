@php
    $content   = $documento->content ?? [];
    $anoLetivo = $documento->year ?? date('Y');
    if (isset($documento)) $aluno = $documento->student;
    $aluno->loadMissing(['schoolClasses' => fn($q) => $q->where('year', $anoLetivo)]);
    $turma = $aluno->schoolClasses->first();

    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $accent = '#009C8C';
    $fn = fn($k) => old($k, $content[$k] ?? '');

    // Campos preenchidos automaticamente a partir do Estudo de Caso
    $ec = $estudo_caso_content ?? [];
    // Diagnóstico/Perfil vem do campo "Necessidade de adaptações curriculares" do Estudo de Caso
    $ec_diagnostico_perfil     = $ec['adaptacoes_necessarias']      ?? '';
    $ec_tecnologias_assistivas = $ec['paee_tecnologias_assistivas'] ?? '';
    $ec_adaptacoes             = $ec['paee_adaptacoes']             ?? '';
    $ec_metodologias           = $ec['paee_metodologias']           ?? '';
@endphp

@php
$section = fn(string $title, string $subtitle = '') =>
    '<div style="border-left: 3px solid ' . $accent . '; padding: 4px 0 4px 14px; margin-bottom: 16px;">
        <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">' . $title . '</p>'
        . ($subtitle ? '<p style="font-size: 12px; color: #9CA3AF; font-style: italic; margin: 0;">' . $subtitle . '</p>' : '') .
    '</div>';

$textarea = fn(string $name, string $label, int $rows = 3, string $placeholder = '') =>
    '<div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">' . $label . '</label>
        <textarea name="' . $name . '" rows="' . $rows . '" placeholder="' . $placeholder . '"
                  style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor=\'' . $accent . '\'" onblur="this.style.borderColor=\'#E5E7EB\'">'
            . e(old($name, $content[$name] ?? '')) .
        '</textarea>
    </div>';
@endphp

{{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
<div style="background: #F0FDFB; border: 1px solid #CCECE9; border-radius: 10px; padding: 20px; margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: #9CA3AF; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Identificação do Estudante — preenchido automaticamente</p>
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
            <span style="color:#9CA3AF; font-weight:600;">Turma / Turno:</span>
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

{{-- ═══ DIAGNÓSTICO / PERFIL (preenchido automaticamente do Estudo de Caso) ═══ --}}
{!! $section('Diagnóstico / Perfil', 'Preenchido automaticamente a partir do Estudo de Caso.') !!}

<div style="margin-bottom: 28px;">
    <div style="display: inline-flex; align-items: center; gap: 6px; background: #E6F5F4; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; color: #009C8C; margin-bottom: 12px;">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        Preenchido automaticamente pelo Estudo de Caso
    </div>
    @if($ec_diagnostico_perfil)
        <div style="background: #F0FDFB; border: 1px solid #99E6E0; border-radius: 8px; padding: 14px 16px; font-size: 13px; color: #374151; white-space: pre-wrap; line-height: 1.7; min-height: 80px;">{{ $ec_diagnostico_perfil }}</div>
    @else
        <div style="background: #FFF7ED; border: 1px solid #FED7AA; border-radius: 8px; padding: 14px 16px; font-size: 13px; color: #92400E; font-style: italic;">
            Campo não preenchido no Estudo de Caso. Preencha a seção "FAZ PARTE DO PAEE" no Estudo de Caso para auto-completar este campo.
        </div>
    @endif
    <input type="hidden" name="diagnostico_perfil" value="{{ $ec_diagnostico_perfil }}">
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ OBJETIVOS DO AEE ═══ --}}
{!! $section('Objetivos do AEE', 'Objetivos gerais e específicos do atendimento educacional especializado.') !!}

<div style="margin-bottom: 28px;">
    {!! $textarea('objetivo_geral', 'Objetivo Geral', 3,
        'Ex: Garantir o acesso ao currículo, a participação nas atividades escolares e o desenvolvimento da aprendizagem...') !!}
    {!! $textarea('objetivos_especificos', 'Objetivos Específicos', 5,
        'Liste os objetivos específicos do atendimento, separados por linha...') !!}
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ RECURSOS E ESTRATÉGIAS (preenchido automaticamente do Estudo de Caso) ═══ --}}
{!! $section('Recursos e Estratégias', 'Preenchido automaticamente a partir do Estudo de Caso.') !!}

<div style="margin-bottom: 28px;">
    <div style="display: inline-flex; align-items: center; gap: 6px; background: #E6F5F4; border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; color: #009C8C; margin-bottom: 16px;">
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
        Preenchido automaticamente pelo Estudo de Caso
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Tecnologias Assistivas</label>
        @if($ec_tecnologias_assistivas)
            <div style="background: #F0FDFB; border: 1px solid #99E6E0; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #374151; white-space: pre-wrap; line-height: 1.7; min-height: 60px;">{{ $ec_tecnologias_assistivas }}</div>
        @else
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #9CA3AF; font-style: italic;">Não preenchido no Estudo de Caso.</div>
        @endif
        <input type="hidden" name="tecnologias_assistivas" value="{{ $ec_tecnologias_assistivas }}">
    </div>

    <div style="margin-bottom: 16px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Adaptações para o AEE</label>
        @if($ec_adaptacoes)
            <div style="background: #F0FDFB; border: 1px solid #99E6E0; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #374151; white-space: pre-wrap; line-height: 1.7; min-height: 60px;">{{ $ec_adaptacoes }}</div>
        @else
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #9CA3AF; font-style: italic;">Não preenchido no Estudo de Caso.</div>
        @endif
        <input type="hidden" name="adaptacoes" value="{{ $ec_adaptacoes }}">
    </div>

    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Metodologias e Estratégias para AEE</label>
        @if($ec_metodologias)
            <div style="background: #F0FDFB; border: 1px solid #99E6E0; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #374151; white-space: pre-wrap; line-height: 1.7; min-height: 60px;">{{ $ec_metodologias }}</div>
        @else
            <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px 16px; font-size: 13px; color: #9CA3AF; font-style: italic;">Não preenchido no Estudo de Caso.</div>
        @endif
        <input type="hidden" name="metodologias" value="{{ $ec_metodologias }}">
    </div>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ ORGANIZAÇÃO DO ATENDIMENTO ═══ --}}
{!! $section('Organização do Atendimento', 'Frequência, duração e local do atendimento especializado.') !!}

<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
        @foreach([
            'frequencia'        => 'Frequência',
            'duracao'           => 'Duração',
            'local_atendimento' => 'Local',
        ] as $field => $label)
        <div>
            <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">{{ $label }}</label>
            <input type="text" name="{{ $field }}" value="{{ $fn($field) }}"
                   placeholder="{{ $field === 'frequencia' ? 'Ex: 2x por semana' : ($field === 'duracao' ? 'Ex: 50 minutos' : 'Ex: Sala de Recurso') }}"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        @endforeach
    </div>
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ AVALIAÇÃO E MONITORAMENTO ═══ --}}
{!! $section('Avaliação e Monitoramento', 'Critérios e periodicidade de avaliação do plano.') !!}

<div style="margin-bottom: 28px;">
    {!! $textarea('criterios_avaliacao', 'Critérios de Avaliação', 4,
        'Descreva como o progresso do estudante será avaliado...') !!}
    {!! $textarea('periodicidade_avaliacao', 'Periodicidade de Avaliação e Atualização do Plano', 2,
        'Ex: Bimestral, semestral, conforme necessidade...') !!}
</div>

<hr style="border: none; border-top: 1px solid #F3F4F6; margin-bottom: 28px;">

{{-- ═══ EQUIPE RESPONSÁVEL ═══ --}}
{!! $section('Equipe Responsável', 'Profissionais envolvidos na elaboração e acompanhamento do PAEE.') !!}

<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Profissional do AEE</label>
            <input type="text" name="profissional_aee" value="{{ $fn('profissional_aee') }}"
                   placeholder="Nome completo"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        <div>
            <label style="display: block; font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 8px;">Equipe Colaborativa</label>
            <input type="text" name="equipe_colaborativa" value="{{ $fn('equipe_colaborativa') }}"
                   placeholder="Ex: Professor titular, psicólogo, terapeuta..."
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
    </div>
</div>
