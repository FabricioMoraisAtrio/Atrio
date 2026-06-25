@php
    $content  = isset($documento) ? ($documento->content ?? []) : [];

    $colunas = [
        'realiza_sem_suporte' => 'Realiza sem suporte',
        'realiza_com_apoio'   => 'Realiza com apoio',
        'ainda_nao_realiza'   => 'Ainda não realiza',
        'nao_observado'       => 'Não observado',
    ];
@endphp

{{-- ═══ MATÉRIA (auto-preenchida, somente leitura) ═══ --}}
<div style="margin-bottom: 24px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Disciplina / Matéria</label>
    @if($subject)
        <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--accent-bg); border-radius: 8px;">
            <span style="font-size: 15px; font-weight: 700; color: var(--accent-text);">{{ $subject->name }}</span>
            <span style="font-size: 11px; color: var(--text-3);">{{ $subject->label_responsavel }}</span>
        </div>
        <input type="hidden" name="materia" value="{{ $subject->name }}">
    @else
        <input type="text" name="materia" value="{{ old('materia', $content['materia'] ?? '') }}"
               placeholder="Ex: Matemática, Português, Ciências..."
               style="width: 100%; border: none; border-bottom: 2px solid var(--border); padding: 8px 0; font-size: 14px; color: var(--text-1); outline: none; background: transparent; box-sizing: border-box;"
               onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
    @endif
</div>

{{-- ═══ ESTRATÉGIAS PEDAGÓGICAS ═══ --}}
<div style="border: 1px solid var(--border-sub); border-radius: 10px; padding: 20px; margin-bottom: 20px;">
    <p style="font-size: 11px; font-weight: 700; color: var(--accent-text); letter-spacing: 1px; text-transform: uppercase; margin: 0 0 4px;">Estratégias Pedagógicas</p>
    <p style="font-size: 12px; color: var(--text-4); margin: 0 0 16px;">Descreva como você vai adaptar o ensino desta disciplina para este aluno.</p>
    <textarea name="estrategias_pedagogicas" rows="4"
              placeholder="Ex: Uso de material concreto, atividades em dupla, rotina estruturada, apoio visual..."
              style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">{{ old('estrategias_pedagogicas', $content['estrategias_pedagogicas'] ?? '') }}</textarea>
</div>

{{-- ═══ INVENTÁRIO DE HABILIDADES ═══ --}}
@if($subject && $subject->inventoryItems->count())
@php
    $categorias = [
        'academica'      => ['label' => 'Objetivos Curriculares',      'cor' => '#004B8D', 'key' => 'habilidades_academicas'],
        'socioemocional' => ['label' => 'Desenvolvimento Socioemocional',  'cor' => '#009C8C', 'key' => 'habilidades_socioemocionais'],
        'global'         => ['label' => 'Desenvolvimento Global',        'cor' => '#6D28D9', 'key' => 'desenvolvimento_global'],
    ];
    $itensPorCategoria = $subject->inventoryItems->groupBy('categoria');
@endphp

@foreach($categorias as $catKey => $cat)
    @php $itens = $itensPorCategoria->get($catKey, collect()); @endphp
    @if($itens->count())
    <div style="border: 1px solid var(--border-sub); border-radius: 10px; overflow: hidden; margin-bottom: 20px;">
        <div style="padding: 14px 20px; border-bottom: 1px solid var(--border-sub); background: rgba(0,0,0,0.02);">
            <p style="font-size: 11px; font-weight: 700; color: {{ $cat['cor'] }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">
                {{ $cat['label'] }} — {{ $subject->name }}
            </p>
            <p style="font-size: 11px; color: var(--text-4); margin: 4px 0 0;">Avalie cada meta conforme o desempenho do aluno nesta disciplina.</p>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--bg-subtle);">
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 36%;">Metas / Objetivos</th>
                        @foreach($colunas as $colLabel)
                            <th style="text-align: center; padding: 10px 6px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 10%;">{{ $colLabel }}</th>
                        @endforeach
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3); width: 14%;">Responsável</th>
                        <th style="text-align: left; padding: 10px 14px; font-size: 10px; font-weight: 600; color: var(--text-3);">Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $i => $item)
                        @php $saved = $content[$cat['key']][$i] ?? []; @endphp
                        <tr style="border-top: 1px solid var(--border-sub); {{ $i % 2 !== 0 ? 'background: #FAFAFA;' : '' }}">
                            <td style="padding: 10px 14px; color: var(--text-2); font-size: 13px;">
                                {{ $item->meta }}
                                <input type="hidden" name="{{ $cat['key'] }}[{{ $i }}][meta]" value="{{ $item->meta }}">
                            </td>
                            @foreach(array_keys($colunas) as $col)
                                <td style="text-align: center; padding: 10px 6px;">
                                    <input type="checkbox"
                                           name="{{ $cat['key'] }}[{{ $i }}][{{ $col }}]"
                                           value="1"
                                           {{ !empty($saved[$col]) ? 'checked' : '' }}
                                           style="width: 15px; height: 15px; cursor: pointer; accent-color: {{ $cat['cor'] }};">
                                </td>
                            @endforeach
                            <td style="padding: 10px 14px;">
                                <input type="text" name="{{ $cat['key'] }}[{{ $i }}][responsavel]"
                                       value="{{ $saved['responsavel'] ?? $subject->label_responsavel }}"
                                       style="width: 100%; border: none; border-bottom: 1px solid var(--border); padding: 4px 0; font-size: 12px; color: var(--text-2); outline: none; background: transparent;"
                                       onfocus="this.style.borderColor='{{ $cat['cor'] }}'" onblur="this.style.borderColor='var(--border)'">
                            </td>
                            <td style="padding: 10px 14px;">
                                <input type="text" name="{{ $cat['key'] }}[{{ $i }}][observacoes]"
                                       value="{{ $saved['observacoes'] ?? '' }}"
                                       placeholder="..."
                                       style="width: 100%; border: none; border-bottom: 1px solid var(--border); padding: 4px 0; font-size: 12px; color: var(--text-2); outline: none; background: transparent;"
                                       onfocus="this.style.borderColor='{{ $cat['cor'] }}'" onblur="this.style.borderColor='var(--border)'">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach
@endif

