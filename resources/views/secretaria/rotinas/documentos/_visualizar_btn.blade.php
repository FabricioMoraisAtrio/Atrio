{{--
    Botão que acessa o documento + atalho para PDF.
    Variáveis: $doc, $corPrincipal, $bgPrincipal
--}}
<div style="display: inline-flex; align-items: center; gap: 6px;">
    <a href="{{ route('secretaria.documentos.show', $doc) }}"
       style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600;
              color: {{ $corPrincipal }}; text-decoration: none; padding: 6px 14px;
              border: 1px solid {{ $corPrincipal }}; border-radius: 8px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
        Acessar
    </a>
    <a href="{{ route('secretaria.documentos.pdf', $doc) }}"
       style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600;
              color: #6B7280; text-decoration: none; padding: 6px 12px;
              border: 1px solid #E5E7EB; border-radius: 8px;">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
            <path d="M14 2v6h6"/>
        </svg>
        PDF
    </a>
</div>
