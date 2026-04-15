@extends('layouts.app')
@section('title', 'Nova Matéria')

@section('content')
<div style="margin-bottom:24px;">
    <a href="{{ route('secretaria.config.index', ['tab' => 'materias']) }}"
       style="font-size:13px;color:#9CA3AF;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Voltar para matérias
    </a>
    <h1 style="font-size:22px;font-weight:700;color:#111827;margin:0;">Nova Matéria</h1>
</div>

<div style="background:#fff;border-radius:12px;border:1px solid #F3F4F6;padding:32px;max-width:560px;">
    @if($errors->any())
        <div style="background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;font-size:13px;border-radius:8px;padding:12px 16px;margin-bottom:20px;">
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
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Nome da Matéria *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Ex: Língua Portuguesa"
                   style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:14px;color:#111827;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required
                   placeholder="Ex: portugues"
                   style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:14px;color:#111827;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            <p style="font-size:11px;color:#9CA3AF;margin:4px 0 0;">Identificador único, somente letras, números e hífens.</p>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Label do Responsável *</label>
            <input type="text" name="label_responsavel" value="{{ old('label_responsavel') }}" required
                   placeholder="Ex: Prof. Português"
                   style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:14px;color:#111827;outline:none;box-sizing:border-box;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
            <p style="font-size:11px;color:#9CA3AF;margin:4px 0 0;">Aparece na coluna "Responsável" do inventário no PEI.</p>
        </div>

        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Tipo *</label>
            <select name="tipo"
                    style="width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:14px;color:#111827;outline:none;background:#fff;">
                <option value="disciplina" {{ old('tipo') === 'disciplina' ? 'selected' : '' }}>Disciplina</option>
                <option value="regente"    {{ old('tipo') === 'regente'    ? 'selected' : '' }}>Regente (preenche Socioemocionais e Funcionais)</option>
            </select>
        </div>

        <div style="margin-bottom:28px;">
            <label style="display:block;font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:8px;">Ordem de exibição</label>
            <input type="number" name="ordem" value="{{ old('ordem', 0) }}" min="0"
                   style="width:120px;border:1px solid #E5E7EB;border-radius:8px;padding:10px 12px;font-size:14px;color:#111827;outline:none;"
                   onfocus="this.style.borderColor='#004B8D'" onblur="this.style.borderColor='#E5E7EB'">
        </div>

        <button type="submit"
                style="background:#004B8D;color:white;border:none;padding:11px 24px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
            Criar matéria
        </button>
    </form>
</div>
@endsection
