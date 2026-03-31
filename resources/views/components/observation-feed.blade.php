@props(['aluno', 'role' => 'secretaria'])

<div class="observation-feed-card" style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <h3 class="text-sm font-semibold text-white mb-4">Mural de observações</h3>

    {{-- Formulário --}}
    <form method="POST"
          action="{{ route($role . '.alunos.observacoes.store', $aluno) }}"
          class="mb-6 space-y-3">
        @csrf

        <textarea name="content" rows="2" maxlength="1000"
                  placeholder="Registre uma observação sobre este aluno..."
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('content') }}</textarea>

        <div class="flex gap-3">
            <select name="urgency"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="normal">Normal</option>
                <option value="atencao">Atenção</option>
                <option value="critico">Crítico</option>
            </select>

            <select name="category"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="comportamento">Comportamento</option>
                <option value="aprendizado">Aprendizado</option>
                <option value="saude">Saúde</option>
            </select>

            <button type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                Registrar
            </button>
        </div>
    </form>

    {{-- Feed --}}
    @forelse($aluno->observations as $obs)
        <div class="border-l-4 pl-4 py-2 mb-3
            {{ $obs->urgency === 'critico' ? 'border-red-400' :
               ($obs->urgency === 'atencao' ? 'border-amber-400' : 'border-gray-200') }}">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-medium text-gray-700">{{ $obs->user->name }}</span>
                        <span class="text-xs px-1.5 py-0.5 rounded
                            {{ $obs->urgency === 'critico' ? 'bg-red-100 text-red-700' :
                               ($obs->urgency === 'atencao' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($obs->urgency) }}
                        </span>
                        <span class="text-xs bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded">
                            {{ ucfirst($obs->category) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $obs->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm text-gray-700">{{ $obs->content }}</p>
                </div>

                @if($obs->user_id === auth()->id() || $role === 'secretaria')
                    <form method="POST"
                          action="{{ route($role . '.observacoes.destroy', $obs) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Remover observação?')"
                                class="text-xs text-red-400 hover:text-red-600 shrink-0">
                            Remover
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400">Nenhuma observação registrada ainda.</p>
    @endforelse
</div>