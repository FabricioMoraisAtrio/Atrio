@php
    $content = $documento->content ?? [];

    $itensAcad = [
        ['meta' => 'Ler e compreender textos diversos',                           'responsavel' => ''],
        ['meta' => 'Estruturar parágrafos de forma compreensível',                'responsavel' => ''],
        ['meta' => 'Expressar-se oralmente de forma clara e funcional',           'responsavel' => ''],
        ['meta' => 'Aplicar conhecimentos em situações-problema',                 'responsavel' => ''],
        ['meta' => 'Estabelecer relação entre causa e efeito',                    'responsavel' => ''],
        ['meta' => 'Demonstrar curiosidade e interesse nas atividades',           'responsavel' => ''],
        ['meta' => 'Participar ativamente das atividades propostas',              'responsavel' => ''],
    ];

    $itensSocio = [
        ['meta' => 'Cumprir as regras e combinados da turma',                     'responsavel' => ''],
        ['meta' => 'Reconhecer as próprias emoções e limitações',                 'responsavel' => ''],
        ['meta' => 'Demonstrar senso de responsabilidade',                        'responsavel' => ''],
        ['meta' => 'Demonstrar escuta atenta',                                    'responsavel' => ''],
        ['meta' => 'Relacionar-se com os colegas nas situações cotidianas',       'responsavel' => ''],
    ];

    $itensFunc = [
        ['meta' => 'Demonstrar autonomia na realização das tarefas',              'responsavel' => ''],
        ['meta' => 'Demonstrar autocontrole em situações escolares',              'responsavel' => ''],
        ['meta' => 'Demonstrar organização pessoal',                              'responsavel' => ''],
        ['meta' => 'Demonstrar atenção e concentração',                           'responsavel' => ''],
        ['meta' => 'Demonstrar memória auditiva e visual',                        'responsavel' => ''],
    ];

    $habAcad  = !empty($content['habilidades_academicas'])       ? $content['habilidades_academicas']      : $itensAcad;
    $habSocio = !empty($content['habilidades_socioemocionais'])   ? $content['habilidades_socioemocionais']  : $itensSocio;
    $habFunc  = !empty($content['habilidades_funcionais'])        ? $content['habilidades_funcionais']       : $itensFunc;

    $colunas = [
        'realiza_sem_suporte' => 'Realiza sem suporte',
        'realiza_com_apoio'   => 'Realiza com apoio',
        'ainda_nao_realiza'   => 'Ainda não realiza',
        'nao_observado'       => 'Não observado',
    ];

@endphp

{{-- ═══ DISCIPLINA ═══ --}}
<div style="margin-bottom: 24px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Disciplina / Matéria</label>
    <input type="text" name="materia" value="{{ old('materia', $content['materia'] ?? '') }}"
           placeholder="Ex: Matemática, Português, Ciências..."
           style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
</div>

{{-- ═══ ESTRATÉGIAS PEDAGÓGICAS ═══ --}}
<div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Estratégias Pedagógicas</p>
    <p style="font-size: 12px; color: #9CA3AF; margin: 0 0 16px;">Descreva como você vai adaptar o ensino desta disciplina para este aluno.</p>
    <textarea name="estrategias_pedagogicas" rows="4"
              placeholder="Ex: Uso de material concreto, atividades em dupla, rotina estruturada, apoio visual..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('estrategias_pedagogicas', $content['estrategias_pedagogicas'] ?? '') }}</textarea>
</div>

{{-- ═══ INVENTÁRIO DE HABILIDADES ═══ --}}
@php
    $inventarios = [
        ['key' => 'habilidades_academicas',      'label' => 'Habilidades Acadêmicas',      'itens' => $habAcad,  'cor' => '#004B8D'],
        ['key' => 'habilidades_socioemocionais',  'label' => 'Habilidades Socioemocionais', 'itens' => $habSocio, 'cor' => '#009C8C'],
        ['key' => 'habilidades_funcionais',       'label' => 'Habilidades Funcionais',      'itens' => $habFunc,  'cor' => '#7C3700'],
    ];
@endphp

@foreach($inventarios as $inv)
<div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
    <div style="padding: 14px 20px; border-bottom: 1px solid #F3F4F6; background: rgba(0,0,0,0.02);">
        <p style="font-size: 11px; font-weight: 700; color: {{ $inv['cor'] }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">{{ $inv['label'] }}</p>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background: #F9FAFB;">
                    <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280; width: 36%;">Metas / Objetivos</th>
                    @foreach($colunas as $colLabel)
                        <th style="text-align: center; padding: 10px 6px; font-size: 10px; font-weight: 600; color: #6B7280; width: 10%;">{{ $colLabel }}</th>
                    @endforeach
                    <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280; width: 14%;">Responsável</th>
                    <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: #6B7280;">Observações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inv['itens'] as $i => $item)
                <tr style="border-top: 1px solid #F3F4F6; {{ $i % 2 !== 0 ? 'background: #FAFAFA;' : '' }}">
                    <td style="padding: 10px 14px; color: #374151; font-size: 13px;">
                        {{ $item['meta'] }}
                        <input type="hidden" name="{{ $inv['key'] }}[{{ $i }}][meta]" value="{{ $item['meta'] }}">
                    </td>
                    @foreach(array_keys($colunas) as $col)
                    <td style="text-align: center; padding: 10px 6px;">
                        <input type="checkbox"
                               name="{{ $inv['key'] }}[{{ $i }}][{{ $col }}]"
                               value="1"
                               {{ !empty($item[$col]) ? 'checked' : '' }}
                               style="width: 15px; height: 15px; cursor: pointer; accent-color: {{ $inv['cor'] }};">
                    </td>
                    @endforeach
                    <td style="padding: 10px 14px;">
                        <input type="text" name="{{ $inv['key'] }}[{{ $i }}][responsavel]"
                               value="{{ $item['responsavel'] ?? '' }}"
                               placeholder="Prof. ..."
                               style="width: 100%; border: none; border-bottom: 1px solid #E5E7EB; padding: 4px 0; font-size: 12px; color: #374151; outline: none; background: transparent;"
                               onfocus="this.style.borderColor='{{ $inv['cor'] }}'" onblur="this.style.borderColor='#E5E7EB'">
                    </td>
                    <td style="padding: 10px 14px;">
                        <input type="text" name="{{ $inv['key'] }}[{{ $i }}][observacoes]"
                               value="{{ $item['observacoes'] ?? '' }}"
                               placeholder="..."
                               style="width: 100%; border: none; border-bottom: 1px solid #E5E7EB; padding: 4px 0; font-size: 12px; color: #374151; outline: none; background: transparent;"
                               onfocus="this.style.borderColor='{{ $inv['cor'] }}'" onblur="this.style.borderColor='#E5E7EB'">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

{{-- ═══ OBSERVAÇÕES LIVRES ═══ --}}
<div>
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Observações livres</label>
    <textarea name="observacoes_livres" rows="3"
              placeholder="Observações adicionais sobre o desempenho do aluno nesta disciplina..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('observacoes_livres', $content['observacoes_livres'] ?? '') }}</textarea>
</div>

