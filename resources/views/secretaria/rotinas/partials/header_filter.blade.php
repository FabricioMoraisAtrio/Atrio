@php
    $isAtivo = !empty($ativo);
    $cor = $color ?? ($isAtivo ? '#004B8D' : '#6B7280');
@endphp
<div style="position: relative; width: 100%;">
    <button type="button"
            data-filter-btn
            onclick="abrirFiltro('{{ $field }}')"
            style="width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 4px; padding: 10px 10px; background: none; border: none; cursor: pointer; text-align: left;">
        <span style="font-size: 10px; font-weight: 700; color: {{ $isAtivo ? '#004B8D' : ($color ?? '#6B7280') }}; text-transform: uppercase; letter-spacing: 0.4px; line-height: 1.2;">
            @if($isAtivo)
                <span style="display: block; font-size: 8px; font-weight: 600; color: #004B8D; text-transform: none; letter-spacing: 0; margin-bottom: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90px;">{{ $ativo }}</span>
            @endif
            {{ $label }}
        </span>
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $isAtivo ? '#004B8D' : '#9CA3AF' }}" stroke-width="2.5">
            <path d="M6 9l6 6 6-6"/>
        </svg>
    </button>

    <div id="dropdown_{{ $field }}"
         style="display: none; position: absolute; top: 100%; left: 0; min-width: 180px; background: #fff; border: 1px solid #E5E7EB; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); z-index: 200; overflow: hidden;">
        <div onclick="selecionarFiltro('{{ $field }}', '')"
             style="padding: 9px 14px; font-size: 12px; color: #9CA3AF; cursor: pointer; border-bottom: 1px solid #F9FAFB;"
             onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='#fff'">
            Todos
        </div>
        @foreach($opcoes as $val => $texto)
            @if($val !== '')
                <div onclick="selecionarFiltro('{{ $field }}', '{{ $val }}')"
                     style="padding: 9px 14px; font-size: 12px; color: #374151; cursor: pointer; {{ ($ativo && ($ativo === $texto || (string)$val === (string)$filtroTurma || (string)$val === (string)$filtroPublico || (string)$val === (string)$filtroGrupo || (string)$val === (string)$filtroDoc || (string)$val === (string)$filtroLaudo)) ? 'background: #EFF6FF; font-weight: 600; color: #004B8D;' : '' }}"
                     onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='{{ ($ativo && ($ativo === $texto)) ? '#EFF6FF' : 'transparent' }}'">
                    {{ $texto }}
                </div>
            @endif
        @endforeach
    </div>
</div>
