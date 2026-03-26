@php $content = $documento->content ?? []; @endphp

<div style="margin-bottom: 20px; padding-top: 20px; border-top: 1px solid #F3F4F6;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Observações livres</label>
    <textarea name="observacoes_livres" rows="3"
              placeholder="Anotações adicionais..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('observacoes_livres', $content['observacoes_livres'] ?? '') }}</textarea>
</div>