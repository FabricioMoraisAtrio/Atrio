@extends('marketing.layout')

@section('metaTitle', $m['title'] . ' — Átrio')
@section('metaDescription', $m['tagline'])

@section('content')

{{-- ── HERO DO MÓDULO ── --}}
<section class="legislacao-section">
    <div class="section-inner">
        <p style="font-size:12px; color:var(--muted); margin-bottom:28px;">
            <a href="{{ route('plataforma') }}" style="color:var(--muted); text-decoration:none;">A Plataforma</a>
            &nbsp;›&nbsp; {{ $m['nav'] }}
        </p>

        <div class="leg-intro" style="margin-bottom:0;">
            <div class="leg-intro-text">
                <div class="section-tag">Módulo</div>
                <h1 class="section-title">{{ $m['title'] }}</h1>
                <p class="section-lead" style="margin-bottom:28px;">{{ $m['tagline'] }} {{ $m['desc'] }}</p>
                <div style="display:flex; gap:14px; flex-wrap:wrap;">
                    <a href="{{ route('contato') }}" class="btn btn-primary btn-lg">Agendar demonstração</a>
                    <a href="{{ route('plataforma') }}" class="btn btn-outline btn-lg">Ver todos os módulos</a>
                </div>
            </div>
            <div class="leg-intro-visual">
                <div style="width:76px; height:76px; border-radius:18px; background:var(--blue-soft); display:flex; align-items:center; justify-content:center; margin-bottom:6px;">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#004B8D" stroke-width="1.6">{!! $m['icon'] !!}</svg>
                </div>
                <div class="leg-pill">
                    <div class="leg-pill-badge">Base legal</div>
                    <div class="leg-pill-text">{{ $m['law'] }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── O QUE REÚNE ── --}}
<section>
    <div class="section-inner">
        <div class="section-tag">O que reúne</div>
        <h2 class="section-title">Dentro do módulo</h2>
        <p class="section-lead">O que você registra e acompanha em {{ $m['nav'] }} dentro do Átrio.</p>
        <ul class="leg-card-items" style="max-width:640px; gap:14px;">
            @foreach ($m['items'] as $item)
                <li style="font-size:14px;">{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ── OUTROS MÓDULOS ── --}}
<section class="docs-showcase">
    <div class="section-inner">
        <div class="section-tag">A Plataforma</div>
        <h2 class="section-title">Outros módulos</h2>
        <p class="section-lead">O Átrio conecta avaliação, planejamento, acompanhamento e conformidade — o processo inteiro, por estudante.</p>
        <div class="docs-grid">
            @foreach (\App\Support\Modulos::all() as $slug => $outro)
                @continue($slug === $m['slug'])
                <a href="{{ route('modulo', $slug) }}" class="doc-pill">
                    <div class="doc-dot" style="background: {{ $outro['dot'] }};"></div>
                    <div class="doc-info">
                        <div class="doc-name">{{ $outro['nav'] }}</div>
                        <div class="doc-desc">{{ $outro['tagline'] }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="cta-section">
    <div class="section-inner">
        <h2 class="section-title">Quer ver {{ $m['nav'] }} funcionando?</h2>
        <p class="section-lead" style="margin: 0 auto 40px;">
            Agende uma demonstração gratuita e veja o módulo com o cenário da sua escola ou rede.
        </p>
        <a href="{{ route('contato') }}" class="btn btn-white btn-lg">Agendar demonstração</a>
    </div>
</section>

@endsection
