@extends('layouts.app')
@section('title', 'Acompanhamento')

@section('content')
<div style="margin-bottom: 28px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 4px;">Acompanhamento</h1>
    <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Progresso e documentos do seu filho</p>
</div>

@forelse($filhos as $filho)
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 24px; margin-bottom: 24px;">

        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #F9FAFB;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 18px; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                    {{ strtoupper(substr($filho->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 2px;">{{ $filho->name }}</h2>
                    <p style="font-size: 12px; color: #9CA3AF; margin: 0;">Matrícula: {{ $filho->registration_number }}</p>
                </div>
            </div>
            @if($filho->is_atypical)
                <span style="background: #F3E8FF; color: #7E22CE; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                    {{ $filho->condition ?? 'Atípico' }}
                </span>
            @endif
        </div>

        @if($filho->schoolClasses->isNotEmpty())
            <div style="margin-bottom: 20px;">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">Turmas</p>
                <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                    @foreach($filho->schoolClasses as $turma)
                        <span style="background: #E8F0F9; color: #004B8D; font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                            {{ $turma->name }} — {{ $turma->shift }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @if($filho->documents->isNotEmpty())
            <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px;">Documentos {{ date('Y') }}</p>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($filho->documents as $doc)
                    <div style="border: 1px solid #F3F4F6; border-radius: 10px; overflow: hidden;">
                        <div style="background: #F9FAFB; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between;">
                            <p style="font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; margin: 0;">
                                {{ str_replace('_', ' ', $doc->type) }}
                            </p>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <p style="font-size: 11px; color: #9CA3AF; margin: 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                                <a href="{{ route('pai.documentos.pdf', $doc) }}" target="_blank"
                                   style="display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 600; color: #004B8D; text-decoration: none; padding: 4px 10px; border-radius: 6px; border: 1px solid #E8F0F9; background: #E8F0F9;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                                    </svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                        <div>
                            @if($doc->type === 'pei')
                                @if(!empty($doc->content['objetivos']))
                                    <div style="padding: 14px 16px; border-top: 1px solid #F9FAFB;">
                                        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Objetivos</p>
                                        <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.6;">{{ $doc->content['objetivos'] }}</p>
                                    </div>
                                @endif
                                @if(!empty($doc->content['progresso']))
                                    <div style="padding: 14px 16px; border-top: 1px solid #F9FAFB;">
                                        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Progresso</p>
                                        <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.6;">{{ $doc->content['progresso'] }}</p>
                                    </div>
                                @endif
                            @elseif($doc->type === 'estudo_caso')
                                @if(!empty($doc->content['potencialidades']))
                                    <div style="padding: 14px 16px; border-top: 1px solid #F9FAFB;">
                                        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Potencialidades</p>
                                        <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.6;">{{ $doc->content['potencialidades'] }}</p>
                                    </div>
                                @endif
                            @elseif($doc->type === 'paee')
                                @if(!empty($doc->content['recursos']))
                                    <div style="padding: 14px 16px; border-top: 1px solid #F9FAFB;">
                                        <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 6px;">Recursos de apoio</p>
                                        <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.6;">{{ $doc->content['recursos'] }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 13px; color: #9CA3AF;">Nenhum documento registrado este ano.</p>
        @endif
    </div>
@empty
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 48px; text-align: center;">
        <p style="font-size: 14px; color: #9CA3AF;">Nenhum aluno vinculado à sua conta ainda.</p>
    </div>
@endforelse
@endsection