<script>
// ══ TEMA ══ (claro/escuro/slate/contraste — os tokens cuidam de tudo,
// inclusive da cor da escola, injetada por CSS no servidor).
const THEME_VALID = ['light', 'dark', 'slate', 'contrast'];

function currentTheme() {
    return document.documentElement.getAttribute('data-theme') || 'light';
}

function markActiveTheme() {
    const t = currentTheme();
    document.querySelectorAll('.theme-opt').forEach(function (o) {
        o.classList.toggle('active', o.getAttribute('data-theme-val') === t);
    });
}

function toggleThemeMenu(e) {
    e.stopPropagation();
    const menu = document.getElementById('theme-menu');
    const open = menu.style.display === 'block';
    menu.style.display = open ? 'none' : 'block';
    document.getElementById('theme-toggle').setAttribute('aria-expanded', String(!open));
    if (!open) markActiveTheme();
}

function closeThemeMenu() {
    const menu = document.getElementById('theme-menu');
    if (menu) menu.style.display = 'none';
    const btn = document.getElementById('theme-toggle');
    if (btn) btn.setAttribute('aria-expanded', 'false');
}

function setTheme(theme) {
    if (!THEME_VALID.includes(theme)) theme = 'light';
    if (theme === 'light') document.documentElement.removeAttribute('data-theme');
    else document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('atrio-theme', theme);
    markActiveTheme();
    closeThemeMenu();
    // persiste no perfil do usuário (best-effort)
    fetch('{{ route('profile.theme') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ theme: theme }),
    }).catch(function () {});
}

// fecha o menu ao clicar fora
document.addEventListener('click', function (e) {
    const menu = document.getElementById('theme-menu');
    const btn  = document.getElementById('theme-toggle');
    if (!menu || menu.style.display !== 'block') return;
    if (!menu.contains(e.target) && !btn.contains(e.target)) closeThemeMenu();
});

// ── FOCO em inputs nos temas escuros (acento na borda; usa token, segue o tema) ──
document.addEventListener('focusin', function(e) {
    if (!['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
    if (currentTheme() !== 'light') e.target.style.borderColor = 'var(--accent)';
});
document.addEventListener('focusout', function(e) {
    if (!['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
    if (currentTheme() !== 'light') e.target.style.borderColor = 'var(--border)';
});

// marca a opção ativa no menu (o tema já vem aplicado no <head>, sem flash)
document.addEventListener('DOMContentLoaded', markActiveTheme);
</script>
