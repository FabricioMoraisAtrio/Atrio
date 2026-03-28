@extends('layouts.app')
@section('title', 'Turmas')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">Turmas</h1>
    <p style="font-size: 13px; color: #9CA3AF; margin: 0;">Turmas associadas à sua escola</p>
</div>

@if($turmas->isEmpty())
    <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 12px; padding: 48px; text-align: center;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 12px; display: block;">
            <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
        <p style="font-size: 14px; color: #9CA3AF; margin: 0;">Nenhuma turma cadastrada.</p>
    </div>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
        @foreach($turmas as $turma)
            @php
                $turno = match($turma->shift) { 'Matutino' => 'Matutino', 'Vespertino' => 'Vespertino', 'Noturno' => 'Noturno', default => $turma->shift };
                $cor = match($turma->shift) {
                    'Matutino'   => ['bg' => '#E8F0F9', 'text' => '#004B8D'],
                    'Vespertino' => ['bg' => '#E6F5F4', 'text' => '#009C8C'],
                    default      => ['bg' => '#F3E8FF', 'text' => '#7C3AED'],
                };
            @endphp
            <div style="background: #fff; border: 1px solid #F3F4F6; border-radius: 14px; padding: 24px;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
                    <div>
                        <div style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 6px;">{{ $turma->name }}</div>
                        <span style="font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: {{ $cor['bg'] }}; color: {{ $cor['text'] }};">
                            {{ $turno }}
                        </span>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 22px; font-weight: 700; color: #111827;">{{ $turma->students_count }}</div>
                        <div style="font-size: 11px; color: #9CA3AF;">alunos</div>
                    </div>
                </div>
                <div style="font-size: 12px; color: #9CA3AF;">Ano letivo {{ $turma->year }}</div>
            </div>
        @endforeach
    </div>
@endif
@endsection
