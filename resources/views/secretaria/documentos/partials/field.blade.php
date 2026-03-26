@props(['label', 'name', 'placeholder' => '', 'rows' => 3, 'value' => ''])
<div style="margin-bottom: 20px;">
    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
        {{ $label }}
    </label>
    <textarea name="{{ $name }}" rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
              onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">{{ old($name, $value) }}</textarea>
</div>