@extends('layouts.app')
@section('title', 'Editar Turma')

@section('content')
<div style="max-width: 480px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.turmas.index') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para turmas
        </a>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">Editar — {{ $turma->name }}</h1>
    </div>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('secretaria.turmas.update', $turma) }}">
            @csrf @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Nome da turma</label>
                <input type="text" name="name" value="{{ old('name', $turma->name) }}"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
                @error('name')<p style="font-size: 12px; color: #EF4444; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Turno</label>
                <select name="shift"
                        style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;">
                    @foreach(['Matutino','Vespertino','Noturno'] as $turno)
                        <option value="{{ $turno }}" {{ old('shift', $turma->shift) == $turno ? 'selected' : '' }}>{{ $turno }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 28px;">
                <label style="display: block; font-size: 11px; font-weight: 600; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 8px;">Ano letivo</label>
                <input type="number" name="year" value="{{ old('year', $turma->year) }}" min="2020" max="2099"
                       style="width: 100%; border: none; border-bottom: 2px solid #E5E7EB; padding: 8px 0; font-size: 14px; color: #111827; outline: none; background: transparent; box-sizing: border-box;"
                       onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Atualizar
                </button>
                <a href="{{ route('secretaria.turmas.index') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection