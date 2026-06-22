@extends('layouts.app')
@section('title', 'Metas Acadêmicas — ' . $aluno->name)

@section('content')
<div style="max-width: 860px;">

    {{-- Cabeçalho --}}
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o PEI de {{ $aluno->name }}
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Metas de Habilidades — {{ $ano }}</h1>
        <p style="font-size: 13px; color: #6B7280; margin: 0;">
            Cadastre as metas customizadas para <strong>{{ $aluno->name }}</strong>.
            As <strong>metas acadêmicas</strong> aparecem no PEI para o professor avaliar; as
            <strong>socioemocionais</strong> e <strong>funcionais</strong> são preenchidas como texto livre.
        </p>
    </div>

    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.alunos.metas-academicas.update', $aluno) }}">
        @csrf @method('PUT')

        {{-- ─── Metas Acadêmicas (por matéria) ─── --}}
        <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0 0 12px;">Metas Acadêmicas</p>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($subjects as $subject)
                @php $metas = $metasPorMateria->get($subject->id, collect()); @endphp
                <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; overflow: hidden;">
                    <div style="padding: 14px 20px; background: #F9FAFB; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; gap: 10px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #004B8D; flex-shrink: 0;"></span>
                        <span style="font-size: 14px; font-weight: 700; color: #111827;">{{ $subject->name }}</span>
                    </div>

                    <div style="padding: 18px 20px;">
                        <div class="metas-list" data-subject="{{ $subject->id }}" style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 12px;">
                            @forelse($metas as $meta)
                                <div class="meta-row" style="display: flex; align-items: center; gap: 8px;">
                                    <input type="text" name="metas[{{ $subject->id }}][]" value="{{ $meta->meta }}"
                                           placeholder="Descreva a meta acadêmica..."
                                           style="flex: 1; border: 1px solid #E5E7EB; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #374151; outline: none; box-sizing: border-box;"
                                           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                                    <button type="button" onclick="removerMeta(this)"
                                            style="color: #EF4444; background: none; border: none; cursor: pointer; padding: 4px; flex-shrink: 0;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @empty
                                <div class="meta-row" style="display: flex; align-items: center; gap: 8px;">
                                    <input type="text" name="metas[{{ $subject->id }}][]" value=""
                                           placeholder="Descreva a meta acadêmica..."
                                           style="flex: 1; border: 1px solid #E5E7EB; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #374151; outline: none; box-sizing: border-box;"
                                           onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                                    <button type="button" onclick="removerMeta(this)"
                                            style="color: #EF4444; background: none; border: none; cursor: pointer; padding: 4px; flex-shrink: 0;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforelse
                        </div>

                        <button type="button" onclick="adicionarMeta({{ $subject->id }})"
                                style="background: #F9FAFB; color: #374151; border: 1px dashed #D1D5DB; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                            Adicionar meta
                        </button>
                    </div>
                </div>
            @empty
                <div style="background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 10px; padding: 16px 20px;">
                    <p style="font-size: 13px; color: #92400E; margin: 0;">
                        Nenhuma matéria cadastrada. Cadastre a grade curricular em
                        <a href="{{ route('secretaria.config.index', ['tab' => 'materias']) }}" style="color: #92400E; font-weight: 600;">Configurações → Matérias</a>.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- ─── Metas Socioemocionais e Funcionais (texto livre) ─── --}}
        <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 20px; margin-top: 16px; display: flex; flex-direction: column; gap: 20px;">
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #007A6E; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                    Metas Socioemocionais
                </label>
                <textarea name="metas_socioemocionais" rows="5"
                    placeholder="Descreva as metas socioemocionais para o aluno (regulação emocional, interação social, autonomia...)."
                    style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                    onfocus="this.style.borderColor='#007A6E'" onblur="this.style.borderColor='#E5E7EB'">{{ old('metas_socioemocionais', $peiGlobal['metas_socioemocionais'] ?? '') }}</textarea>
            </div>
            <div>
                <label style="display: block; font-size: 11px; font-weight: 700; color: #6D28D9; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">
                    Metas Funcionais
                </label>
                <textarea name="metas_funcionais" rows="5"
                    placeholder="Descreva as metas funcionais para o aluno (atividades de vida diária, autocuidado, mobilidade, comunicação funcional...)."
                    style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 12px; font-size: 14px; color: #111827; outline: none; resize: vertical; box-sizing: border-box; font-family: inherit; line-height: 1.6;"
                    onfocus="this.style.borderColor='#6D28D9'" onblur="this.style.borderColor='#E5E7EB'">{{ old('metas_funcionais', $peiGlobal['metas_funcionais'] ?? '') }}</textarea>
            </div>
            <p style="font-size: 11px; color: #9CA3AF; margin: 0;">
                As metas socioemocionais e funcionais também podem ser preenchidas pelo professor regente no PEI.
            </p>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
            <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                Cancelar
            </a>
            <button type="submit"
                    style="background: #004B8D; color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                Salvar metas
            </button>
        </div>
    </form>
</div>

<script>
function adicionarMeta(subjectId) {
    const list = document.querySelector(`.metas-list[data-subject="${subjectId}"]`);
    const row = document.createElement('div');
    row.className = 'meta-row';
    row.style.cssText = 'display:flex;align-items:center;gap:8px;';
    row.innerHTML = `
        <input type="text" name="metas[${subjectId}][]" value="" placeholder="Descreva a meta acadêmica..."
               style="flex:1;border:1px solid #E5E7EB;border-radius:8px;padding:9px 12px;font-size:13px;color:#374151;outline:none;box-sizing:border-box;"
               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        <button type="button" onclick="removerMeta(this)"
                style="color:#EF4444;background:none;border:none;cursor:pointer;padding:4px;flex-shrink:0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>`;
    list.appendChild(row);
    row.querySelector('input').focus();
}

function removerMeta(btn) {
    const list = btn.closest('.metas-list');
    btn.closest('.meta-row').remove();
    // Mantém ao menos uma linha vazia para facilitar novo cadastro
    if (list && list.querySelectorAll('.meta-row').length === 0) {
        adicionarMeta(list.getAttribute('data-subject'));
    }
}
</script>
@endsection
