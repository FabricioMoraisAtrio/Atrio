{{--
    Botão simples que acessa o registro do documento (show page).
    Variáveis: $doc, $corPrincipal, $bgPrincipal
--}}
<a href="{{ route('secretaria.documentos.show', $doc) }}"
   style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600;
          color: {{ $corPrincipal }}; text-decoration: none; padding: 6px 14px;
          border: 1px solid {{ $bgPrincipal }}; border-radius: 8px; background: {{ $bgPrincipal }};">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>
    Acessar
</a>
