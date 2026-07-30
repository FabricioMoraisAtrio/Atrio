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
        <p style="font-size: 15px; font-weight: 700; color: var(--text-1); margin: 0 0 2px;">' . $title . '</p>'
        . ($subtitle ? '<p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">' . $subtitle . '</p>' : '') .
    '</div>';

$textarea = fn(string $name, string $label, int $rows = 3) =>
    '<div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">' . $label . '</label>
        <textarea name="' . $name . '" rows="' . $rows . '"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor=\'' . $accent . '\'" onblur="this.style.borderColor=\'#E5E7EB\'">'
            . e(old($name, $content[$name] ?? '')) .
        '</textarea>
    </div>';
@endphp

{{-- ═══ IDENTIFICAÇÃO (somente leitura) ═══ --}}
<div style="background: var(--bg-subtle); border: 1px solid var(--border); border-radius: 10px; padding: 20px; margin-bottom: 24px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--text-4); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 14px;">Identificação — preenchido automaticamente</p>
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
            <span style="color:var(--text-4); font-weight:600;">Turma/Turno:</span>
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

{{-- ═══ IDENTIFICAÇÃO — Contexto Familiar ═══ --}}
{!! $section('Identificação', 'Dados do estudante e contexto escolar.') !!}

<div style="margin-bottom: 28px;">
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Breve descrição do contexto familiar</label>
        <textarea name="contexto_familiar" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('contexto_familiar') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ HISTÓRICO ESCOLAR ═══ --}}
{!! $section('Histórico Escolar', 'Trajetória e experiências anteriores.') !!}

<div style="margin-bottom: 28px;">
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Resumo da vida escolar (escolas anteriores, retenções ou avanços) <span style="color: var(--danger);">*</span></label>
        <textarea name="historico_escolar" rows="4" required
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('historico_escolar') }}</textarea>
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Frequência e assiduidade</label>
        <textarea name="frequencia_assiduidade" rows="2"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('frequencia_assiduidade') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ OBSERVAÇÕES PEDAGÓGICAS ═══ --}}
{!! $section('Observações Pedagógicas', 'Comportamento, aprendizagem e interação.') !!}

@php
$obsOpcoes = [
    'Participa ativamente das aulas',
    'Participa quando estimulado',
    'Dificuldade de concentração',
    'Dispersa-se com facilidade',
    'Interage bem com colegas',
    'Dificuldade de interação social',
    'Apresenta comportamento agressivo',
    'Apresenta comportamento retraído',
    'Segue instruções com autonomia',
    'Necessita de mediação constante',
    'Acompanha o ritmo da turma',
    'Necessita de adaptação de atividades',
    'Dificuldade na leitura',
    'Dificuldade na escrita',
    'Dificuldade em matemática',
    'Aprende melhor com apoio visual',
    'Aprende melhor com atividades práticas',
    'Necessita de repetição frequente',
    'Demonstra autonomia na aprendizagem',
];
$obsSelected = old('obs_pedagogicas', $content['obs_pedagogicas'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($obsOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="obs_pedagogicas[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$obsSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="obs_pedagogicas_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('obs_pedagogicas_obs') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ BARREIRAS IDENTIFICADAS ═══ --}}
{!! $section('Barreiras Identificadas', 'Dificuldades e limitações encontradas.') !!}

@php
$barreiraOpcoes = [
    'Barreiras pedagógicas (metodologia inadequada)',
    'Barreiras comunicacionais',
    'Barreiras atitudinais (preconceito, exclusão)',
    'Barreiras físicas (acessibilidade)',
    'Barreiras tecnológicas',
    'Falta de recursos adaptados',
    'Dificuldade de atenção',
    'Dificuldade de compreensão de instruções',
    'Ansiedade',
    'Baixa autoestima',
];
$barreiraSelected = old('barreiras', $content['barreiras'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($barreiraOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="barreiras[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$barreiraSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="barreiras_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('barreiras_obs') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ POTENCIALIDADES ═══ --}}
{!! $section('Potencialidades', 'Habilidades e pontos fortes.') !!}

@php
$potOpcoes = [
    'Boa comunicação oral',
    'Facilidade com tecnologia',
    'Boa memória',
    'Criatividade',
    'Interesse por atividades práticas',
    'Interesse por leitura',
    'Facilidade em matemática',
    'Facilidade em artes',
    'Boa interação social',
    'Persistência nas atividades',
];
$potSelected = old('potencialidades', $content['potencialidades'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($potOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="potencialidades[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$potSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="potencialidades_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('potencialidades_obs') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ ENCAMINHAMENTOS ═══ --}}
{!! $section('Encaminhamentos', 'Sugestões e ações pedagógicas.') !!}

@php
$encOpcoes = [
    'Encaminhamento para AEE',
    'Adaptação curricular',
    'Uso de tecnologia assistiva',
    'Atendimento individualizado',
    'Apoio de profissional auxiliar',
    'Acompanhamento psicológico',
    'Acompanhamento fonoaudiológico',
    'Reunião com responsáveis',
    'Monitoramento contínuo',
];
$encSelected = old('encaminhamentos', $content['encaminhamentos'] ?? []);
@endphp
<div style="margin-bottom: 28px;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 16px;">
        @foreach($encOpcoes as $opt)
        <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-2); cursor: pointer;">
            <input type="checkbox" name="encaminhamentos[]" value="{{ $opt }}"
                   {{ in_array($opt, (array)$encSelected) ? 'checked' : '' }}
                   style="accent-color: {{ $accent }}; width: 15px; height: 15px; flex-shrink: 0;">
            {{ $opt }}
        </label>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-2); margin-bottom: 8px;">Observações complementares</label>
        <textarea name="encaminhamentos_obs" rows="3"
                  style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.7; background: transparent;"
                  onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $fn('encaminhamentos_obs') }}</textarea>
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-sub); margin-bottom: 28px;">

{{-- ═══ EQUIPE RESPONSÁVEL ═══ --}}
{!! $section('Equipe Responsável', 'Profissionais envolvidos na formulação deste documento.') !!}

@php
$equipeParticipantes = old('equipe_participantes', $content['equipe_participantes'] ?? [['nome' => '', 'cargo' => '']]);
if (empty($equipeParticipantes)) $equipeParticipantes = [['nome' => '', 'cargo' => '']];
@endphp

<div id="equipe-lista" style="margin-bottom: 16px;">
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
                   placeholder="Ex: Coordenador Pedagógico"
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
        style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: {{ $accent }}; background: none; border: 1px dashed {{ $accent }}; border-radius: 8px; padding: 8px 14px; cursor: pointer;">
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
            '<div><input type="text" name="equipe_participantes[' + idx + '][cargo]" placeholder="Ex: Coordenador Pedagógico"' +
            ' style="width:100%;border:none;border-bottom:2px solid #E5E7EB;padding:8px 0;font-size:14px;color:#111827;outline:none;background:transparent;box-sizing:border-box;"' +
            ' onfocus="this.style.borderColor=\'' + accent + '\'" onblur="this.style.borderColor=\'#E5E7EB\'"></div>' +
            '<div><button type="button" onclick="removerParticipante(this)" style="background:none;border:none;cursor:pointer;color:#EF4444;font-size:18px;line-height:1;padding:4px;" title="Remover">×</button></div>';
        lista.appendChild(row);
        idx++;
    };

    window.removerParticipante = function(btn) {
        var rows = document.querySelectorAll('.equipe-row');
        if (rows.length <= 1) return; // mínimo 1 linha
        btn.closest('.equipe-row').remove();
    };
})();
</script>

{{-- Elaborado por — preenchido automaticamente pelo usuário logado --}}
<input type="hidden" name="elaborado_por" value="{{ auth()->user()->name }}">
