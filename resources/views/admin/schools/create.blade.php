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
                <label for="logo-input" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border: 1px solid #D1D5DB; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; background: #F9FAFB;"
                       onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='#F9FAFB'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                    </svg>
                    <span id="logo-label">Escolher imagem…</span>
                </label>
                <input id="logo-input" type="file" name="logo" accept=".svg,.png,.jpg,.jpeg,image/svg+xml,image/png,image/jpeg"
                       style="display: none;"
                       onchange="document.getElementById('logo-label').textContent = this.files[0]?.name ?? 'Escolher imagem…'">
                @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
@endsection