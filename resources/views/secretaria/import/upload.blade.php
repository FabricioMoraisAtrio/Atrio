@extends('layouts.app')
@section('title', 'Importar alunos')

@section('content')
<div style="max-width:720px;">
    <div style="margin-bottom:8px;">
        <a href="{{ route('secretaria.alunos.index') }}" style="font-size:13px; color:var(--accent-text); text-decoration:none;">← {{ term('alunos') }}</a>
    </div>
    <h1 style="font-size:22px; font-weight:800; color:var(--text-1); margin:0 0 4px;">Importar {{ strtolower(term('alunos')) }} por planilha</h1>
    <p style="font-size:13px; color:var(--text-3); margin:0 0 18px;">
        Envie um arquivo <strong>CSV</strong> exportado do sistema da escola. O Átrio detecta as colunas, mostra uma
        <strong>pré-visualização</strong> e só grava após a sua confirmação. Nada é apagado e reimportar é seguro.
    </p>

    @if(session('error'))
        <div style="background:var(--danger-bg); border:1px solid var(--danger-border); color:var(--danger); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div style="background:var(--danger-bg); border:1px solid var(--danger-border); color:var(--danger); font-size:13px; border-radius:10px; padding:12px 16px; margin-bottom:18px;">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:24px; margin-bottom:16px;">
        <form method="POST" action="{{ route('secretaria.alunos.importar.preview') }}" enctype="multipart/form-data">
            @csrf
            <label for="arquivo" style="display:flex; flex-direction:column; align-items:center; gap:10px; padding:28px; border:2px dashed var(--border); border-radius:12px; cursor:pointer; background:var(--bg-subtle); text-align:center;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="var(--accent-text)" stroke-width="1.6"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                <span style="font-size:14px; font-weight:600; color:var(--text-1);">Escolher arquivo CSV</span>
                <span id="arquivo-nome" style="font-size:12px; color:var(--text-4);">Nenhum arquivo selecionado · máx. 5MB</span>
            </label>
            <input type="file" id="arquivo" name="arquivo" accept=".csv,text/csv" style="display:none;"
                   onchange="document.getElementById('arquivo-nome').textContent = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado'">
            <div style="margin-top:18px;">
                <button type="submit" style="background:var(--accent); color:var(--accent-contrast); border:none; padding:11px 24px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Pré-visualizar</button>
            </div>
        </form>
    </div>

    <div style="background:var(--bg-card); border:1px solid var(--border-sub); border-radius:12px; padding:18px 20px;">
        <p style="font-size:13px; font-weight:700; color:var(--text-1); margin:0 0 8px;">Colunas reconhecidas</p>
        <p style="font-size:12.5px; color:var(--text-2); line-height:1.7; margin:0;">
            <strong>Nome</strong>, <strong>Matrícula</strong> (chave para não duplicar), Data de nascimento, Responsável,
            E-mail do responsável, Telefone, Turma e Condição.
            A 1ª linha deve conter os títulos das colunas (em qualquer ordem; aceita variações como "RA", "Nasc.", "Classe").
        </p>
        <p style="font-size:12px; color:var(--text-4); margin:10px 0 0;">
            A <strong>condição</strong> entra como sugestão (marca o aluno como atípico para revisão); os campos de CID
            continuam sendo confirmados pela equipe — dado clínico nunca é preenchido automaticamente.
        </p>
    </div>
</div>
@endsection
