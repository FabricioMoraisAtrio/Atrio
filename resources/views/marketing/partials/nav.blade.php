{{-- ── NAVEGAÇÃO (autoral: 1 dropdown "O sistema" + itens diretos) ── --}}
<nav class="nav">
    <a href="{{ route('home') }}" class="nav-logo">
        <div class="nav-logo-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7v10l9 5 9-5V7L12 2z" fill="white" opacity="0.85"/>
                <path d="M12 2L3 7l9 5 9-5-9-5z" fill="white"/>
            </svg>
        </div>
        <span class="nav-logo-text">Átrio</span>
    </a>

    <button type="button" class="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>

    <div class="nav-menu">
        <ul class="nav-links">

            {{-- O sistema (único dropdown: card do produto + índice de módulos) --}}
            <li class="has-mega">
                <button type="button" class="nav-trigger">O sistema
                    <svg class="chev" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="mega mega-feature">
                    <div class="mega-feature-grid">

                        <a href="{{ route('plataforma') }}" class="mega-feature-card">
                            <span class="mff-badge">O sistema por dentro</span>
                            <span class="mff-title">Veja o Átrio funcionando</span>
                            <span class="mff-sub">Não é promessa de tela — é o produto real, do Estudo de Caso ao PEI.</span>
                            <span class="mff-flow"><span>Estudo de Caso</span><i>→</i><span>PEI</span><i>→</i><span>Linha do Tempo</span></span>
                            <span class="mff-cta">Ver o sistema
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </span>
                        </a>

                        <div class="mega-mods">
                            <div class="mega-mods-label">Módulos</div>
                            <div class="mega-mods-grid">
                                @foreach (\App\Support\Modulos::all() as $slug => $mod)
                                    <a href="{{ route('modulo', $slug) }}" class="mega-mod">
                                        <span class="mega-ico"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="1.8">{!! $mod['icon'] !!}</svg></span>
                                        <span class="mega-mod-t">{{ $mod['nav'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </li>

            <li><a href="{{ route('legislacao') }}">Conformidade</a></li>
            <li><a href="{{ route('planos') }}">Planos</a></li>
            <li><a href="{{ route('duvidas') }}">Dúvidas</a></li>
            <li><a href="{{ route('contato') }}">Contato</a></li>
        </ul>

        <div class="nav-cta">
            <a class="nav-enter" href="{{ route('login') }}?perfil=escola">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Entrar
            </a>
            <a class="btn btn-primary" href="{{ route('contato') }}">Agendar demonstração
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</nav>
