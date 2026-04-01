@extends('admin.layouts.app')
@section('title', 'Nova Escola')

@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">Nova escola</h2>

    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
        <form method="POST" action="{{ route('admin.schools.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dados da escola</p>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nome da escola</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Slug <span class="text-gray-400">(identificador único)</span></label>
                <input type="text" name="slug" value="{{ old('slug') }}"
                       placeholder="ex: escola-municipal-centro"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800 @error('slug') border-red-400 @enderror">
                @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Plano</label>
                    <select name="plan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                        <option value="pro" {{ old('plan') === 'pro' ? 'selected' : '' }}>Pro (mensal)</option>
                        <option value="enterprise" {{ old('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise (anual)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Máx. alunos</label>
                    <input type="number" name="max_students" value="{{ old('max_students', 100) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Expira em</label>
                <input type="date" name="plan_expires_at" value="{{ old('plan_expires_at') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                @error('plan_expires_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
    <label class="block text-sm text-gray-600 mb-1">Logo da escola <span class="text-gray-400">(SVG, PNG ou JPG)</span></label>
    
    {{-- Preview --}}
    <div id="logo-preview-wrapper" style="display:none; margin-bottom:10px;">
        <p style="font-size:11px; color:#9CA3AF; margin-bottom:4px;">Pré-visualização:</p>
        <img id="logo-preview-img"
             src=""
             style="height:56px; max-width:180px; object-fit:contain; border:1px solid #E5E7EB; border-radius:8px; padding:6px; background:#F9FAFB;">
                    </div>

                    <label for="logo-input" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border: 1px solid #D1D5DB; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; background: #F9FAFB;"
                        onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                        </svg>
                        <span id="logo-label">Escolher imagem…</span>
                    </label>
                    <input id="logo-input" type="file" name="logo" accept=".svg,.png,.jpg,.jpeg,image/svg+xml,image/png,image/jpeg"
                        style="display: none;"
                        onchange="previewLogo(this)">
                    @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            {{-- Tema de cores --}}
            <div>
                <label class="block text-sm text-gray-600 mb-1">Cor do tema da escola</label>
                <p class="text-xs text-gray-400 mb-2">Define a cor de destaque do painel para os usuários desta escola.</p>
                <div class="flex flex-wrap gap-2 mb-2" id="preset-swatches">
                    @foreach([
                        '#004B8D' => 'Azul Átrio (padrão)',
                        '#16A34A' => 'Verde',
                        '#7C3AED' => 'Roxo',
                        '#DC2626' => 'Vermelho',
                        '#EA580C' => 'Laranja',
                        '#0891B2' => 'Turquesa',
                        '#BE185D' => 'Rosa',
                        '#92400E' => 'Marrom',
                    ] as $hex => $name)
                        <button type="button"
                                onclick="selectColor('{{ $hex }}')"
                                title="{{ $name }}"
                                data-color="{{ $hex }}"
                                style="width: 28px; height: 28px; border-radius: 50%; background: {{ $hex }}; border: 2px solid transparent; cursor: pointer; transition: transform 0.1s;"
                                onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                        </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <input type="color" id="color-picker" value="{{ old('theme_color', '#004B8D') }}"
                           onchange="selectColor(this.value)"
                           class="w-8 h-8 rounded cursor-pointer border border-gray-300 p-0.5">
                    <input type="text" name="theme_color" id="color-hex"
                           value="{{ old('theme_color', '#004B8D') }}"
                           maxlength="7" placeholder="#004B8D"
                           oninput="syncColor(this.value)"
                           class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-28 focus:outline-none focus:ring-2 focus:ring-gray-800 font-mono">
                    <span class="text-xs text-gray-400">código hexadecimal</span>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Observações internas</label>
                <textarea name="notes" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800"
                          placeholder="Notas sobre contrato, contato, etc...">{{ old('notes') }}</textarea>
            </div>

            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-4">Secretaria inicial</p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nome</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800 @error('admin_name') border-red-400 @enderror">
                        @error('admin_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">E-mail</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800 @error('admin_email') border-red-400 @enderror">
                        @error('admin_email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Senha</label>
                        <input type="text" name="admin_password" value="{{ old('admin_password') }}"
                               placeholder="Mínimo 6 caracteres"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800 @error('admin_password') border-red-400 @enderror">
                        @error('admin_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                    Criar escola
                </button>
                <a href="{{ route('admin.schools.index') }}"
                   class="text-sm text-gray-500 hover:underline self-center">Cancelar</a>
            </div>
        </form>
    </div>
</div>
<script>

function previewLogo(input) {
    const label   = document.getElementById('logo-label');
    const wrapper = document.getElementById('logo-preview-wrapper');
    const img     = document.getElementById('logo-preview-img');

    if (input.files && input.files[0]) {
        label.textContent = input.files[0].name;

        const reader = new FileReader();
        reader.onload = e => {
            img.src = e.target.result;
            wrapper.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function selectColor(hex) {
    document.getElementById('color-hex').value = hex;
    document.getElementById('color-picker').value = hex;
    document.querySelectorAll('#preset-swatches button').forEach(btn => {
        btn.style.border = btn.dataset.color === hex ? '2px solid #111827' : '2px solid transparent';
    });
}
function syncColor(val) {
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById('color-picker').value = val;
        document.querySelectorAll('#preset-swatches button').forEach(btn => {
            btn.style.border = btn.dataset.color.toLowerCase() === val.toLowerCase() ? '2px solid #111827' : '2px solid transparent';
        });
    }
}
// Destaca a cor padrão ao carregar
selectColor(document.getElementById('color-hex').value || '#004B8D');
</script>
@endsection