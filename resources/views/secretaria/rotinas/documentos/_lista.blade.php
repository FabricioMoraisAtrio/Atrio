{{--
    Partial compartilhado para listagem de documentos por tipo.
    Variáveis esperadas: $alunos, $turmas, $cfg, $rotaNome, $corPrincipal, $bgPrincipal
--}}

<div style="margin-bottom: 24px;">
    <a href="{{ route('secretaria.rotinas.documentos.index') }}"
       style="font-size: 13px; color: #9CA3AF; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 12px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Documentos
    </a>
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px;">{{ $cfg['titulo'] }}</h1>
            <p style="font-size: 13px; color: #9CA3AF; margin: 0;">
                {{ $alunos->count() }} aluno(s) listado(s)
                @if($cfg['so_publico']) · apenas {{ term('publico_alvo') }} @endif
            </p>
        </div>
        @php
            $comDoc = $alunos->filter(fn($a) => $a->documents->isNotEmpty())->count();
            $semDoc = $alunos->count() - $comDoc;
        @endphp
        <div style="display: flex; gap: 10px;">
            <div style="background: #ECFDF5; border-radius: 8px; padding: 8px 16px; text-align: center;">
                <p style="font-size: 18px; font-weight: 700; color: #065F46; margin: 0;">{{ $comDoc }}</p>
                <p style="font-size: 11px; color: #065F46; margin: 0;">Com documento</p>
            </div>
            <div style="background: #FEF2F2; border-radius: 8px; padding: 8px 16px; text-align: center;">
                <p style="font-size: 18px; font-weight: 700; color: #991B1B; margin: 0;">{{ $semDoc }}</p>
                <p style="font-size: 11px; color: #991B1B; margin: 0;">Sem documento</p>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route($rotaNome) }}"
      style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; padding: 16px 20px; margin-bottom: 16px; display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;">

    <div style="flex: 1; min-width: 180px;">
        <label style="display: block; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Turma</label>
        <select name="turma" style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #374151; outline: none; background: #fff;">
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
        <label style="display: block; font-size: 11px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">{{ term('publico_alvo') }}</label>
        <select name="publico" style="width: 100%; border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #374151; outline: none; background: #fff;">
            <option value="">Todos</option>
            <option value="sim" {{ request('publico') === 'sim' ? 'selected' : '' }}>Sim</option>
            <option value="nao" {{ request('publico') === 'nao' ? 'selected' : '' }}>Não</option>
        </select>
    </div>
    @endif

    <div style="display: flex; gap: 8px;">
        <button type="submit"
                style="background: #004B8D; color: white; border: none; padding: 9px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
            Filtrar
        </button>
        @if(request()->hasAny(['turma', 'publico']))
            <a href="{{ route($rotaNome) }}"
               style="padding: 9px 14px; border-radius: 8px; font-size: 13px; color: #6B7280; text-decoration: none; border: 1px solid #E5E7EB;">
                Limpar
            </a>
        @endif
    </div>
</form>

{{-- Tabela --}}
<div style="background: #fff; border-radius: 12px; border: 1px solid #F3F4F6; overflow: hidden;">
    @if($alunos->isEmpty())
        <div style="padding: 48px; text-align: center;">
            <p style="font-size: 14px; color: #9CA3AF;">Nenhum aluno encontrado com os filtros selecionados.</p>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background: #F9FAFB; border-bottom: 1px solid #F3F4F6;">
                    <th style="text-align: left; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Aluno</th>
                    <th style="text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Turma</th>
                    @if(!$cfg['so_publico'])
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Perfil</th>
                    @endif
                    <th style="text-align: center; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">{{ $cfg['titulo'] }}</th>
                    <th style="text-align: right; padding: 12px 20px; font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px;">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alunos as $aluno)
                    @php $turma = $aluno->schoolClasses->first(); @endphp

                    @if($cfg['multiplos'])

                        {{-- ── MODO MÚLTIPLOS DOCUMENTOS (PEI / Atendimentos) ── --}}
                        <tr style="border-bottom: 1px solid #F3F4F6; background: #FAFAFA;">
                            <td style="padding: 12px 20px;" colspan="{{ $cfg['so_publico'] ? 3 : 4 }}">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $bgPrincipal }}; color: {{ $corPrincipal }}; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 0;">{{ $aluno->name }}</p>
                                        @if($aluno->registration_number)
                                            <p style="font-size: 11px; color: #9CA3AF; margin: 0;">Mat. {{ $aluno->registration_number }}</p>
                                        @endif
                                    </div>
                                    @if($turma)
                                        <span style="font-size: 11px; color: #6B7280; padding: 2px 8px; background: #F3F4F6; border-radius: 20px;">{{ $turma->name }} · {{ $turma->shift }}</span>
                                    @endif
                                    @if(!$cfg['so_publico'] && $aluno->is_atypical)
                                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px 20px; text-align: right;">
                                @if($cfg['tipo'] === 'pei')
                                    {{-- PEI: botão para o consolidado, não para criar novo --}}
                                    <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
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
                        <tr style="border-bottom: 1px solid #F9FAFB;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 10px 20px 10px 56px; color: #374151; font-size: 12px;" colspan="{{ $cfg['so_publico'] ? 3 : 4 }}">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="{{ $corPrincipal }}" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                                    @if($doc->author)
                                        <span style="font-weight: 600; color: #374151;">{{ $doc->author->name }}</span>
                                    @endif
                                    <span style="font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px;
                                        {{ $doc->status === 'published' ? 'background: #ECFDF5; color: #065F46;' : 'background: #FEF3C7; color: #92400E;' }}">
                                        {{ $doc->status === 'published' ? 'Publicado' : 'Rascunho' }}
                                    </span>
                                    <span style="font-size: 11px; color: #9CA3AF;">{{ $doc->updated_at->format('d/m/Y') }}</span>
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
                        <tr style="border-bottom: 1px solid #F9FAFB;">
                            <td colspan="{{ $cfg['so_publico'] ? 4 : 5 }}" style="padding: 8px 20px 8px 56px;">
                                <span style="font-size: 11px; color: #9CA3AF; font-style: italic;">Nenhum documento registrado</span>
                            </td>
                        </tr>
                        @endif

                    @else

                        {{-- ── MODO ÚNICO DOCUMENTO POR ALUNO ── --}}
                        @php $doc = $aluno->documents->first(); @endphp
                        <tr style="border-bottom: 1px solid #F9FAFB;" onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">

                            <td style="padding: 14px 20px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $bgPrincipal }}; color: {{ $corPrincipal }}; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        {{ strtoupper(substr($aluno->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p style="font-size: 13px; font-weight: 600; color: #111827; margin: 0;">{{ $aluno->name }}</p>
                                        @if($aluno->registration_number)
                                            <p style="font-size: 11px; color: #9CA3AF; margin: 0;">Mat. {{ $aluno->registration_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td style="padding: 14px 16px;">
                                @if($turma)
                                    <p style="font-size: 13px; font-weight: 500; color: #374151; margin: 0;">{{ $turma->name }}</p>
                                    <p style="font-size: 11px; color: #9CA3AF; margin: 0;">{{ $turma->shift }}</p>
                                @else
                                    <span style="font-size: 12px; color: #D1D5DB;">Sem turma</span>
                                @endif
                            </td>

                            @if(!$cfg['so_publico'])
                            <td style="padding: 14px 16px; text-align: center;">
                                @if($aluno->is_atypical)
                                    @if($aluno->is_publico_alvo)
                                        <span style="background: #F3E8FF; color: #7E22CE; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">{{ term('publico_alvo') }}</span>
                                    @else
                                        <span style="background: #FEF3C7; color: #92400E; font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;">Atípico</span>
                                    @endif
                                @else
                                    <span style="background: #F3F4F6; color: #6B7280; font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px;">{{ term('nao_publico_alvo') }}</span>
                                @endif
                            </td>
                            @endif

                            <td style="padding: 14px 16px; text-align: center;">
                                @if($cfg['tipo'] === 'pei')
                                    @php $qtdProfessores = $aluno->documents->where('type', 'pei')->count(); @endphp
                                    @if($qtdProfessores > 0)
                                        <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #ECFDF5; color: #065F46;">
                                            {{ $qtdProfessores }} {{ $qtdProfessores === 1 ? 'professor' : 'professores' }}
                                        </span>
                                    @else
                                        <span style="font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; background: #FEF2F2; color: #991B1B;">
                                            Pendente
                                        </span>
                                    @endif
                                @elseif($doc)
                                    <span style="font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #ECFDF5; color: #065F46;">
                                        Preenchido
                                    </span>
                                    <p style="font-size: 10px; color: #9CA3AF; margin: 4px 0 0;">{{ $doc->updated_at->format('d/m/Y') }}</p>
                                @else
                                    <span style="font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; background: #FEF2F2; color: #991B1B;">
                                        Pendente
                                    </span>
                                @endif
                            </td>

                            <td style="padding: 14px 20px; text-align: right;">
                                @if($cfg['tipo'] === 'pei')
                                    @php $peiConsolidado = $aluno->documents->first(fn($d) => $d->type === 'pei_consolidado'); @endphp
                                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                                        <a href="{{ route('secretaria.alunos.pei-consolidado', $aluno) }}"
                                           style="display: inline-flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: {{ $corPrincipal }}; text-decoration: none; padding: 6px 14px; border-radius: 8px; border: 1px solid {{ $corPrincipal }};">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Acessar
                                        </a>
                                        @if($peiConsolidado)
                                        <a href="{{ route('secretaria.documentos.pdf', $peiConsolidado) }}"
                                           style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 600; color: #6B7280; text-decoration: none; padding: 6px 12px; border-radius: 8px; border: 1px solid #E5E7EB;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                                            PDF
                                        </a>
                                        @endif
                                    </div>
                                @elseif($doc)
                                    @include('secretaria.rotinas.documentos._visualizar_btn', ['doc' => $doc, 'corPrincipal' => $corPrincipal, 'bgPrincipal' => $bgPrincipal])
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
