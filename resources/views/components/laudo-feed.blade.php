@props(['aluno', 'role' => 'secretaria'])

<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 16px;">
    <h3 style="font-size: 14px; font-weight: 600; color: #111827; margin: 0 0 16px;">{{ term('laudos') }}</h3>

    @if($role === 'secretaria')
    <form method="POST"
          action="{{ route('secretaria.alunos.laudos.store', $aluno) }}"
          enctype="multipart/form-data"
          style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #F3F4F6;">
        @csrf

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Tipo</label>
                <select name="tipo"
                        style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                    <option value="medico" {{ old('tipo') === 'medico' ? 'selected' : '' }}>Médico</option>
                    <option value="psicologico" {{ old('tipo') === 'psicologico' ? 'selected' : '' }}>Psicológico</option>
                    <option value="fonoaudiologico" {{ old('tipo') === 'fonoaudiologico' ? 'selected' : '' }}>Fonoaudiológico</option>
                    <option value="neuropediatrico" {{ old('tipo') === 'neuropediatrico' ? 'selected' : '' }}>Neuropediátrico</option>
                    <option value="outro" {{ old('tipo') === 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
                @error('tipo')<p style="font-size: 12px; color: #EF4444; margin: 4px 0 0;">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Data do {{ strtolower(term('laudo')) }}</label>
                <input type="date" name="data_laudo" value="{{ old('data_laudo') }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                @error('data_laudo')<p style="font-size: 12px; color: #EF4444; margin: 4px 0 0;">{{ $message }}</p>@enderror
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Descrição (opcional)</label>
            <input type="text" name="descricao" value="{{ old('descricao') }}" placeholder="Ex: Diagnóstico de TEA — CID F84.0"
                   style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
        </div>

        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <label id="laudo-file-label-{{ $aluno->id }}"
                   style="display: inline-flex; align-items: center; gap: 8px; background: #F3F4F6; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; color: #374151; font-weight: 500;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                </svg>
                <span id="laudo-file-name-{{ $aluno->id }}">Escolher PDF</span>
                <input type="file" name="arquivo" accept=".pdf" style="display: none;"
                       onchange="document.getElementById('laudo-file-name-{{ $aluno->id }}').textContent = this.files[0] ? this.files[0].name : 'Escolher PDF'">
            </label>
            <button type="submit"
                    style="background: #004B8D; color: white; border: none; padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Enviar {{ strtolower(term('laudo')) }}
            </button>
        </div>
        @error('arquivo')<p style="font-size: 12px; color: #EF4444; margin-top: 8px;">{{ $message }}</p>@enderror
    </form>
    @endif

    {{-- Lista de laudos --}}
    @forelse($aluno->laudos as $laudo)
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #E8F0F9; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">{{ $laudo->tipo_label }}</p>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                        {{ $laudo->data_laudo->format('d/m/Y') }}
                        @if($laudo->descricao) · {{ $laudo->descricao }} @endif
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route($role . '.laudos.download', $laudo) }}"
                   style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 600; padding: 5px 12px; border: 1px solid #E8F0F9; background: #E8F0F9; border-radius: 6px;">
                    Baixar
                </a>
                @if($role === 'secretaria')
                    <form method="POST" action="{{ route('secretaria.laudos.destroy', $laudo) }}" style="display: inline;">
                        @csrf @method('DELETE')
                        <button type="button" data-confirm="Remover {{ strtolower(term('laudo')) }}?"
                                style="font-size: 12px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                            Remover
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p style="font-size: 13px; color: #9CA3AF;">Nenhum {{ strtolower(term('laudo')) }} cadastrado.</p>
    @endforelse
</div>