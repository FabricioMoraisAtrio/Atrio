@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<h2 class="text-lg font-semibold text-gray-800 mb-6">Visão geral</h2>

<div class="grid grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Escolas cadastradas</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $stats['total_schools'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Escolas ativas</p>
        <p class="text-2xl font-semibold text-green-600">{{ $stats['active_schools'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Total de alunos</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $stats['total_students'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs text-gray-500 mb-1">Documentos gerados</p>
        <p class="text-2xl font-semibold text-gray-800">{{ $stats['total_documents'] }}</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
        <p class="text-sm font-medium text-gray-700">Escolas</p>
        <a href="{{ route('admin.schools.create') }}"
           class="text-xs bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-lg px-3 py-1.5 transition">
            Nova escola
        </a>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Escola</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Plano</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Status</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Alunos</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Usuários</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Expira em</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($schools as $school)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $school->name }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {{ $school->plan === 'enterprise' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($school->plan) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-xs font-medium
                        {{ $school->plan_status === 'active' ? 'text-green-600' : ($school->plan_status === 'suspended' ? 'text-amber-500' : 'text-red-500') }}">
                        {{ ucfirst($school->plan_status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $school->students_count }} / {{ $school->max_students }}
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $school->users_count }}</td>
                <td class="px-4 py-3 text-gray-600">
                    @if($school->plan_expires_at)
                        @php $expires = \Carbon\Carbon::parse($school->plan_expires_at); @endphp
                        <span class="{{ $expires->isPast() ? 'text-red-500' : ($expires->diffInDays() < 30 ? 'text-amber-500' : 'text-gray-600') }}">
                            {{ $expires->format('d/m/Y') }}
                        </span>
                    @else
                        —
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.schools.show', $school) }}"
                       class="text-blue-600 hover:underline text-xs">Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma escola cadastrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection