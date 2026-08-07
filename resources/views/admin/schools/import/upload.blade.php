@extends('admin.layouts.app')
@section('title', 'Importar alunos')

@section('content')
@php
    $campos = [
        ['nome', 'Nome completo', 'Obrigatório'],
        ['matricula', 'Matrícula / RA', 'Obrigatório — chave para não duplicar'],
        ['data_nascimento', 'Data de nascimento', 'dd/mm/aaaa ou aaaa-mm-dd'],
        ['responsavel_nome', 'Responsável 1 — nome', ''],
        ['responsavel_email', 'Responsável 1 — e-mail', ''],
        ['responsavel_telefone', 'Responsável 1 — telefone', ''],
        ['responsavel_2_nome', 'Responsável 2 — nome', 'Opcional'],
        ['responsavel_2_email', 'Responsável 2 — e-mail', 'Opcional'],
        ['responsavel_2_telefone', 'Responsável 2 — telefone', 'Opcional'],
        ['turma', 'Turma', 'Cria/matricula no ano corrente'],
        ['condicao', 'Condição', 'Texto livre — entra como sugestão (marca atípico p/ revisão)'],
    ];
@endphp

<div style="max-width:860px;">
    <div style="margin-bottom:8px;">
        <a href="{{ route('admin.schools.edit', $school) }}" style="font-size:13px; color:var(--adm-accent); text-decoration:none;">← {{ $school->name }}</a>
    </div>
    <h1 style="font-size:22px; font-weight:800; color:var(--adm-text); margin:0 0 4px;">Importar estudantes — {{ $school->name }}</h1>
    <p style="font-size:13px; color:var(--adm-text-3); margin:0 0 18px;">
        Disponível apenas aqui no painel. Envie um <strong>CSV</strong>; o sistema detecta as colunas, mostra uma
        <strong>pré-visualização</strong> e só grava após confirmação. Deduplica por matrícula (reimportar é seguro) e nunca apaga.
    </p>

    @if(session('error'))
        <div style="background:var(--adm-red-bg); border:1px solid #F4B5AE; color:var(--adm-red); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div style="background:var(--adm-red-bg); border:1px solid #F4B5AE; color:var(--adm-red); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    {{-- Upload --}}
    <div class="adm-card" style="padding:24px; margin-bottom:16px;">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
            <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0;">1. Modelo padrão</p>
            <a href="{{ route('admin.schools.import.template', $school) }}" class="adm-btn adm-btn-ghost">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Baixar modelo CSV
            </a>
        </div>
        <div style="font-family:monospace; font-size:12px; color:var(--adm-text-2); background:var(--adm-border-2); border:1px solid var(--adm-border); border-radius:8px; padding:10px 12px; overflow-x:auto; white-space:nowrap;">{{ implode(';', $header) }}</div>
        <p style="font-size:12px; color:var(--adm-text-3); margin:10px 0 0;">A 1ª linha do arquivo deve conter os títulos das colunas (em qualquer ordem; aceita variações como "RA", "Nasc.", "Classe").</p>
    </div>

    <div class="adm-card" style="padding:24px; margin-bottom:16px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 16px;">2. Enviar arquivo</p>
        <form method="POST" action="{{ route('admin.schools.import.preview', $school) }}" enctype="multipart/form-data">
            @csrf
            <label for="arquivo" style="display:flex; flex-direction:column; align-items:center; gap:10px; padding:26px; border:2px dashed var(--adm-border); border-radius:12px; cursor:pointer; background:var(--adm-border-2); text-align:center;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--adm-accent)" stroke-width="1.6"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                <span style="font-size:14px; font-weight:600; color:var(--adm-text);">Escolher arquivo CSV</span>
                <span id="arquivo-nome" style="font-size:12px; color:var(--adm-text-3);">Nenhum arquivo selecionado · máx. 5MB</span>
            </label>
            <input type="file" id="arquivo" name="arquivo" accept=".csv,text/csv" style="display:none;"
                   onchange="document.getElementById('arquivo-nome').textContent = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado'">
            <div style="margin-top:18px;">
                <button type="submit" class="adm-btn adm-btn-primary">Pré-visualizar</button>
            </div>
        </form>
    </div>

    {{-- Campos --}}
    <div class="adm-card" style="padding:24px;">
        <p style="font-size:14px; font-weight:700; color:var(--adm-text); margin:0 0 4px;">Campos lidos pelo sistema</p>
        <p style="font-size:12px; color:var(--adm-text-3); margin:0 0 14px;">Tudo que alimenta o cadastro do aluno. Dados clínicos (CIDs, laudos) seguem confirmados pela equipe — nunca preenchidos automaticamente.</p>
        <table style="width:100%; border-collapse:collapse; font-size:12.5px;">
            <thead>
                <tr style="text-align:left; background:var(--adm-border-2);">
                    <th style="padding:8px 12px; font-size:10.5px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Coluna</th>
                    <th style="padding:8px 12px; font-size:10.5px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Descrição</th>
                    <th style="padding:8px 12px; font-size:10.5px; font-weight:700; color:var(--adm-text-3); text-transform:uppercase;">Obs.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campos as $c)
                <tr style="border-top:1px solid var(--adm-border-2);">
                    <td style="padding:8px 12px; font-family:monospace; color:var(--adm-text);">{{ $c[0] }}</td>
                    <td style="padding:8px 12px; color:var(--adm-text-2);">{{ $c[1] }}</td>
                    <td style="padding:8px 12px; color:var(--adm-text-3);">{{ $c[2] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
