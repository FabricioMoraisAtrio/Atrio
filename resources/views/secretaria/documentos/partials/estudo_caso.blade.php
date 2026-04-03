@php
    $content = $documento->content ?? [];
    $aluno   = isset($documento) ? $documento->student : $aluno;
    $aluno->loadMissing(['schoolClasses' => fn($q) => $q->where('year', date('Y'))]);
    $turma   = $aluno->schoolClasses->first();

    // Diagnóstico: monta lista de siglas ativas
    $transtornos = config('transtornos');
    $diagnostico = collect($transtornos)
        ->filter(fn($v, $k) => $aluno->$k)
        ->map(fn($v) => $v[0])
        ->implode(', ');
    if ($aluno->condition) $diagnostico .= ($diagnostico ? ', ' : '') . $aluno->condition;

    $responsaveis = collect([$aluno->responsavel_nome, $aluno->responsavel_2_nome])
        ->filter()->implode(' / ');
@endphp

{{-- ═══ CABEÇALHO AUTOPREENCHIDO (somente leitura) ═══ --}}
<div style="border: 1px solid #E5E7EB; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
    <div style="background: #B8C8DC; padding: 8px 16px;">
        <p style="font-size: 11px; font-weight: 700; color: #1a2a3a; letter-spacing: 1px; text-transform: uppercase; margin: 0;">Dados de Identificação</p>
    </div>
    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
        <tr style="border-bottom: 1px solid #F3F4F6;">
            <td style="padding: 8px 14px; width: 130px; font-size: 11px; font-weight: 600; color: #6B7280;">Estudante</td>
            <td style="padding: 8px 14px; color: #111827; font-weight: 600;">{{ $aluno->name }}</td>
        </tr>
        <tr style="border-bottom: 1px solid #F3F4F6;">
            <td style="padding: 8px 14px; font-size: 11px; font-weight: 600; color: #6B7280;">Nascimento</td>
            <td style="padding: 8px 14px; color: #374151; display: flex; gap: 32px;">
                <span>{{ $aluno->birth_date ? $aluno->birth_date->format('d/m/Y') : '—' }}</span>
                <span style="color: #6B7280; font-size: 11px;">
                    Idade:
                    <strong style="color: #374151;">
                        {{ $aluno->birth_date ? $aluno->birth_date->age . ' anos' : '—' }}
                    </strong>
                </span>
            </td>
        </tr>
        @if($diagnostico)
        <tr style="border-bottom: 1px solid #F3F4F6;">
            <td style="padding: 8px 14px; font-size: 11px; font-weight: 600; color: #6B7280;">Diagnóstico</td>
            <td style="padding: 8px 14px; color: #374151;">{{ $diagnostico }}</td>
        </tr>
        @endif
        @if($responsaveis)
        <tr style="border-bottom: 1px solid #F3F4F6;">
            <td style="padding: 8px 14px; font-size: 11px; font-weight: 600; color: #6B7280;">Responsáveis</td>
            <td style="padding: 8px 14px; color: #374151;">{{ $responsaveis }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 8px 14px; font-size: 11px; font-weight: 600; color: #6B7280;">Turma / Turno</td>
            <td style="padding: 8px 14px; color: #374151;">
                @if($turma)
                    {{ $turma->name }} · {{ $turma->shift }} · {{ $documento->year }}
                @else
                    Ano letivo: {{ $documento->year }}
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- ═══ EQUIPE DO COLÉGIO ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #7C3700; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 16px;">Equipe do Colégio</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        @foreach(['professor_titular' => 'Professor Titular/Conselheiro', 'soe' => 'SOE', 'scp' => 'SCP', 'saee' => 'SAEE'] as $field => $label)
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">{{ $label }}</label>
            <input type="text" name="{{ $field }}" value="{{ old($field, $content[$field] ?? '') }}"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        @endforeach
    </div>

    <p style="font-size: 11px; font-weight: 700; color: #009C8C; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px; padding-top: 12px; border-top: 1px solid #F3F4F6;">Equipe Multidisciplinar</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
        @foreach(['psicologa' => 'Psicóloga', 'psiquiatra' => 'Psiquiatra', 'psicopedagoga' => 'Psicopedagoga', 'fisioterapeuta' => 'Fisioterapeuta / Educador Físico', 'at' => 'AT'] as $field => $label)
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">{{ $label }}</label>
            <input type="text" name="{{ $field }}" value="{{ old($field, $content[$field] ?? '') }}"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='#009C8C'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        @endforeach
    </div>
</div>

{{-- ═══ TRAJETÓRIA ESCOLAR ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #7C3700; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Histórico de Vida e Trajetória Escolar</p>
    <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 16px;">Histórico escolar e familiar relevante desde o início da escolarização.</p>
    <textarea name="historico" rows="5"
              placeholder="Descreva o histórico escolar e familiar relevante..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">{{ old('historico', $content['historico'] ?? '') }}</textarea>
</div>

{{-- ═══ CARACTERIZAÇÃO: ASPECTOS ═══ --}}
@php
$aspectos = [
    'A) Aspectos Cognitivos e Acadêmicos' => [
        'atencao'         => 'Atenção',
        'memoria'         => 'Memória',
        'raciocinio_logico' => 'Raciocínio lógico',
        'leitura_escrita' => 'Leitura e Escrita',
        'matematica'      => 'Matemática',
        'organizacao'     => 'Organização',
    ],
    'B) Aspectos Comunicacionais' => [
        'linguagem_expressiva'    => 'Linguagem expressiva (fala)',
        'linguagem_receptiva'     => 'Linguagem receptiva (compreensão)',
        'comunicacao_alternativa' => 'Comunicação alternativa',
    ],
    'C) Aspectos Socioemocionais e Comportamentais' => [
        'interacao_social'     => 'Interação social',
        'autonomia'            => 'Autonomia',
        'regulacao_emocional'  => 'Regulação emocional',
        'interesses_motivacao' => 'Interesses e motivação',
    ],
    'D) Aspectos Sensoriais e Motores' => [
        'motricidade_grossa'       => 'Motricidade grossa',
        'motricidade_fina'         => 'Motricidade fina',
        'processamento_sensorial'  => 'Processamento sensorial',
    ],
];
@endphp

<div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
    <div style="background: #B8C8DC; padding: 10px 16px;">
        <p style="font-size: 11px; font-weight: 700; color: #1a2a3a; letter-spacing: 1px; text-transform: uppercase; margin: 0;">Caracterização do(a) Estudante</p>
    </div>
    @foreach($aspectos as $titulo => $campos)
    <div style="border-top: 1px solid #F3F4F6;">
        <div style="background: #E8EFF6; padding: 8px 16px;">
            <p style="font-size: 11px; font-weight: 700; color: #1a2a3a; margin: 0;">{{ $titulo }}</p>
        </div>
        @foreach($campos as $field => $label)
        <div style="display: flex; align-items: center; border-top: 1px solid #F9FAFB; padding: 0 16px; gap: 12px;">
            <span style="font-size: 12px; font-weight: 600; color: #374151; width: 200px; flex-shrink: 0; padding: 10px 0;">{{ $label }}</span>
            <input type="text" name="{{ $field }}" value="{{ old($field, $content[$field] ?? '') }}"
                   placeholder="Descreva..."
                   style="flex: 1; border: none; border-bottom: 1px solid #E5E7EB; padding: 8px 0; font-size: 13px; color: #111827; outline: none; background: transparent;"
                   onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        @endforeach
    </div>
    @endforeach
</div>

{{-- ═══ ANÁLISE DA PARTICIPAÇÃO ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
    <div style="background: #B8C8DC; padding: 10px 16px;">
        <p style="font-size: 11px; font-weight: 700; color: #1a2a3a; letter-spacing: 1px; text-transform: uppercase; margin: 0;">Análise da Participação e da Rotina Escolar</p>
    </div>
    @foreach(['rotina' => 'Rotina', 'participacao_aulas' => 'Participação nas aulas', 'participacao_intervalos' => 'Participação nos intervalos', 'apoios_necessarios' => 'Apoios necessários'] as $field => $label)
    <div style="display: flex; align-items: center; border-top: 1px solid #F9FAFB; padding: 0 16px; gap: 12px;">
        <span style="font-size: 12px; font-weight: 600; color: #374151; width: 200px; flex-shrink: 0; padding: 10px 0;">{{ $label }}</span>
        <input type="text" name="{{ $field }}" value="{{ old($field, $content[$field] ?? '') }}"
               placeholder="Descreva..."
               style="flex: 1; border: none; border-bottom: 1px solid #E5E7EB; padding: 8px 0; font-size: 13px; color: #111827; outline: none; background: transparent;"
               onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">
    </div>
    @endforeach
</div>

{{-- ═══ SÍNTESE ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
    <div style="background: #B8C8DC; padding: 10px 16px;">
        <p style="font-size: 11px; font-weight: 700; color: #1a2a3a; letter-spacing: 1px; text-transform: uppercase; margin: 0;">Síntese do Estudo de Caso</p>
    </div>
    @foreach(['potencialidades' => 'Principais potencialidades', 'barreiras' => 'Principais barreiras para a aprendizagem e participação', 'encaminhamentos' => 'Encaminhamentos necessários (o que a escola pode fazer)'] as $field => $label)
    <div style="border-top: 1px solid #F9FAFB; padding: 12px 16px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 8px;">{{ $label }}</label>
        <textarea name="{{ $field }}" rows="{{ $field === 'encaminhamentos' ? 5 : 3 }}"
                  placeholder="Descreva..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 10px 12px; font-size: 13px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">{{ old($field, $content[$field] ?? '') }}</textarea>
    </div>
    @endforeach
</div>

{{-- ═══ RESPONSÁVEIS PELA ELABORAÇÃO ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #7C3700; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 16px;">Responsáveis pela Elaboração do Estudo de Caso</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
        @foreach(['profissional_aee' => 'Profissional do AEE', 'orientador_educacional' => 'Orientador(a) Educacional'] as $field => $label)
        <div>
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">{{ $label }}</label>
            <input type="text" name="{{ $field }}" value="{{ old($field, $content[$field] ?? '') }}"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                   onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">
        </div>
        @endforeach
    </div>
    <div>
        <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Contribuições</label>
        <textarea name="contribuicoes" rows="2"
                  placeholder="Outras contribuições ou observações relevantes..."
                  style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 10px 12px; font-size: 13px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                  onfocus="this.style.borderColor='#7C3700'" onblur="this.style.borderColor='#E5E7EB'">{{ old('contribuicoes', $content['contribuicoes'] ?? '') }}</textarea>
    </div>
</div>
