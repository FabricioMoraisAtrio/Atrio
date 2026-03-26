@extends('layouts.app')
@section('title', 'Documentos')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Documentos</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Todos os documentos gerados na escola</p>
</div>

@forelse($documentos as $tipo => $docs)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden; margin-bottom: 16px;">
        <div style="padding: 14px 20px; background: #F9FAFB; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; gap: 10px;">
            <div style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                {{ $tipo === 'pei' ? 'background: #E8F0F9; color: #004B8D;' : ($tipo === 'paee' ? 'background: #E6F5F4; color: #009C8C;' : 'background: #F5EDE6; color: #7C3700;') }}">
                {{ strtoupper(str_replace('_', ' ', $tipo)) }}
            </div>
            <span style="font-size: 12px; color: #9CA3AF;">{{ $docs->count() }} documento(s)</span>
        </div>
        <div>
            @foreach($docs as $doc)
                <a href="{{ route('secretaria.documentos.show', $doc) }}"
                   style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; text-decoration: none; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}"
                   onmouseover="this.style.background='#FAFAFA'"
                   onmouseout="this.style.background='transparent'">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            {{ strtoupper(substr($doc->student->name, 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $doc->student->name }}</p>
                            <p style="font-size: 12px; color: #9CA3AF; margin: 0;">{{ $doc->year }} · {{ $doc->author->name }} · {{ $doc->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <span style="font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px;
                        {{ $doc->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                        {{ $doc->status === 'published' ? 'Publicado' : 'Rascunho' }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@empty
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 48px; text-align: center;">
        <p style="font-size: 14px; color: #9CA3AF;">Nenhum documento gerado ainda.</p>
    </div>
@endforelse
@endsection