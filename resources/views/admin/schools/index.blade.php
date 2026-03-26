@extends('admin.layouts.app')
@section('title', 'Escolas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-gray-800">Escolas</h2>
    <a href="{{ route('admin.schools.create') }}"
       class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
        Nova escola
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Escola</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Plano</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Status</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Alunos</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Usuários</th>
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
                <td class="px-4 py-3 text-gray-600">{{ $school->students_count }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $school->users_count }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.schools.show', $school) }}"
                       class="text-blue-600 hover:underline">Ver</a>
                    <a href="{{ route('admin.schools.edit', $school) }}"
                       class="text-gray-500 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('admin.schools.destroy', $school) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Remover escola e todos os dados?')"
                                class="text-red-500 hover:underline">
                            Remover
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma escola cadastrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection