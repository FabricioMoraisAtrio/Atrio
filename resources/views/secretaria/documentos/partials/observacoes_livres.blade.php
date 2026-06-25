@php $content = $documento->content ?? []; @endphp

<div style="margin-bottom: 20px; padding-top: 20px; border-top: 1px solid var(--border-sub);">
    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-3); letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Observações livres</label>
    <textarea name="observacoes_livres" rows="3"
              placeholder="Anotações adicionais..."
              style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 12px; font-size: 14px; color: var(--text-1); outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">{{ old('observacoes_livres', $content['observacoes_livres'] ?? '') }}</textarea>
</div>