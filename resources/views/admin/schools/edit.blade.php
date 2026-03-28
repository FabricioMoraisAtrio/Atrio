@extends('admin.layouts.app')
@section('title', 'Editar Escola')

@section('content')
<div class="max-w-lg">
    <h2 class="text-lg font-semibold text-gray-800 mb-6">Editar — {{ $school->name }}</h2>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.schools.update', $school) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm text-gray-600 mb-1">Nome da escola</label>
                <input type="text" name="name" value="{{ old('name', $school->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- Logo atual --}}
            @if($school->logo)
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Logo atual</label>
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($school->logo) }}"
                        style="height: 40px; object-fit: contain;">
                </div>
            @endif

            {{-- Upload logo --}}
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Logo da escola (SVG, PNG ou JPG)</label>
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
                @error('logo')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Plano</label>
                    <select name="plan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                        <option value="pro" {{ old('plan', $school->plan) === 'pro' ? 'selected' : '' }}>Pro (mensal)</option>
                        <option value="enterprise" {{ old('plan', $school->plan) === 'enterprise' ? 'selected' : '' }}>Enterprise (anual)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Status do plano</label>
                    <select name="plan_status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                        <option value="active" {{ old('plan_status', $school->plan_status) === 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="suspended" {{ old('plan_status', $school->plan_status) === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                        <option value="cancelled" {{ old('plan_status', $school->plan_status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Expira em</label>
                    <input type="date" name="plan_expires_at"
                           value="{{ old('plan_expires_at', $school->plan_expires_at?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Máx. alunos</label>
                    <input type="number" name="max_students"
                           value="{{ old('max_students', $school->max_students) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $school->is_active) ? 'checked' : '' }}
                           class="rounded">
                    <span class="text-sm text-gray-700">Escola ativa</span>
                </label>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Observações internas</label>
                <textarea name="notes" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-800">{{ old('notes', $school->notes) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                    Atualizar
                </button>
<a href="{{ route('admin.schools.show', $school) }}"
                   class="text-sm text-gray-500 hover:underline self-center">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection