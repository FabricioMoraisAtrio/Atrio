@extends('layouts.app')
@section('title', ($reuniao->exists ? 'Editar' : 'Nova') . ' reunião — ' . $aluno->name)

@php
    use App\Models\Meeting;

    $editando = $reuniao->exists;
    $action   = $editando
        ? route('secretaria.alunos.reunioes.update', [$aluno, $reuniao])
        : route('secretaria.alunos.reunioes.store', $aluno);

    $dataValor = old('data', $reuniao->data ? $reuniao->data->format('Y-m-d') : '');

    $labelStyle = 'font-size:12px;font-weight:600;color:var(--text-2);display:block;margin-bottom:6px;';
    $inputStyle = 'width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:13px;color:var(--text-2);outline:none;box-sizing:border-box;';
@endphp

@section('content')
<div style="max-width: 720px;">

    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.reunioes.index', $aluno) }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para as reuniões
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $editando ? 'Editar reunião' : 'Nova reunião' }}</h1>
        <p style="font-size: 13px; color: var(--text-3); margin: 4px 0 0;">{{ $aluno->name }}</p>
    </div>

    @if($errors->any())
        <div style="background: var(--danger-bg); border: 1px solid var(--danger-border); color: var(--danger); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ $action }}">
        @csrf
        @if($editando) @method('PUT') @endif

        <div style="background: var(--bg-card); border: 1px solid var(--border-sub); border-radius: 12px; padding: 22px 24px; display: flex; flex-direction: column; gap: 18px;">

            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 160px;">
                    <label style="{{ $labelStyle }}">Data</label>
                    <input type="date" name="data" value="{{ $dataValor }}" required style="{{ $inputStyle }}">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="{{ $labelStyle }}">Tipo</label>
                    <select name="tipo" style="{{ $inputStyle }} cursor:pointer;">
                        @foreach(Meeting::TIPOS as $val => $label)
                            <option value="{{ $val }}" @selected(old('tipo', $reuniao->tipo) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label style="{{ $labelStyle }}">Participantes</label>
                <textarea name="participantes" rows="2" required placeholder="Ex.: Coordenação, professora regente, mãe do estudante..."
                          style="{{ $inputStyle }} resize:vertical;">{{ old('participantes', $reuniao->participantes) }}</textarea>
            </div>

            <div>
                <label style="{{ $labelStyle }}">Pauta <span style="color:var(--text-4);font-weight:400;">(opcional)</span></label>
                <textarea name="pauta" rows="3" placeholder="Assuntos tratados..."
                          style="{{ $inputStyle }} resize:vertical;">{{ old('pauta', $reuniao->pauta) }}</textarea>
            </div>

            <div>
                <label style="{{ $labelStyle }}">Encaminhamentos <span style="color:var(--text-4);font-weight:400;">(opcional)</span></label>
                <textarea name="encaminhamentos" rows="3" placeholder="Decisões e próximos passos..."
                          style="{{ $inputStyle }} resize:vertical;">{{ old('encaminhamentos', $reuniao->encaminhamentos) }}</textarea>
            </div>

            <div>
                <label style="{{ $labelStyle }}">Observações <span style="color:var(--text-4);font-weight:400;">(opcional)</span></label>
                <textarea name="observacoes" rows="2" placeholder="Outras anotações..."
                          style="{{ $inputStyle }} resize:vertical;">{{ old('observacoes', $reuniao->observacoes) }}</textarea>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
            <a href="{{ route('secretaria.alunos.reunioes.index', $aluno) }}"
               style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                Cancelar
            </a>
            <button type="submit"
                    style="background: var(--accent); color: white; border: none; padding: 11px 28px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                {{ $editando ? 'Salvar alterações' : 'Registrar reunião' }}
            </button>
        </div>
    </form>
</div>
@endsection
