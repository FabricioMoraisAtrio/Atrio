@extends('layouts.app')
@section('title', strtoupper(str_replace('_', ' ', $documento->type)))

@section('content')
<div style="max-width: 680px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('secretaria.alunos.show', $documento->student) }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para {{ $documento->student->name }}
        </a>

        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                    {{ $documento->type === 'pei' ? 'background: #E8F0F9; color: #004B8D;' : ($documento->type === 'paee' ? 'background: #E6F5F4; color: #009C8C;' : 'background: #F5EDE6; color: #7C3700;') }}">
                    {{ strtoupper(str_replace('_', ' ', $documento->type)) }}
                </div>
                <div>
                    <h1 style="font-size: 20px; font-weight: 700; color: #111827; margin: 0 0 2px;">{{ $documento->student->name }}</h1>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Ano letivo {{ $documento->year }}</p>
                </div>
            </div>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('secretaria.documentos.pdf', $documento) }}" target="_blank"
                   style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('secretaria.documentos.word', $documento) }}"
   style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
    </svg>
    Word
</a>
                <a href="{{ route('secretaria.documentos.edit', $documento) }}"
                   style="background: #004B8D; color: white; text-decoration: none; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    Editar
                </a>
                <form method="POST" action="{{ route('secretaria.documentos.destroy', $documento) }}" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Remover documento?')"
                            style="padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid #FECACA; background: #FEF2F2; color: #EF4444; cursor: pointer;">
                        Remover
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
        @foreach($documento->content as $campo => $valor)
            @if($valor)
            <div style="padding: 20px 24px; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">
                    {{ str_replace('_', ' ', $campo) }}
                </p>
                @if(is_array($valor))
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach($valor as $tag)
                            <span style="padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; background: #E8F0F9; color: #004B8D;">{{ $tag }}</span>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.7; white-space: pre-line;">{{ $valor }}</p>
                @endif
            </div>
            @endif
        @endforeach
    </div>

    <div style="margin-top: 16px; display: flex; align-items: center; justify-content: space-between;">
        <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
            Criado por {{ $documento->author->name }} · {{ $documento->created_at->format('d/m/Y') }}
        </p>
        <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
            {{ $documento->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
            {{ $documento->status === 'published' ? 'Publicado' : 'Rascunho' }}
        </span>
    </div>
</div>
@endsection