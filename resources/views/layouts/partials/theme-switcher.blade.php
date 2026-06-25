<div style="position: relative;">
    <button id="theme-toggle" onclick="toggleThemeMenu(event)" title="Tema" aria-haspopup="true" aria-expanded="false">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="4"/>
            <path d="M12 2v2M12 20v2M2 12h2M20 12h2M5 5l1.5 1.5M17.5 17.5L19 19M19 5l-1.5 1.5M6.5 17.5L5 19"/>
        </svg>
    </button>
    <div id="theme-menu" role="menu" style="display:none;position:absolute;right:0;top:44px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,.18);padding:6px;min-width:184px;z-index:60;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--text-4);padding:6px 10px 8px;">Tema</div>
        @foreach(['light'=>'Claro','dark'=>'Escuro','slate'=>'Escuro suave','contrast'=>'Alto contraste'] as $val => $label)
        <button type="button" class="theme-opt" data-theme-val="{{ $val }}" onclick="setTheme('{{ $val }}')">
            <span class="theme-swatch" data-sw="{{ $val }}"></span>
            <span style="flex:1;">{{ $label }}</span>
            <svg class="theme-check" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--accent-text)" stroke-width="3" style="display:none;"><path d="M20 6L9 17l-5-5"/></svg>
        </button>
        @endforeach
    </div>
</div>
