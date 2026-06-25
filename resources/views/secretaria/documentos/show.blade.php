@extends('layouts.app')
@section('title', strtoupper(str_replace('_', ' ', $documento->type)))

@section('content')
<div style="max-width: 1000px;">
    <div style="margin-bottom: 24px;">
        @php
            $backUrl   = request('back') ? urldecode(request('back')) : route('secretaria.alunos.show', $documento->student);
            $backLabel = request('back') ? 'Voltar' : 'Voltar para ' . $documento->student->name;
        @endphp
        <a href="{{ $backUrl }}"
           style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            {{ $backLabel }}
        </a>

        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
                    {{ $documento->type === 'pei' ? 'background: #E8F0F9; color: var(--accent-text);' : ($documento->type === 'paee' ? 'background: #E6F5F4; color: var(--teal);' : 'background: #F5EDE6; color: var(--brown);') }}">
                    {{ strtoupper(str_replace('_', ' ', $documento->type)) }}
                </div>
                <div>
                    <h1 style="font-size: 20px; font-weight: 700; color: var(--text-1); margin: 0 0 2px;">{{ $documento->student->name }}</h1>
                    <p style="font-size: 12px; color: var(--text-4); margin: 0;">Ano letivo {{ $documento->year }}</p>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                {{-- Visualizar (abre inline no navegador) --}}
                <a href="{{ route('secretaria.documentos.preview', $documento) }}" target="_blank"
                   style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid var(--border); color: var(--text-2);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    Visualizar
                </a>
                {{-- Baixar PDF --}}
                @if(auth()->user()->pdf_preview)
                <button type="button"
                        onclick="abrirPreview()"
                        style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid var(--border); color: var(--text-2); background: var(--bg-card); cursor: pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Baixar PDF
                </button>
                @else
                <a href="{{ route('secretaria.documentos.pdf', $documento) }}"
                   style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid var(--border); color: var(--text-2);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Baixar PDF
                </a>
                @endif
                <a href="{{ route('secretaria.documentos.edit', $documento) }}"
                   style="background: var(--accent); color: white; text-decoration: none; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    Editar
                </a>
                <form method="POST" action="{{ route('secretaria.documentos.destroy', $documento) }}" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="button" data-confirm="Remover documento?"
                            style="padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid var(--danger-border); background: var(--danger-bg); color: var(--danger); cursor: pointer;">
                        Remover
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: var(--success-bg); border: 1px solid var(--success-border); color: var(--success); font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); overflow: hidden;">
        @foreach($documento->content as $campo => $valor)
            @if($valor)
            <div style="padding: 20px 24px; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                <p style="font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">
                    {{ str_replace('_', ' ', $campo) }}
                </p>
                @if(is_array($valor))
                    @php $primeiroItem = reset($valor); @endphp
                    @if($campo === 'equipe_participantes')
                        {{-- Equipe: array de {nome, cargo} --}}
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach($valor as $p)
                                @if(!empty($p['nome']))
                                <div style="display: flex; gap: 12px; font-size: 13px; color: var(--text-2);">
                                    <span style="font-weight: 600; min-width: 180px;">{{ $p['nome'] }}</span>
                                    <span style="color: var(--text-4);">{{ $p['cargo'] ?? '' }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @elseif(is_array($primeiroItem))
                        {{-- Array de objetos com 'meta' --}}
                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                            @foreach($valor as $item)
                                @if(!empty($item['meta']))
                                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; background: var(--bg-subtle); color: var(--text-2);">{{ $item['meta'] }}</span>
                                @endif
                            @endforeach
                        </div>
                    @else
                        {{-- Array simples de strings --}}
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($valor as $tag)
                                <span style="padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; background: var(--accent-bg); color: var(--accent-text);">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                @else
                    <p style="font-size: 14px; color: var(--text-2); margin: 0; line-height: 1.7; white-space: pre-line;">{{ $valor }}</p>
                @endif
            </div>
            @endif
        @endforeach
    </div>

    <div style="margin-top: 16px;">
        <p style="font-size: 12px; color: var(--text-4); margin: 0;">
            Criado por {{ $documento->author->name }} · {{ $documento->created_at->format('d/m/Y') }}
        </p>
    </div>
</div>

@if(auth()->user()->pdf_preview)
{{-- Modal preview PDF --}}
<div id="modal-pdf-preview"
     style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999; align-items: center; justify-content: center; padding: 24px;">
    <div style="background: var(--bg-card); border-radius: 16px; width: 100%; max-width: 900px; height: 90vh; display: flex; flex-direction: column; overflow: hidden;">
        {{-- Header --}}
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--border-sub); flex-shrink: 0;">
            <div>
                <p style="font-size: 14px; font-weight: 700; color: var(--text-1); margin: 0;">
                    {{ strtoupper(str_replace('_', ' ', $documento->type)) }} — {{ $documento->student->name }}
                </p>
                <p style="font-size: 12px; color: var(--text-4); margin: 0;">Pré-visualização do documento</p>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <a href="{{ route('secretaria.documentos.pdf', $documento) }}"
                   style="display: flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; background: var(--accent); color: #fff;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Baixar
                </a>
                <button type="button" onclick="fecharPreview()"
                        style="background: var(--bg-subtle); border: none; cursor: pointer; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--text-3);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        {{-- Preview container --}}
        <div id="pdf-preview-container" style="flex: 1; width: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
            <p style="font-size: 13px; color: var(--text-4);">Carregando pré-visualização...</p>
        </div>
    </div>
</div>
<script>
const _previewUrl = '{{ route('secretaria.documentos.preview', $documento) }}';
let _previewLoaded = false;
let _previewBlobUrl = null;

function abrirPreview() {
    document.getElementById('modal-pdf-preview').style.display = 'flex';
    if (_previewLoaded) return;
    _previewLoaded = true;

    const container = document.getElementById('pdf-preview-container');
    container.innerHTML = '<p style="font-size:13px;color:#9CA3AF;">Carregando pré-visualização...</p>';

    fetch(_previewUrl, { credentials: 'same-origin' })
        .then(function(res) {
            if (!res.ok) throw new Error('Erro ' + res.status);
            return res.blob();
        })
        .then(function(blob) {
            _previewBlobUrl = URL.createObjectURL(blob);
            container.innerHTML = '';
            var iframe = document.createElement('iframe');
            iframe.src = _previewBlobUrl;
            iframe.style.cssText = 'flex:1;width:100%;height:100%;border:none;';
            container.appendChild(iframe);
        })
        .catch(function(err) {
            container.innerHTML =
                '<div style="text-align:center;padding:32px;">' +
                '<p style="font-size:13px;color:#EF4444;margin-bottom:12px;">Não foi possível carregar a pré-visualização.</p>' +
                '<a href="' + _previewUrl + '" target="_blank" style="font-size:13px;color:#004B8D;text-decoration:underline;">Abrir em nova aba</a>' +
                '</div>';
        });
}

function fecharPreview() {
    document.getElementById('modal-pdf-preview').style.display = 'none';
}

document.getElementById('modal-pdf-preview').addEventListener('click', function(e) {
    if (e.target === this) fecharPreview();
});
</script>
@endif
@endsection