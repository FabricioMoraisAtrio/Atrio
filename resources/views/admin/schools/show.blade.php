@extends('admin.layouts.app')
@section('title', $school->name)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        @if($school->logo)
            <img src="{{ route('school.logo', ['filename' => basename($school->logo)]) }}"
                 alt="Logo {{ $school->name }}" style="height: 48px; width: 48px; object-fit: contain; border-radius: 6px;">
        @endif
        <div>
            <h2 class="text-lg font-semibold text-gray-800">{{ $school->name }}</h2>
            <p class="text-sm text-gray-500">{{ $school->slug }}</p>
        </div>
    </div>
    <a href="{{ route('admin.schools.edit', $school) }}"
       class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
        Editar
    </a>
</div>

<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Plano</p>
        <p class="text-sm font-semibold text-gray-800">{{ ucfirst($school->plan) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Status</p>
        <p class="text-sm font-semibold
            {{ $school->plan_status === 'active' ? 'text-green-600' : ($school->plan_status === 'suspended' ? 'text-amber-500' : 'text-red-500') }}">
            {{ ucfirst($school->plan_status) }}
        </p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Estudantes</p>
        <p class="text-sm font-semibold text-gray-800">{{ $school->students_count }} / {{ $school->max_students }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500 mb-1">Documentos</p>
        <p class="text-sm font-semibold text-gray-800">{{ $documentCount }}</p>
    </div>
</div>

@if($school->notes)
    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-6 text-sm text-amber-800">
        {{ $school->notes }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
        <p class="text-sm font-medium text-gray-700">Usuários</p>
    </div>
    <table class="w-full text-sm">
        <thead class="border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Nome</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">E-mail</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Perfil</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($school->users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs bg-gray-100 text-gray-700 font-medium px-2 py-0.5 rounded-full">
                        {{ $user->getRoleNames()->first() ?? '—' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-500' }} text-xs">
                        {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <form method="POST"
                          action="{{ route('admin.schools.resetPassword', [$school, $user]) }}"
                          class="inline">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Resetar senha de {{ $user->name }}?')"
                                class="text-xs text-amber-600 hover:underline">
                            Resetar senha
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<a href="{{ route('admin.schools.index') }}" class="text-sm text-gray-500 hover:underline">
    ← Voltar para escolas
</a>
@endsection