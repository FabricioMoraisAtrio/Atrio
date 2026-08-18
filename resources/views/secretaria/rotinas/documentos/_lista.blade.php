{{--
    Partial compartilhado para listagem de documentos por tipo.
    Variáveis esperadas: $alunos, $turmas, $cfg, $rotaNome, $corPrincipal, $bgPrincipal
--}}

<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.rotinas.documentos.index') }}"
       style="font-size: 13px; color: var(--text-4); text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Documentos
    </a>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--text-1); margin: 0 0 4px;">{{ $cfg['titulo'] }}</h1>
            <p style="font-size: 13px; color: var(--text-4); margin: 0;">
                {{ $alunos->count() }} estudante(s) listado(s)
                @if($cfg['so_publico']) · apenas {{ term('publico_alvo') }} @endif
            </p>
        </div>
        @php
            $comDoc = $alunos->filter(fn($a) => $a->documents->isNotEmpty())->count();
            $semDoc = $alunos->count() - $comDoc;
        @endphp
        <div style="display: flex; gap: 10px;">
            <div style="background: var(--success-bg); border-radius: 8px; padding: 8px 16px; text-align: center;">
                <p style="font-size: 18px; font-weight: 700; color: var(--success); margin: 0;">{{ $comDoc }}</p>
                <p style="font-size: 11px; color: var(--success); margin: 0;">Com documento</p>
            </div>
            <div style="background: var(--danger-bg); border-radius: 8px; padding: 8px 16px; text-align: center;">
                <p style="font-size: 18px; font-weight: 700; color: var(--danger); margin: 0;">{{ $semDoc }}</p>
                <p style="font-size: 11px; color: var(--danger); margin: 0;">Sem documento</p>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route($rotaNome) }}"
      style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); padding: 16px 20px; margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">

    <div style="flex: 1; min-width: 180px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Turma</label>
        <select name="turma" style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-2); outline: none; background: var(--bg-card);">
            <option value="">Todas as turmas</option>
            @foreach($turmas as $turma)
                <option value="{{ $turma->id }}" {{ request('turma') == $turma->id ? 'selected' : '' }}>
                    {{ $turma->name }} — {{ $turma->shift }}
                </option>
            @endforeach
        </select>
    </div>

    @if(!$cfg['so_publico'])
    <div style="min-width: 160px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-4); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">{{ term('publico_alvo') }}</label>
        <select name="publico" style="width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--text-2); outline: none; background: var(--bg-card);">
            <option value="">Todos</option>
            <option value="sim" {{ request('publico') === 'sim' ? 'selected' : '' }}>Sim</option>
            <option value="nao" {{ request('publico') === 'nao' ? 'selected' : '' }}>Não</option>
        </select>
    </div>
    @endif

    <div style="display: flex; gap: 8px;">
        <button type="submit"
                style="background: var(--accent); color: white; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Filtrar
        </button>
        @if(request()->hasAny(['turma', 'publico']))
            <a href="{{ route($rotaNome) }}"
               style="padding: 9px 14px; border-radius: 8px; font-size: 13px; color: var(--text-3); text-decoration: none; border: 1px solid var(--border);">
                Limpar
            </a>
        @endif
    </div>
</form>

