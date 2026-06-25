@extends('layouts.app')
@section('title', 'Nova Matéria')

@section('content')
<div style="margin-bottom:24px;">
    <a href="{{ route('secretaria.config.index', ['tab' => 'materias']) }}"
       style="font-size:13px;color:var(--text-4);text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para matérias
    </a>
    <h1 style="font-size:22px;font-weight:700;color:var(--text-1);margin:0;">Nova Matéria</h1>
</div>

<div style="background:var(--bg-card);border-radius:12px;border:1px solid var(--border-sub);padding:32px;max-width:560px;">
    @if($errors->any())
        <div style="background:var(--danger-bg);border:1px solid var(--danger-border);color:var(--danger);font-size:13px;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('secretaria.subjects.store') }}">
        @csrf

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Nome da Matéria *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Ex: Língua Portuguesa"
                   style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text-1);outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required
                   placeholder="Ex: portugues"
                   style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text-1);outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            <p style="font-size:11px;color:var(--text-4);margin:4px 0 0;">Identificador único, somente letras, números e hífens.</p>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Label do Responsável *</label>
            <input type="text" name="label_responsavel" value="{{ old('label_responsavel') }}" required
                   placeholder="Ex: Prof. Português"
                   style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text-1);outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
            <p style="font-size:11px;color:var(--text-4);margin:4px 0 0;">Aparece na coluna "Responsável" do inventário no PEI.</p>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Tipo *</label>
            <select name="tipo"
                    style="width:100%;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text-1);outline:none;background:var(--bg-card);">
                <option value="disciplina" {{ old('tipo') === 'disciplina' ? 'selected' : '' }}>Disciplina</option>
                <option value="regente"    {{ old('tipo') === 'regente'    ? 'selected' : '' }}>Regente (preenche Socioemocionais e Funcionais)</option>
            </select>
        </div>

        <div style="margin-bottom:28px;">
            <label style="display:block;font-size:11px;font-weight:600;color:var(--text-3);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Ordem de exibição</label>
            <input type="number" name="ordem" value="{{ old('ordem', 0) }}" min="0"
                   style="width:120px;border:1px solid var(--border);border-radius:8px;padding:10px 12px;font-size:14px;color:var(--text-1);outline:none;"
                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border)'">
        </div>

        <div style="display:flex; gap:10px; align-items:center;">
            <button type="submit"
                    style="background:var(--accent);color:var(--accent-contrast);border:none;padding:11px 24px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                Criar matéria
            </button>
            <a href="{{ route('secretaria.config.index', ['tab' => 'materias']) }}"
               style="padding:11px 24px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--text-2);font-size:14px;font-weight:600;text-decoration:none;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
