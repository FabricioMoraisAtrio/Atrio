@props(['aluno', 'role' => 'secretaria'])

<div class="observation-feed-card" style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border); padding: 24px; margin-bottom: 16px;">
    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-1); margin: 0 0 16px;">Mural de observações</h3>

    {{-- Formulário --}}
    <form method="POST"
          action="{{ route('secretaria.alunos.observacoes.store', $aluno) }}"
          style="margin-bottom: 20px;">
        @csrf

        <textarea name="content" rows="2" maxlength="1000"
                  placeholder="Registre uma observação sobre este estudante..."
                  style="width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 10px 14px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none; resize: none; box-sizing: border-box; margin-bottom: 10px; font-family: inherit;">{{ old('content') }}</textarea>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <select name="urgency"
                    style="border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none;">
                <option value="normal">Normal</option>
                <option value="atencao">Atenção</option>
                <option value="critico">Crítico</option>
            </select>

            <select name="category"
                    style="border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-1); background: var(--bg-card); outline: none;">
                <option value="comportamento">Comportamento</option>
                <option value="aprendizado">Aprendizado</option>
                <option value="saude">Saúde</option>
            </select>

            <button type="submit"
                    style="background: var(--accent); color: white; font-size: 13px; font-weight: 600; border: none; border-radius: 8px; padding: 8px 20px; cursor: pointer;">
                Registrar
            </button>
        </div>
    </form>

    {{-- Feed --}}
    @forelse($aluno->observations as $obs)
    @php
        $urgencyStyle = match($obs->urgency) {
            'critico' => ['border' => '#F87171', 'badge_bg' => '#FEF2F2', 'badge_color' => '#991B1B'],
            'atencao' => ['border' => '#FCD34D', 'badge_bg' => '#FEF3C7', 'badge_color' => '#92400E'],
            default   => ['border' => 'var(--border)', 'badge_bg' => '#F3F4F6', 'badge_color' => '#6B7280'],
        };
    @endphp
        <div style="border-left: 3px solid {{ $urgencyStyle['border'] }}; padding: 8px 0 8px 14px; margin-bottom: 12px;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 8px;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 4px;">
                        <span style="font-size: 12px; font-weight: 600; color: var(--text-1);">{{ $obs->user->name }}</span>
                        <span style="font-size: 11px; font-weight: 600; padding: 1px 7px; border-radius: 20px; background: {{ $urgencyStyle['badge_bg'] }}; color: {{ $urgencyStyle['badge_color'] }};">
                            {{ ucfirst($obs->urgency) }}
                        </span>
                        <span style="font-size: 11px; font-weight: 600; padding: 1px 7px; border-radius: 20px; background: var(--accent-bg); color: var(--accent-text);">
                            {{ ucfirst($obs->category) }}
                        </span>
                        <span style="font-size: 11px; color: var(--text-3);">{{ $obs->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p style="font-size: 13px; color: var(--text-2); margin: 0; line-height: 1.5;">{{ $obs->content }}</p>
                </div>

                @if($obs->user_id === auth()->id() || auth()->user()?->podeVerTodosEstudantes())
                    <form method="POST" action="{{ route('secretaria.observacoes.destroy', $obs) }}">
                        @csrf @method('DELETE')
                        <button type="button" data-confirm="Remover observação?"
                                style="font-size: 12px; color: var(--danger); background: none; border: none; cursor: pointer; white-space: nowrap; padding: 0;">
                            Remover
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p style="font-size: 13px; color: var(--text-3); margin: 0;">Nenhuma observação registrada ainda.</p>
    @endforelse
</div>
