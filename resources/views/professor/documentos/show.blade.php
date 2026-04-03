@extends('layouts.app')
@section('title', strtoupper(str_replace('_', ' ', $documento->type)))

@section('content')
<div style="max-width: 1000px;">
    <div style="margin-bottom: 24px;">
        <a href="{{ route('professor.dashboard') }}"
           style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Voltar para o painel
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
                <a href="{{ route('professor.documentos.pdf', $documento) }}" target="_blank"
                   style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('professor.documentos.word', $documento) }}"
                style="display: flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #E5E7EB; color: #374151;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    Word
                </a>
                @if($documento->type === 'pei' && $documento->author_id === auth()->id())
                <a href="{{ route('professor.documentos.edit', $documento) }}"
                   style="background: #004B8D; color: white; text-decoration: none; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    Editar
                </a>
                <button type="button" onclick="abrirModalDelete()"
                        style="background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Excluir
                </button>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; font-size: 13px; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Campos de texto simples --}}
    @php
        $camposSimples = collect($documento->content)->filter(fn($v, $k) =>
            !in_array($k, ['habilidades_academicas', 'habilidades_socioemocionais', 'habilidades_funcionais'])
            && !empty($v)
        );
        $inventarios = [
            'habilidades_academicas'     => 'Habilidades Acadêmicas',
            'habilidades_socioemocionais' => 'Habilidades Socioemocionais',
            'habilidades_funcionais'     => 'Habilidades Funcionais',
        ];
    @endphp

    @if($camposSimples->isNotEmpty())
    <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden; margin-bottom: 16px;">
        @foreach($camposSimples as $campo => $valor)
            <div style="padding: 20px 24px; {{ !$loop->last ? 'border-bottom: 1px solid #F9FAFB;' : '' }}">
                <p style="font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px;">
                    {{ str_replace('_', ' ', $campo) }}
                </p>
                @if(is_array($valor))
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach($valor as $tag)
                            @if(is_string($tag))
                                <span style="padding: 5px 14px; border-radius: 20px; font-size: 13px; font-weight: 500; background: #E8F0F9; color: #004B8D;">{{ $tag }}</span>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 14px; color: #374151; margin: 0; line-height: 1.7; white-space: pre-line;">{{ $valor }}</p>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    {{-- Inventários de habilidades --}}
    @foreach($inventarios as $key => $titulo)
        @php $itens = $documento->content[$key] ?? []; @endphp
        @if(!empty($itens))
        <div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden; margin-bottom: 16px;">
            <div style="padding: 14px 20px; border-bottom: 1px solid #F3F4F6; background: #FAFAFA;">
                <p style="font-size: 11px; font-weight: 700; color: #004B8D; letter-spacing: 1px; text-transform: uppercase; margin: 0;">{{ $titulo }}</p>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                    <thead>
                        <tr style="background: #F9FAFB;">
                            <th style="text-align: left; padding: 10px 16px; font-size: 10px; font-weight: 600; color: #6B7280; width: 40%;">Meta / Objetivo</th>
                            <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: #6B7280;">Sem suporte</th>
                            <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: #6B7280;">Com apoio</th>
                            <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: #6B7280;">Não realiza</th>
                            <th style="text-align: center; padding: 10px 8px; font-size: 10px; font-weight: 600; color: #6B7280;">Não observado</th>
                            <th style="text-align: left; padding: 10px 16px; font-size: 10px; font-weight: 600; color: #6B7280;">Responsável</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itens as $i => $item)
                        <tr style="border-top: 1px solid #F3F4F6; {{ $i % 2 !== 0 ? 'background: #FAFAFA;' : '' }}">
                            <td style="padding: 10px 16px; color: #374151; font-size: 13px;">{{ $item['meta'] ?? '' }}</td>
                            @foreach(['realiza_sem_suporte','realiza_com_apoio','ainda_nao_realiza','nao_observado'] as $col)
                            <td style="text-align: center; padding: 10px 8px;">
                                @if(!empty($item[$col]))
                                    <span style="color: #004B8D; font-weight: 700; font-size: 14px;">✓</span>
                                @else
                                    <span style="color: #E5E7EB;">—</span>
                                @endif
                            </td>
                            @endforeach
                            <td style="padding: 10px 16px; font-size: 12px; color: #6B7280;">{{ $item['responsavel'] ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endforeach

    <div style="margin-top: 16px;">
        <p style="font-size: 12px; color: #9CA3AF; margin: 0;">
            Criado por {{ $documento->author->name }} · {{ $documento->created_at->format('d/m/Y') }}
        </p>
    </div>
</div>

@if($documento->type === 'pei' && $documento->author_id === auth()->id())
{{-- Modal de exclusão --}}
<div id="modal-delete"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:14px; box-shadow:0 20px 60px rgba(0,0,0,0.18); padding:28px 32px; width:100%; max-width:400px; margin:0 16px;">

        {{-- Ícone + título --}}
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:16px;">
            <div style="width:44px; height:44px; border-radius:50%; background:#FEF2F2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <div>
                <p style="font-size: 15px; font-weight: 700; color: #111827; margin: 0 0 2px;">Excluir PEI</p>
                <p style="font-size: 13px; color: #6B7280; margin: 0;">Esta ação não pode ser desfeita.</p>
            </div>
        </div>

        <p style="font-size: 13px; color: #374151; background: #F9FAFB; border-radius: 8px; padding: 12px 14px; margin: 0 0 24px; line-height: 1.6;">
            O PEI de <strong>{{ $documento->student->name }}</strong> referente ao ano letivo <strong>{{ $documento->year }}</strong> será excluído permanentemente.
        </p>

        <form id="form-delete-pei" method="POST" action="{{ route('professor.documentos.destroy', $documento) }}">
            @csrf @method('DELETE')
        </form>

        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button type="button" onclick="fecharModalDelete()"
                    style="padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #374151; background: #F3F4F6; border: 1px solid #E5E7EB; cursor: pointer;">
                Cancelar
            </button>
            <button type="submit" form="form-delete-pei"
                    style="padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff; background: #DC2626; border: none; cursor: pointer;">
                Sim, excluir
            </button>
        </div>
    </div>
</div>

<script>
function abrirModalDelete() {
    document.getElementById('modal-delete').style.display = 'flex';
}
function fecharModalDelete() {
    document.getElementById('modal-delete').style.display = 'none';
}
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) fecharModalDelete();
});
</script>
@endif

@endsection