{{-- Tabela --}}
<div style="background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-sub); overflow: hidden;">
    @if($alunos->isEmpty())
        <div style="padding: 48px; text-align: center;">
            <p style="font-size: 14px; color: var(--text-4);">Nenhum estudante encontrado com os filtros selecionados.</p>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background: var(--bg-subtle); border-bottom: 1px solid var(--border-sub);">
                    <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Estudante</th>
                    <th style="text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Turma</th>
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">{{ $cfg['titulo'] }}</th>
                    <th style="text-align: right; padding: 12px 20px; font-size: 11px; font-weight: 600; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.5px;">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alunos as $aluno)
                    @php $turma = $aluno->schoolClasses->first(); @endphp

                    @if($cfg['multiplos'])

                        {{-- ── MODO MÚLTIPLOS DOCUMENTOS (PEI / Atendimentos) ── --}}
                        <tr style="border-bottom: 1px solid var(--border-sub); background: var(--bg-subtle);">
                            <td style="padding: 12px 20px;" colspan="4">
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $bgPrincipal }}; color: {{ $corPrincipal }}; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-size: 13px; font-weight: 700; color: var(--text-1); margin: 0;">{{ $aluno->name }}</p>
                                        @if($aluno->registration_number)
                                            <p style="font-size: 11px; color: var(--text-4); margin: 0;">Mat. {{ $aluno->registration_number }}</p>
                                        @endif
                                    </div>
                                    @if($turma)
                                        <span style="font-size: 11px; color: var(--text-3); padding: 2px 8px; background: var(--bg-subtle); border-radius: 20px;">{{ $turma->name }} · {{ $turma->shift }}</span>
                                    @endif
                                    @if($aluno->is_atypical)
                                        @if($aluno->is_publico_alvo)
                                            <span style="background: var(--purple-bg); color: var(--purple); font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
                                        @else
                                            <span style="background: var(--warning-bg); color: var(--warning); font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;">Atípico</span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px 20px; text-align: right;">
                                @if($cfg['tipo'] === 'pei')
                                    {{-- PEI: botão para o consolidado, não para criar novo --}}
                                    <a href="{{ route('secretaria.alunos.pei.edit', $aluno) }}"
                                       style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #fff; text-decoration: none; padding: 6px 14px; border-radius: 8px; background: {{ $corPrincipal }};">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/></svg>
                                        PEI Consolidado
                                    </a>
                                @else
                                    <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => $cfg['tipo']]) }}"
                                       style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: #fff; text-decoration: none; padding: 5px 12px; border-radius: 8px; background: {{ $corPrincipal }};">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                        Novo
                                    </a>
                                @endif
                            </td>
                        </tr>

                        {{-- Documentos existentes --}}
                        @foreach($aluno->documents as $doc)
                        <tr style="border-bottom: 1px solid var(--border-sub);" onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 10px 20px 10px 56px; color: var(--text-2); font-size: 12px;" colspan="4">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="{{ $corPrincipal }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                                    @if($doc->author)
                                        <span style="font-weight: 600; color: var(--text-2);">{{ $doc->author->name }}</span>
                                    @endif
                                    <span style="font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                                        {{ $doc->status === 'published' ? 'background: #ECFDF5; color: var(--success);' : 'background: #FEF3C7; color: var(--warning);' }}">
                                        {{ $doc->status === 'published' ? 'Publicado' : 'Rascunho' }}
                                    </span>
                                    <span style="font-size: 11px; color: var(--text-4);">{{ $doc->updated_at->format('d/m/Y') }}</span>
                                </div>
                            </td>
                            <td style="padding: 10px 20px; text-align: right;">
                                @if($cfg['tipo'] === 'pei')
                                    {{-- PEI individual: somente leitura, sem ações --}}
                                @else
                                    @include('secretaria.rotinas.documentos._visualizar_btn', ['doc' => $doc, 'corPrincipal' => $corPrincipal, 'bgPrincipal' => $bgPrincipal])
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        @if($aluno->documents->isEmpty())
                        <tr style="border-bottom: 1px solid var(--border-sub);">
                            <td colspan="5" style="padding: 8px 20px 8px 56px;">
                                <span style="font-size: 11px; color: var(--text-4); font-style: italic;">Nenhum documento registrado</span>
                            </td>
                        </tr>
                        @endif

                    @else

                        {{-- ── MODO ÚNICO DOCUMENTO POR ALUNO ── --}}
                        @php $doc = $aluno->documents->first(); @endphp
                        <tr style="border-bottom: 1px solid var(--border-sub);" onmouseover="this.style.background='var(--bg-subtle)'" onmouseout="this.style.background='transparent'">

                            <td style="padding: 14px 20px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgPrincipal }}; color: {{ $corPrincipal }}; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-size: 13px; font-weight: 600; color: var(--text-1); margin: 0;">{{ $aluno->name }}</p>
                                        @if($aluno->registration_number)
                                            <p style="font-size: 11px; color: var(--text-4); margin: 0;">Mat. {{ $aluno->registration_number }}</p>
                                        @endif
                                        @php
                                            $siglas = collect(config('transtornos'))->filter(fn($v, $k) => $aluno->$k)->map(fn($v) => $v[0]);
                                        @endphp
                                        @if($siglas->isNotEmpty())
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                                            @foreach($siglas as $sigla)
                                                <span style="font-size: 10px; font-weight: 600; padding: 1px 6px; border-radius: 20px; background: var(--bg-subtle); color: var(--text-2);">{{ $sigla }}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td style="padding: 14px 16px;">
                                @if($turma)
                                    <p style="font-size: 13px; font-weight: 500; color: var(--text-2); margin: 0;">{{ $turma->name }}</p>
                                    <p style="font-size: 11px; color: var(--text-4); margin: 0;">{{ $turma->shift }}</p>
                                @else
                                    <span style="font-size: 12px; color: var(--text-4);">Sem turma</span>
                                @endif
                            </td>

                            <td style="padding: 14px 16px; text-align: center;">
                                @if($aluno->is_atypical)
                                    @if($aluno->is_publico_alvo)
                                        <span style="background: var(--purple-bg); color: var(--purple); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
                                    @else
                                        <span style="background: var(--warning-bg); color: var(--warning); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Atípico</span>
                                    @endif
                                @else
                                    <span style="background: var(--bg-subtle); color: var(--text-3); font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
                                @endif
                            </td>

                            <td style="padding: 14px 16px; text-align: center;">
                                @if($cfg['tipo'] === 'pei')
                                    @php $qtdProfessores = $aluno->documents->where('type', 'pei')->count(); @endphp
                                    @if($qtdProfessores > 0)
                                        <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: var(--success-bg); color: var(--success);">
                                            {{ $qtdProfessores }} {{ $qtdProfessores === 1 ? 'professor' : 'professores' }}
                                        </span>
                                    @else
                                        <span style="font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; background: var(--danger-bg); color: var(--danger);">
                                            Pendente
                                        </span>
                                    @endif
                                @elseif($doc)
                                    <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: var(--success-bg); color: var(--success);">
                                        Preenchido
                                    </span>
                                    <p style="font-size: 10px; color: var(--text-4); margin: 4px 0 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                                @else
                                    <span style="font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; background: var(--danger-bg); color: var(--danger);">
                                        Pendente
                                    </span>
                                @endif
                            </td>

                            <td style="padding: 14px 20px; text-align: right;">
                                @if($cfg['tipo'] === 'pei')
                                    @php $peiConsolidado = $aluno->documents->first(fn($d) => $d->type === 'pei_consolidado'); @endphp
                                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                                        <a href="{{ route('secretaria.alunos.pei.edit', $aluno) }}"
                                           style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: {{ $corPrincipal }}; text-decoration: none; padding: 6px 14px; border-radius: 8px; border: 1px solid {{ $corPrincipal }};">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Acessar
                                        </a>
                                        @if($peiConsolidado)
                                        <a href="{{ route('secretaria.documentos.pdf', $peiConsolidado) }}"
                                           style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: var(--text-3); text-decoration: none; padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border);">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                                            PDF
                                        </a>
                                        @endif
                                    </div>
                                @elseif($doc)
                                    @include('secretaria.rotinas.documentos._visualizar_btn', ['doc' => $doc, 'corPrincipal' => $corPrincipal, 'bgPrincipal' => $bgPrincipal])
                                @elseif($cfg['tipo'] === 'paee' && ! $aluno->has_case_study)
                                    <span title="Preencha o Estudo de Caso antes de criar o PAEE"
                                          style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: var(--text-4); padding: 6px 14px; border-radius: 8px; background: var(--bg-subtle); border: 1px solid var(--border); cursor: not-allowed;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                        Estudo de Caso pendente
                                    </span>
                                @else
                                    <a href="{{ route('secretaria.alunos.documentos.create', [$aluno, 'type' => $cfg['tipo']]) }}"
                                       style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #fff; text-decoration: none; padding: 6px 14px; border-radius: 8px; background: {{ $corPrincipal }};">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                                        Incluir
                                    </a>
                                @endif
                            </td>
                        </tr>

                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>
