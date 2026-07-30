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
        <p style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 2px;">' . $title . '</p>'
        . ($subtitle ? '<p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">' . $subtitle . '</p>' : '') .
    '</div>';

$textarea = fn(string $name, string $label, int $rows = 3, string $placeholder = '') =>
    '<div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">' . $label . '</label>
        <textarea name="' . $name . '" rows="' . $rows . '" placeholder="' . $placeholder . '"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor=\'' . $accent . '\'" onblur="this.style.borderColor=\'#E5E7EB\'">'
            . e(old($name, $content[$name] ?? '')) .
        '</textarea>
    </div>';
@endphp

{{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
<div style="background: var(--teal-bg); border: 1px solid var(--teal); border-radius: 10px; padding: 20px; margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Identificação do Estudante — preenchido automaticamente</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; color: var(--text-2);">
        <div><span style="color:var(--text-4); font-weight:600;">Escola:</span> {{ $aluno->school?->name }}</div>
        <div><span style="color:var(--text-4); font-weight:600;">Matrícula:</span> {{ $aluno->registration_number }}</div>
        <div><span style="color:var(--text-4); font-weight:600;">Aluno(a):</span> {{ $aluno->name }}</div>
        <div>
            <span style="color:var(--text-4); font-weight:600;">Data de Nascimento:</span>
            {{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '—' }}
            @if($aluno->birth_date)
                <span style="color:var(--text-4);"> · </span>{{ $aluno->birth_date->age }} anos
            @endif
        </div>
        <div>
            <span style="color:var(--text-4); font-weight:600;">Turma / Turno:</span>
            {{ $turma ? $turma->name . ' · ' . $turma->shift : '—' }}
        </div>
        <div><span style="color:var(--text-4); font-weight:600;">Ano Letivo:</span> {{ $anoLetivo }}</div>
        @if($aluno->responsavel_nome)
        <div><span style="color:var(--text-4); font-weight:600;">Responsável:</span> {{ $aluno->responsavel_nome }}</div>
        @endif
        @if($aluno->responsavel_2_nome)
        <div><span style="color:var(--text-4); font-weight:600;">Responsável 2:</span> {{ $aluno->responsavel_2_nome }}</div>
        @endif
        @if($diagnostico)
        <div style="grid-column: 1 / -1;">
            <span style="color:var(--text-4); font-weight:600;">Diagnóstico / Laudo:</span> {{ $diagnostico }}
        </div>
        @endif
    </div>
</div>

{{-- ═══ DIAGNÓSTICO / PERFIL ═══ --}}
{!! $section('Diagnóstico / Perfil', 'Necessidades educacionais e barreiras identificadas.') !!}
<p style="font-size: 12px; color: var(--danger); margin: -6px 0 14px;">* Selecione ao menos um item abaixo.</p>

@php
$diagOpcoes = [
    'Deficiência intelectual',
    'Transtorno do espectro autista (TEA)',
    'TDAH',
    'Deficiência física',
    'Deficiência auditiva',
    'Deficiência visual',
    'Altas habilidades/superdotação',
    'Dificuldades específicas de aprendizagem',
];
$diagSelected = old('diagnostico_perfil', $content['diagnostico_perfil'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($diagOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="diagnostico_perfil[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$diagSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="diagnostico_perfil_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ old('diagnostico_perfil_obs', $content['diagnostico_perfil_obs'] ?? '') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ RECURSOS E ESTRATÉGIAS ═══ --}}
{!! $section('Recursos e Estratégias', 'Recursos e estratégias utilizados no atendimento.') !!}

@php
$recOpcoes = [
    'Material adaptado',
    'Recursos visuais',
    'Tecnologias assistivas',
    'Jogos pedagógicos',
    'Rotinas estruturadas',
    'Comunicação alternativa',
    'Atividades práticas',
    'Ensino individualizado',
];
$recSelected = old('recursos_estrategias', $content['recursos_estrategias'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($recOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="recursos_estrategias[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$recSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="recursos_estrategias_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ old('recursos_estrategias_obs', $content['recursos_estrategias_obs'] ?? '') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ ORGANIZAÇÃO DO ATENDIMENTO ═══ --}}
{!! $section('Organização do Atendimento', 'Formato e frequência do atendimento especializado.') !!}

@php
$orgOpcoes = [
    'Atendimento individual',
    'Atendimento em grupo',
    '1 vez por semana',
    '2 vezes por semana',
    'Mais de 2 vezes por semana',
    'No contraturno',
    'No mesmo turno',
];
$orgSelected = old('organizacao_atendimento', $content['organizacao_atendimento'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($orgOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="organizacao_atendimento[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$orgSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="organizacao_atendimento_obs" rows="2"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ old('organizacao_atendimento_obs', $content['organizacao_atendimento_obs'] ?? '') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ AVALIAÇÃO E MONITORAMENTO ═══ --}}
{!! $section('Avaliação e Monitoramento', 'Critérios e periodicidade de avaliação.') !!}

@php
$avalOpcoes = [
    'Observação contínua',
    'Registros descritivos',
    'Avaliações adaptadas',
    'Relatórios periódicos',
    'Reuniões com equipe pedagógica',
    'Reuniões com família',
];
$avalSelected = old('avaliacao_monitoramento', $content['avaliacao_monitoramento'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($avalOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="avaliacao_monitoramento[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$avalSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="avaliacao_monitoramento_obs" rows="2"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ old('avaliacao_monitoramento_obs', $content['avaliacao_monitoramento_obs'] ?? '') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ EQUIPE RESPONSÁVEL ═══ --}}
{!! $section('Equipe Responsável', 'Profissionais envolvidos na elaboração e acompanhamento do PAEE.') !!}

@php
$equipeParticipantes = old('equipe_participantes', $content['equipe_participantes'] ?? [['nome' => '', 'cargo' => '']]);
if (empty($equipeParticipantes)) $equipeParticipantes = [['nome' => '', 'cargo' => '']];
@endphp

<div id="equipe-lista" style="margin-bottom: 12px;">
    @foreach($equipeParticipantes as $i => $p)
    <div class="equipe-row" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end; margin-bottom: 12px;">
        <div>
            @if($i === 0)<label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;">Nome completo</label>@endif
            <input type="text" name="equipe_participantes[{{ $i }}][nome]"
                   value="{{ $p['nome'] ?? '' }}"
                   placeholder="Nome completo"
                   style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">
        </div>
        <div>
            @if($i === 0)<label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 6px;">Cargo / Função</label>@endif
            <input type="text" name="equipe_participantes[{{ $i }}][cargo]"
                   value="{{ $p['cargo'] ?? '' }}"
                   placeholder="Ex: Profissional do AEE"
                   style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">
        </div>
        <div style="{{ $i === 0 ? 'padding-top: 22px;' : '' }}">
            <button type="button" onclick="removerParticipante(this)"
                    style="background: none; border: none; cursor: pointer; color: var(--danger); font-size: 18px; line-height: 1; padding: 4px;" title="Remover">×</button>
        </div>
    </div>
    @endforeach
</div>

<button type="button" onclick="adicionarParticipante()"
        style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: {{ $accent }}; background: none; border: 1px dashed {{ $accent }}; border-radius: 8px; padding: 8px 14px; cursor: pointer; margin-bottom: 28px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
    Adicionar participante
</button>

<script>
(function() {
    var accent = '{{ $accent }}';
    var idx = {{ count($equipeParticipantes) }};

    window.adicionarParticipante = function() {
        var lista = document.getElementById('equipe-lista');
        var row = document.createElement('div');
        row.className = 'equipe-row';
        row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end; margin-bottom: 12px;';
        row.innerHTML =
            '<div><input type="text" name="equipe_participantes[' + idx + '][nome]" placeholder="Nome completo"' +
            ' style="width:100%;border:none;border-bottom:2px solid #E5E7EB;padding:8px 0;font-size:14px;color:#111827;outline:none;background:transparent;box-sizing:border-box;"' +
            ' onfocus="this.style.borderColor=\'' + accent + '\'" onblur="this.style.borderColor=\'#E5E7EB\'"></div>' +
            '<div><input type="text" name="equipe_participantes[' + idx + '][cargo]" placeholder="Ex: Profissional do AEE"' +
            ' style="width:100%;border:none;border-bottom:2px solid #E5E7EB;padding:8px 0;font-size:14px;color:#111827;outline:none;background:transparent;box-sizing:border-box;"' +
            ' onfocus="this.style.borderColor=\'' + accent + '\'" onblur="this.style.borderColor=\'#E5E7EB\'"></div>' +
            '<div><button type="button" onclick="removerParticipante(this)" style="background:none;border:none;cursor:pointer;color:#EF4444;font-size:18px;line-height:1;padding:4px;" title="Remover">×</button></div>';
        lista.appendChild(row);
        idx++;
    };

    window.removerParticipante = function(btn) {
        var rows = document.querySelectorAll('.equipe-row');
        if (rows.length <= 1) return;
        btn.closest('.equipe-row').remove();
    };
})();
</script>
