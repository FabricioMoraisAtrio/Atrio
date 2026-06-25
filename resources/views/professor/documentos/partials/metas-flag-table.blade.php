{{-- Tabela de avaliação de metas (Meta + flags + observações).
     Espera: $metas (Collection), $accent, $bg, $label, $cat, $metasSalvas --}}
@php
    $opcoes = [
        'autonomia'     => 'Executa com autonomia',
        'suporte'       => 'Executa com suporte',
        'nao_executa'   => 'Ainda não executa',
        'nao_observado' => 'Ainda não observado',
    ];
@endphp
<div style="border: 1px solid {{ $bg }}; border-radius: 10px; overflow: hidden;">
    <div style="padding: 12px 18px; background: {{ $bg }};">
        <p style="font-size: 11px; font-weight: 700; color: {{ $accent }}; letter-spacing: 1px; text-transform: uppercase; margin: 0;">{{ $label }}</p>
    </div>

    @if($metas->isEmpty())
        <div style="padding: 16px 18px;">
            <p style="font-size: 12px; color: var(--text-4); font-style: italic; margin: 0;">
                Nenhuma meta cadastrada nesta categoria. O administrador/coordenação cadastra as metas na ficha do aluno.
            </p>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background: var(--bg-subtle); border-bottom: 1px solid #F0F0F0;">
                        <th style="text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); width: 35%;">Meta / Objetivo</th>
                        @foreach($opcoes as $rotulo)
                            <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: var(--text-3); white-space: nowrap;">{{ $rotulo }}</th>
                        @endforeach
                        <th style="text-align: left; padding: 10px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); width: 28%;">Observações <span style="font-weight: 400; color: var(--text-4);">(opcional)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metas as $item)
                        @php
                            $salvos    = old("metas.{$item->id}", $metasSalvas[$item->id] ?? []);
                            $flagSalva = is_array($salvos) ? ($salvos['flag'] ?? null) : $salvos;
                            $obsSalva  = is_array($salvos) ? ($salvos['obs']  ?? '') : '';
                        @endphp
                        <tr style="border-top: 1px solid var(--border-sub); {{ $loop->even ? 'background:#FAFAFA;' : '' }}">
                            <td style="padding: 12px 16px; color: var(--text-2); font-size: 13px; font-weight: 500; line-height: 1.4;">
                                <input type="hidden" name="metas[{{ $item->id }}][texto]" value="{{ $item->meta }}">
                                <input type="hidden" name="metas[{{ $item->id }}][cat]" value="{{ $cat }}">
                                {{ $item->meta }}
                            </td>
                            @foreach($opcoes as $valor => $rotulo)
                                <td style="text-align: center; padding: 12px 8px;">
                                    <label style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; border: 2px solid {{ $flagSalva === $valor ? $accent : 'var(--border)' }}; background: {{ $flagSalva === $valor ? $accent : 'transparent' }}; cursor: pointer; transition: all 0.15s;">
                                        <input type="radio"
                                               name="metas[{{ $item->id }}][flag]"
                                               value="{{ $valor }}"
                                               {{ $flagSalva === $valor ? 'checked' : '' }}
                                               style="display: none;"
                                               onchange="atualizarLinha(this, '{{ $accent }}')">
                                        @if($flagSalva === $valor)
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5"><polyline points="20 6 9 17 4 12"/></svg>
                                        @endif
                                    </label>
                                </td>
                            @endforeach
                            <td style="padding: 10px 16px; vertical-align: top;">
                                <textarea name="metas[{{ $item->id }}][obs]"
                                          rows="3"
                                          placeholder="Observação..."
                                          style="width: 100%; border: 1px solid var(--border); border-radius: 6px; padding: 7px 10px; font-size: 12px; color: var(--text-2); outline: none; background: transparent; box-sizing: border-box; resize: vertical; font-family: inherit; line-height: 1.5;"
                                          onfocus="this.style.borderColor='{{ $accent }}'" onblur="this.style.borderColor='var(--border)'">{{ $obsSalva }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
