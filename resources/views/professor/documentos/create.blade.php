@extends('layouts.app')
@section('title', 'Novo Documento')

@section('content')
<div style="max-width: 640px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('professor.dashboard') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o painel
        </a>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                {{ $type === 'pei' ? 'background: #E8F0F9; color: #004B8D;' : ($type === 'paee' ? 'background: #E6F5F4; color: #009C8C;' : 'background: #F5EDE6; color: #7C3700;') }}">
                {{ strtoupper(str_replace('_', ' ', $type)) }}
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $aluno->name }}</h1>
        </div>
    </div>

    @if($errors->any())
        <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('professor.alunos.documentos.store', $aluno) }}">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            @if($type === 'estudo_caso')
                @include('secretaria.documentos.partials.estudo_caso')
            @elseif($type === 'pei')
                @include('secretaria.documentos.partials.pei')
            @elseif($type === 'paee')
                @include('secretaria.documentos.partials.paee')
            @endif

            @include('secretaria.documentos.partials.observacoes_livres')

            <div style="display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #F3F4F6;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Salvar documento
                </button>
                <a href="{{ route('professor.dashboard') }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection