@php $content = $documento->content ?? []; @endphp

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Cronograma de atendimento</label>
    <textarea name="cronograma" rows="3"
              placeholder="Frequência e horários de atendimento especializado..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('cronograma', $content['cronograma'] ?? '') }}</textarea>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Recursos necessários</label>
    <textarea name="recursos" rows="3"
              placeholder="Materiais, tecnologias assistivas, apoios..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('recursos', $content['recursos'] ?? '') }}</textarea>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Acessibilidade</label>
    <textarea name="acessibilidade" rows="3"
              placeholder="Adaptações de acessibilidade necessárias..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('acessibilidade', $content['acessibilidade'] ?? '') }}</textarea>
</div>

<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Parcerias com rede de saúde</label>
    <textarea name="parcerias" rows="3"
              placeholder="Profissionais e instituições parceiras..."
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old('parcerias', $content['parcerias'] ?? '') }}</textarea>
</div>