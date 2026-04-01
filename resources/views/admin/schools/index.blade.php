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
                        {{-- Botão que abre o modal --}}
                        <button type="button"
                                onclick="abrirModalDelete('{{ $school->id }}', '{{ addslashes($school->name) }}')"
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


{{-- Modal de confirmação de exclusão --}}
<div id="modal-delete"
     class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center"
     style="display:none;">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-auto mt-32">
        <h3 class="text-base font-semibold text-gray-900 mb-1">Excluir escola</h3>
        <p class="text-sm text-gray-500 mb-4">
            Esta ação é <strong>irreversível</strong>. Todos os alunos, documentos, usuários e laudos serão excluídos permanentemente.
        </p>
        <p class="text-sm text-gray-700 mb-2">
            Digite o nome da escola para confirmar:
        </p>
        <input id="modal-confirmation"
               type="text"
               placeholder="Nome da escola"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-red-400">

        <form id="modal-form" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="confirmation" id="modal-hidden-confirmation">
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="fecharModalDelete()"
                        class="text-sm text-gray-500 hover:text-gray-700 px-4 py-2">
                    Cancelar
                </button>
                <button type="button"
                        onclick="confirmarDelete()"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    Excluir permanentemente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalDelete(id, nome) {
    document.getElementById('modal-confirmation').value = '';
    document.getElementById('modal-form').action = '/superadmin/schools/' + id;
    document.getElementById('modal-delete').dataset.nome = nome;
    document.getElementById('modal-delete').style.display = 'flex';
}

function fecharModalDelete() {
    document.getElementById('modal-delete').style.display = 'none';
}

function confirmarDelete() {
    const digitado = document.getElementById('modal-confirmation').value;
    const esperado = document.getElementById('modal-delete').dataset.nome;

    if (digitado !== esperado) {
        alert('O nome digitado não confere com o nome da escola.');
        return;
    }

    document.getElementById('modal-hidden-confirmation').value = digitado;
    document.getElementById('modal-form').submit();
}

// Fechar ao clicar fora do modal
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) fecharModalDelete();
});
</script>

@endsection