@extends('layouts.app')
@section('title', $turma->name)

@section('content')
<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.turmas.index') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para {{ strtolower(term('turmas')) }}
    </a>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $turma->name }}</h1>
            <p style="font-size: 13px; color: #9CA3AF; margin: 0;">{{ $turma->shift }} · {{ $turma->year }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="document.getElementById('modal-aluno').style.display='flex'"
                    style="background: #004B8D; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                Novo {{ strtolower(term('aluno')) }}
            </button>
            <a href="{{ route('secretaria.turmas.edit', $turma) }}"
               style="padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                Editar {{ strtolower(term('turma')) }}
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
        {{ session('success') }}
    </div>
@endif

{{-- Cards métricas --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Total de {{ strtolower(term('alunos')) }}</p>
        <p style="font-size: 28px; font-weight: 700; color: #111827; margin: 0;">{{ $turma->students->count() }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">{{ term('alunos') }} atípicos</p>
        <p style="font-size: 28px; font-weight: 700; color: #7E22CE; margin: 0;">{{ $turma->students->where('is_atypical', true)->count() }}</p>
    </div>
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 20px;">
        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">{{ term('professores') }}</p>
        <p style="font-size: 28px; font-weight: 700; color: #004B8D; margin: 0;">{{ $turma->teachers->count() }}</p>
    </div>
</div>

{{-- Lista de alunos --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid #F3F4F6;">
        <p style="font-size: 13px; font-weight: 600; color: #374151; margin: 0;">{{ term('alunos') }} matriculados</p>
    </div>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB;">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">{{ term('aluno') }}</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Matrícula</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Est. Caso</th>
                <th style="padding: 12px 20px;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($turma->students as $aluno)
            <tr style="border-top: 1px solid #F9FAFB;"
                onmouseover="this.style.background='#FAFAFA'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            {{ strtoupper(substr($aluno->name, 0, 1)) }}
                        </div>
                        <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $aluno->name }}</p>
                    </div>
                </td>
                <td style="padding: 14px 20px; font-size: 13px; color: #6B7280;">{{ $aluno->registration_number }}</td>
                <td style="padding: 14px 20px;">
                    @if($aluno->is_atypical)
                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Atípico</span>
                    @else
                        <span style="background: #F3F4F6; color: #6B7280; font-size: 11px; padding: 3px 8px; border-radius: 20px;">Típico</span>
                    @endif
                </td>
                <td style="padding: 14px 20px;">
                    @if($aluno->has_case_study)
                        <span style="background: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">✓ Preenchido</span>
                    @else
                        <span style="background: #FEF2F2; color: #991B1B; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">Pendente</span>
                    @endif
                </td>
                <td style="padding: 14px 20px; text-align: right;">
                    <a href="{{ route('secretaria.alunos.show', $aluno) }}"
                       style="font-size: 13px; color: #004B8D; text-decoration: none; font-weight: 500;">Ver</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
                    Nenhum {{ strtolower(term('aluno')) }} matriculado nesta {{ strtolower(term('turma')) }}.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal novo aluno --}}
<div id="modal-aluno"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div style="background: #fff; border-radius: 16px; padding: 32px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; position: relative;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
            <h2 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Novo {{ strtolower(term('aluno')) }}</h2>
            <button onclick="document.getElementById('modal-aluno').style.display='none'"
                    style="background: none; border: none; cursor: pointer; color: #9CA3AF; padding: 4px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('secretaria.alunos.store') }}">
            @csrf
            <input type="hidden" name="school_class_id" value="{{ $turma->id }}">

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome completo</label>
                <input type="text" name="name" required
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Matrícula</label>
                <input type="text" name="registration_number" required
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Data de nascimento</label>
                <input type="date" name="birth_date" required
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="border: 1px solid #F3F4F6; border-radius: 10px; padding: 16px; margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_atypical" value="1"
                           id="modal_is_atypical"
                           onchange="document.getElementById('modal_atypical_fields').style.display = this.checked ? 'block' : 'none'">
                    <span style="font-size: 14px; font-weight: 600; color: #111827;">{{ term('aluno') }} com perfil de atipicidade</span>
                </label>

                <div id="modal_atypical_fields" style="display: none; margin-top: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; margin-bottom: 12px;">
                        @foreach([
                            'cid_autismo' => 'Autismo (TEA)',
                            'cid_tdah' => 'TDAH',
                            'cid_down' => 'Síndrome de Down',
                            'cid_deficiencia_intelectual' => 'D. Intelectual',
                            'cid_deficiencia_visual' => 'D. Visual',
                            'cid_deficiencia_auditiva' => 'D. Auditiva',
                        ] as $field => $label)
                            <label style="display: flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 6px; border: 1px solid #F3F4F6; cursor: pointer; font-size: 12px; color: #374151;">
                                <input type="checkbox" name="{{ $field }}" value="1">
                                {{ $label }}
                            </label>
                        @endforeach
                        <label style="display: flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 6px; border: 1px solid #F3F4F6; cursor: pointer; font-size: 12px; color: #374151;">
                            <input type="checkbox" name="cid_outros" value="1"
                                   onchange="document.getElementById('modal_condition').style.display = this.checked ? 'block' : 'none'">
                            Outros
                        </label>
                    </div>
                    <div id="modal_condition" style="display: none;">
                        <input type="text" name="condition" placeholder="Descreva a condição ou CID..."
                               style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 13px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                               onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; flex: 1;">
                    Cadastrar {{ strtolower(term('aluno')) }}
                </button>
                <button type="button" onclick="document.getElementById('modal-aluno').style.display='none'"
                        style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; border: 1px solid #E5E7EB; background: none; cursor: pointer;">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Fecha modal clicando fora
document.getElementById('modal-aluno').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endsection