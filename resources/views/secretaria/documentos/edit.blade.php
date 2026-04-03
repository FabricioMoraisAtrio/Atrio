@extends('layouts.app')
@section('title', 'Editar Documento')

@section('content')
<div style="max-width: 1000px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.documentos.show', $documento) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o documento
        </a>
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                {{ $documento->type === 'pei' ? 'background: #E8F0F9; color: #004B8D;' : ($documento->type === 'paee' ? 'background: #E6F5F4; color: #009C8C;' : 'background: #F5EDE6; color: #7C3700;') }}">
                {{ strtoupper(str_replace('_', ' ', $documento->type)) }}
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0;">{{ $documento->student->name }}</h1>
        </div>
    </div>

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 28px;">
        <form method="POST" action="{{ route('secretaria.documentos.update', $documento) }}">
            @csrf @method('PUT')

            @if($documento->type === 'estudo_caso')
                @include('secretaria.documentos.partials.estudo_caso')
            @elseif($documento->type === 'pei')
                @include('secretaria.documentos.partials.pei')
            @elseif($documento->type === 'paee')
                @include('secretaria.documentos.partials.paee')
            @endif

            @include('secretaria.documentos.partials.observacoes_livres')

            <div style="display: flex; gap: 12px; margin-top: 28px; padding-top: 20px; border-top: 1px solid #F3F4F6;">
                <button type="submit"
                        style="background: #004B8D; color: white; border: none; padding: 11px 24px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Atualizar
                </button>
                <a href="{{ route('secretaria.documentos.show', $documento) }}"
                   style="padding: 11px 20px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection