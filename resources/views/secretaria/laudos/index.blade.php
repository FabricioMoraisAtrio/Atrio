@extends('layouts.app')
@section('title', 'Laudos')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Laudos</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Todos os laudos enviados para alunos da escola</p>
</div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@forelse($laudos as $tipo => $items)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden; margin-bottom: 16px;">
        <div style="padding: 14px 20px; background: #F9FAFB; border-bottom: 1px solid #F3F4F6; display: flex; align-items: center; gap: 10px;">
            <div style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: #E8F0F9; color: #004B8D;">
                {{ match($tipo) {
                    'medico'          => 'Médico',
                    'psicologico'     => 'Psicológico',
                    'fonoaudiologico' => 'Fonoaudiológico',
                    'neuropediatrico' => 'Neuropediátrico',
                    default           => 'Outro',
                } }}
            </div>
            <span style="font-size: 12px; color: #9CA3AF;">{{ $items->count() }} laudo(s)</span>
        </div>
        <div>
            @foreach($items as $laudo)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            {{ strtoupper(substr($laudo->student->name, 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $laudo->student->name }}</p>
                            <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
                                {{ $laudo->data_laudo->format('d/m/Y') }}
                                @if($laudo->descricao) · {{ $laudo->descricao }} @endif
                                · Enviado por {{ $laudo->uploader->name }}
                            </p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <a href="{{ route('secretaria.laudos.download', $laudo) }}"
                           style="font-size: 12px; color: #004B8D; text-decoration: none; font-weight: 600; padding: 5px 12px; border: 1px solid #E8F0F9; background: #E8F0F9; border-radius: 6px;">
                            Baixar
                        </a>
                        <form method="POST" action="{{ route('secretaria.laudos.destroy', $laudo) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm="Remover laudo?"
                                    style="font-size: 12px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                                Remover
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 48px; text-align: center;">
        <p style="font-size: 14px; color: #9CA3AF;">Nenhum laudo cadastrado ainda.</p>
    </div>
@endforelse
@endsection