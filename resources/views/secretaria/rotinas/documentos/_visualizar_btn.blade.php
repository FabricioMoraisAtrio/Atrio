{{--
    Botão que acessa o documento + atalho para PDF.
    Variáveis: $doc, $corPrincipal, $bgPrincipal
--}}
<div style="display: inline-flex; align-items: center; gap: 6px;">
    <a href="{{ route('secretaria.documentos.show', $doc) }}?back={{ urlencode(url()->current()) }}"
       style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600;
              color: {{ $corPrincipal }}; text-decoration: none; padding: 6px 14px;
              border: 1px solid {{ $corPrincipal }}; border-radius: 8px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        Acessar
    </a>
    <a href="{{ route('secretaria.documentos.preview', $doc) }}" target="_blank"
       style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600;
              color: var(--text-3); text-decoration: none; padding: 6px 12px;
              border: 1px solid var(--border); border-radius: 8px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        Visualizar
    </a>
</div>
