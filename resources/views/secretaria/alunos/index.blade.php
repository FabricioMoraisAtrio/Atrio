@extends('layouts.app')
@section('title', term('alunos'))

@section('content')

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
    <div>
        <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ term('alunos') }}</h1>
        <p style="font-size: 13px; color: #9CA3AF; margin: 0;">
        {{ \App\Models\Student::count() }} {{ strtolower(term('alunos')) }} cadastrados
        @if(request('turma') || request('perfil'))
            — <span style="color: #004B8D; font-weight: 600;">filtro ativo: {{ count(array_filter([request('turma'), request('perfil')])) }} critério(s)</span>
        @endif
    </p>
    </div>
    <a href="{{ route('secretaria.alunos.create') }}"
       style="background: #004B8D; color: white; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
        Novo {{ strtolower(term('aluno')) }}
    </a>
</div>

{{-- A mensagem de sucesso já é exibida globalmente pelo layout --}}

{{-- Filtros --}}
<form method="GET" action="{{ route('secretaria.alunos.index') }}"
      style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 16px 20px; margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">

    <div style="flex: 1; min-width: 160px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Perfil / Condição</label>
        <select name="perfil"
                style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #374151; outline: none; background: #fff;">
            <option value="">Todos os perfis</option>
            <optgroup label="Perfil geral">
                <option value="atipico"      {{ request('perfil') === 'atipico'      ? 'selected' : '' }}>Atípico (qualquer condição)</option>
                <option value="publico_alvo" {{ request('perfil') === 'publico_alvo' ? 'selected' : '' }}>{{ term('publico_alvo') }} (habilitam PEI)</option>
                <option value="tipico"       {{ request('perfil') === 'tipico'       ? 'selected' : '' }}>{{ term('nao_publico_alvo') }}</option>
            </optgroup>
            <optgroup label="Público Alvo — condição específica">
                <option value="tea"         {{ request('perfil') === 'tea'         ? 'selected' : '' }}>TEA (Autismo)</option>
                <option value="tgd"         {{ request('perfil') === 'tgd'         ? 'selected' : '' }}>TGD</option>
                <option value="down"        {{ request('perfil') === 'down'        ? 'selected' : '' }}>Síndrome de Down</option>
                <option value="intelectual" {{ request('perfil') === 'intelectual' ? 'selected' : '' }}>D. Intelectual</option>
                <option value="visual"      {{ request('perfil') === 'visual'      ? 'selected' : '' }}>D. Visual</option>
                <option value="auditiva"    {{ request('perfil') === 'auditiva'    ? 'selected' : '' }}>D. Auditiva</option>
            </optgroup>
            <optgroup label="Outras condições">
                <option value="tdah"        {{ request('perfil') === 'tdah'        ? 'selected' : '' }}>TDAH</option>
            </optgroup>
        </select>
    </div>

    <div style="flex: 1; min-width: 180px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Turma</label>
        <select name="turma"
                style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #374151; outline: none; background: #fff;">
            <option value="">Todas as turmas</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}" {{ request('turma') == $turma->id ? 'selected' : '' }}>
                    {{ $turma->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; gap: 8px;">
        <button type="submit"
                style="background: #004B8D; color: white; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Filtrar
        </button>
        @if(request('turma') || request('perfil'))
            <a href="{{ route('secretaria.alunos.index') }}"
               style="padding: 9px 14px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                Limpar
            </a>
        @endif
    </div>
</form>

{{-- Busca local --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid #F9FAFB; display: flex; align-items: center; gap: 12px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
        </svg>
        <input type="text" id="search" placeholder="Buscar por nome ou matrícula..."
               oninput="filterTable(this.value)"
               style="border: none; outline: none; font-size: 13px; color: #374151; width: 100%; background: transparent;">
    </div>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F9FAFB;">
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">{{ term('aluno') }}</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Matrícula</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">{{ term('turma') }}</th>
                <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                <th style="text-align: center; padding: 12px 10px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Est. Caso</th>
                <th style="text-align: center; padding: 12px 10px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">PAEE</th>
                <th style="text-align: center; padding: 12px 10px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">PEI</th>
                <th style="text-align: center; padding: 12px 10px; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px;">Laudo</th>
                <th style="padding: 12px 20px;"></th>
            </tr>
        </thead>
        <tbody id="tableBody">
            @forelse($alunos as $aluno)
            <tr style="border-top: 1px solid #F9FAFB;" class="table-row"
                onmouseover="this.style.background=document.documentElement.getAttribute('data-theme')==='dark'?'rgba(77,159,255,0.06)':'#FAFAFA'"
                onmouseout="this.style.background='transparent'">
                <td style="padding: 14px 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        @if($aluno->photo)
                            <img src="{{ route('alunos.foto', $aluno) }}" alt="{{ $aluno->name }}"
                                 style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 1px solid #E5E7EB; flex-shrink: 0;">
                        @else
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #E8F0F9; color: #004B8D; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ strtoupper(substr($aluno->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-size: 14px; font-weight: 500; color: #111827;" class="aluno-name">{{ $aluno->name }}</div>
                            @if($aluno->is_atypical)
                                <div style="display: flex; flex-wrap: wrap; gap: 3px; margin-top: 4px;">
                                    @foreach(config('transtornos') as $field => [$sigla, $nome])
                                        @if($aluno->$field)
                                            <span style="background: #F5EDE6; color: #7C3700; font-size: 10px; font-weight: 600; padding: 1px 6px; border-radius: 20px;" title="{{ $nome }}">{{ $sigla }}@if($field === 'cid_autismo' && $aluno->tea_nivel_suporte) N{{ $aluno->tea_nivel_suporte }}@endif</span>
                                        @endif
                                    @endforeach
                                    @if($aluno->condition)
                                        <span style="background: #F3F4F6; color: #6B7280; font-size: 10px; padding: 1px 6px; border-radius: 20px;">{{ $aluno->condition }}</span>
                                    @endif
                                </div>
                            @else
                                <div style="font-size: 12px; color: #9CA3AF;">{{ $aluno->birth_date->format('d/m/Y') }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="padding: 14px 20px; font-size: 13px; color: #6B7280;" class="aluno-reg">{{ $aluno->registration_number }}</td>
                <td style="padding: 14px 20px; font-size: 13px; color: #6B7280;">
                    {{ $aluno->schoolClasses->pluck('name')->join(', ') ?: '—' }}
                </td>
                <td style="padding: 14px 20px;">
                    @if($aluno->is_atypical)
                        @if($aluno->is_publico_alvo)
                            <span style="background: #F3E8FF; color: #7E22CE; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; display: inline-block;">{{ term('publico_alvo') }}</span>
                        @else
                            <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; display: inline-block;">Atípico</span>
                        @endif
                    @else
                        <span style="background: #F3F4F6; color: #6B7280; font-size: 11px; padding: 3px 8px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
                    @endif
                </td>
                @php
                    $docs     = $aluno->documents;
                    $temEC    = $docs->firstWhere('type', 'estudo_caso');
                    $temPAEE  = $docs->firstWhere('type', 'paee');
                    $temPEI   = $docs->whereIn('type', ['pei','pei_consolidado'])->first();
                    $temLaudo = $aluno->laudos->isNotEmpty();
                @endphp
                @foreach([
                    ['ok' => $temEC,    'label' => 'EC'],
                    ['ok' => $temPAEE,  'label' => 'PAEE'],
                    ['ok' => $temPEI,   'label' => 'PEI'],
                    ['ok' => $temLaudo, 'label' => 'Laudo'],
                ] as $col)
                <td style="padding: 14px 10px; text-align: center;">
                    @if($col['ok'])
                        <span style="background: #ECFDF5; color: #065F46; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 20px; white-space: nowrap;">✓ Preenchido</span>
                    @else
                        <span style="background: #F3F4F6; color: #9CA3AF; font-size: 11px; padding: 3px 8px; border-radius: 20px; white-space: nowrap;">Pendente</span>
                    @endif
                </td>
                @endforeach
                <td style="padding: 14px 20px; text-align: right;">
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('secretaria.alunos.show', $aluno) }}?back={{ urlencode(url()->current()) }}"
                           style="font-size: 13px; color: #004B8D; text-decoration: none; font-weight: 500;">Ver</a>
                        <a href="{{ route('secretaria.alunos.edit', $aluno) }}"
                           style="font-size: 13px; color: #6B7280; text-decoration: none;">Editar</a>
                        <form method="POST" action="{{ route('secretaria.alunos.destroy', $aluno) }}" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm="Remover {{ strtolower(term('aluno')) }}?"
                                    style="font-size: 13px; color: #EF4444; background: none; border: none; cursor: pointer; padding: 0;">
                                Remover
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="padding: 48px; text-align: center; color: #9CA3AF; font-size: 14px;">
                    Nenhum {{ strtolower(term('aluno')) }} cadastrado.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function filterTable(query) {
    query = query.toLowerCase();
    document.querySelectorAll('#tableBody .table-row').forEach(row => {
        const name = row.querySelector('.aluno-name')?.textContent.toLowerCase() || '';
        const reg  = row.querySelector('.aluno-reg')?.textContent.toLowerCase() || '';
        row.style.display = name.includes(query) || reg.includes(query) ? '' : 'none';
    });
}
</script>
@endsection