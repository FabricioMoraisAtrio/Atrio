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

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Escola</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Plano</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Status</th>
                <th class="text-left px-4 py-3 text-gray-600 font-medium">Estudantes</th>
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
                    <button type="button"
                            onclick="abrirModalDelete('{{ $school->id }}', '{{ addslashes($school->name) }}')"
                            class="text-red-500 hover:underline">
                        Remover
                    </button>
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

{{-- Modal de exclusão --}}
<div id="modal-delete"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,0.2); padding:28px; width:100%; max-width:460px; margin:0 16px;">

        {{-- Cabeçalho --}}
        <div style="display:flex; align-items:flex-start; gap:12px; margin-bottom:20px;">
            <div style="background:#FEE2E2; border-radius:50%; width:40px; height:40px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </div>
            <div>
                <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 4px;">Excluir escola</h3>
                <p style="font-size:13px; color:#6B7280; margin:0;">
                    Esta ação é <strong style="color:#DC2626;">irreversível</strong>. Todos os estudantes, usuários, documentos e laudos serão excluídos permanentemente.
                </p>
            </div>
        </div>

        <form id="modal-form" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="confirmation" id="modal-hidden-confirmation">

            {{-- Nome da escola --}}
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    Digite o nome da escola para confirmar
                </label>
                <input id="modal-confirmation"
                       type="text"
                       placeholder="Nome exato da escola"
                       style="width:100%; border:1px solid #D1D5DB; border-radius:8px; padding:9px 12px; font-size:13px; color:#111827; outline:none; box-sizing:border-box;"
                       onfocus="this.style.borderColor='var(--adm-red)'; this.style.boxShadow='0 0 0 3px rgba(220,38,38,0.1)'"
                       onblur="this.style.borderColor='var(--adm-border)'; this.style.boxShadow='none'">
                <p id="modal-confirmation-error"
                   style="display:none; font-size:12px; color:#DC2626; margin-top:4px;">
                    O nome digitado não confere. Tente novamente.
                </p>
            </div>

            {{-- Motivo --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    Motivo da exclusão <span style="color:#9CA3AF; font-weight:400; text-transform:none;">(opcional)</span>
                </label>
                <textarea name="motivo"
                          rows="3"
                          placeholder="Ex: Escola encerrou o contrato, dados de teste, etc."
                          style="width:100%; border:1px solid #D1D5DB; border-radius:8px; padding:9px 12px; font-size:13px; color:#111827; outline:none; resize:none; box-sizing:border-box;"
                          onfocus="this.style.borderColor='var(--adm-accent)'; this.style.boxShadow='0 0 0 3px rgba(107,114,128,0.1)'"
                          onblur="this.style.borderColor='var(--adm-border)'; this.style.boxShadow='none'"></textarea>
            </div>

            {{-- Botões --}}
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button"
                        onclick="fecharModalDelete()"
                        style="font-size:13px; color:#6B7280; background:none; border:1px solid #E5E7EB; border-radius:8px; padding:9px 18px; cursor:pointer;">
                    Cancelar
                </button>
                <button type="button"
                        onclick="confirmarDelete()"
                        style="font-size:13px; font-weight:600; color:#fff; background:#DC2626; border:none; border-radius:8px; padding:9px 18px; cursor:pointer;">
                    Excluir permanentemente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalDelete(id, nome) {
    document.getElementById('modal-confirmation').value = '';
    document.getElementById('modal-confirmation-error').style.display = 'none';
    document.getElementById('modal-form').action = '/superadmin/schools/' + id;
    document.getElementById('modal-delete').dataset.nome = nome;
    document.getElementById('modal-delete').style.display = 'flex';
}

function fecharModalDelete() {
    document.getElementById('modal-delete').style.display = 'none';
}

function confirmarDelete() {
    const digitado  = document.getElementById('modal-confirmation').value.trim();
    const esperado  = document.getElementById('modal-delete').dataset.nome;
    const erro      = document.getElementById('modal-confirmation-error');

    if (digitado !== esperado) {
        erro.style.display = 'block';
        document.getElementById('modal-confirmation').focus();
        return;
    }

    erro.style.display = 'none';
    document.getElementById('modal-hidden-confirmation').value = digitado;
    document.getElementById('modal-form').submit();
}

// Fechar ao clicar no fundo escuro
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) fecharModalDelete();
});
</script>

@endsection