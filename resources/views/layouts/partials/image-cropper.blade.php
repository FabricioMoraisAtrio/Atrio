{{-- Cropper de imagem compartilhado.
     Uso: AtrioCropper.open(inputEl, { aspect: 1|null, previewId, placeholderId, output:'jpeg'|'png', removeFlagId, name })
     - aspect 1 = quadrado (avatar/foto); null = livre (logo). SVG passa direto (vetor). --}}
<div id="atrio-cropper" style="display:none; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.6); align-items:center; justify-content:center; padding:20px;">
    <div style="background:var(--bg-card, #fff); border:1px solid var(--border, #E5E7EB); border-radius:14px; max-width:560px; width:100%; box-shadow:0 20px 60px rgba(0,0,0,.35); overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid var(--border-sub, #F3F4F6); display:flex; align-items:center; justify-content:space-between;">
            <span style="font-size:15px; font-weight:700; color:var(--text-1, #111827);">Ajustar imagem</span>
            <button type="button" onclick="AtrioCropper.cancel()" title="Fechar" style="background:none; border:none; cursor:pointer; color:var(--text-3, #6B7280); padding:4px; display:flex;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:16px 20px; text-align:center;">
            <p style="font-size:12px; color:var(--text-4, #9CA3AF); margin:0 0 12px; text-align:left;">Arraste a seleção e use as alças nos cantos para escolher a parte que vai aparecer.</p>
            <div id="atrio-cropper-stage" style="position:relative; display:inline-block; user-select:none; touch-action:none; background:var(--bg-subtle, #F3F4F6); line-height:0; overflow:hidden; border-radius:8px; max-width:100%;">
                <img id="atrio-cropper-img" src="" style="display:block; max-width:100%; max-height:58vh; pointer-events:none;" alt="">
                <div id="atrio-cropper-box" style="position:absolute; box-shadow:0 0 0 9999px rgba(0,0,0,.55); border:1px solid #fff; cursor:move; box-sizing:border-box;">
                    <span class="acrop-h" data-h="nw" style="top:-7px;left:-7px;cursor:nwse-resize;"></span>
                    <span class="acrop-h" data-h="ne" style="top:-7px;right:-7px;cursor:nesw-resize;"></span>
                    <span class="acrop-h" data-h="sw" style="bottom:-7px;left:-7px;cursor:nesw-resize;"></span>
                    <span class="acrop-h" data-h="se" style="bottom:-7px;right:-7px;cursor:nwse-resize;"></span>
                </div>
            </div>
        </div>
        <div style="padding:14px 20px; border-top:1px solid var(--border-sub, #F3F4F6); display:flex; gap:10px; justify-content:flex-end;">
            <button type="button" onclick="AtrioCropper.cancel()"
                    style="padding:10px 18px; border-radius:8px; border:1px solid var(--border, #E5E7EB); background:transparent; color:var(--text-2, #374151); font-size:13px; font-weight:600; cursor:pointer;">Cancelar</button>
            <button type="button" onclick="AtrioCropper.confirm()"
                    style="padding:10px 18px; border-radius:8px; border:none; background:var(--accent, #004B8D); color:var(--accent-contrast, #fff); font-size:13px; font-weight:600; cursor:pointer;">Aplicar recorte</button>
        </div>
    </div>
</div>
<style>
    #atrio-cropper .acrop-h { position:absolute; width:13px; height:13px; background:#fff; border:1px solid var(--accent, #004B8D); border-radius:50%; }
</style>
<script>
const AtrioCropper = (function () {
    let inputEl = null, opts = {}, img, stage, box, natW, natH, dispW, dispH, drag = null;
    const el = (id) => document.getElementById(id);
    const clamp = (v, a, b) => Math.max(a, Math.min(b, v));

    function open(input, options) {
        const file = input.files && input.files[0];
        if (!file) return;
        opts = options || {};
        inputEl = input;
        // SVG (logo vetor): não recorta, só preview
        if (/svg/i.test(file.type) || /\.svg$/i.test(file.name)) {
            const r = new FileReader();
            r.onload = (e) => { setPreview(e.target.result); resetRemoveFlag(); if (opts.onDone) opts.onDone(); };
            r.readAsDataURL(file);
            return;
        }
        if (!/^image\//.test(file.type)) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            img = el('atrio-cropper-img');
            img.onload = function () {
                el('atrio-cropper').style.display = 'flex';
                requestAnimationFrame(() => requestAnimationFrame(initBox));
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function initBox() {
        stage = el('atrio-cropper-stage'); box = el('atrio-cropper-box');
        natW = img.naturalWidth; natH = img.naturalHeight;
        dispW = img.clientWidth; dispH = img.clientHeight;
        let w, h;
        if (opts.aspect) {
            const a = opts.aspect;
            if (dispW / dispH > a) { h = dispH * 0.92; w = h * a; } else { w = dispW * 0.92; h = w / a; }
        } else { w = dispW * 0.86; h = dispH * 0.86; }
        setBox((dispW - w) / 2, (dispH - h) / 2, w, h);
        box.onpointerdown = function (e) {
            startDrag(e, e.target.classList.contains('acrop-h') ? e.target.dataset.h : 'move');
        };
    }

    function setBox(x, y, w, h) { box.style.left = x + 'px'; box.style.top = y + 'px'; box.style.width = w + 'px'; box.style.height = h + 'px'; }
    function getBox() { return { x: box.offsetLeft, y: box.offsetTop, w: box.offsetWidth, h: box.offsetHeight }; }

    function startDrag(e, mode) {
        e.preventDefault();
        drag = { mode, sx: e.clientX, sy: e.clientY, b: getBox() };
        document.addEventListener('pointermove', onMove);
        document.addEventListener('pointerup', onUp);
    }

    function onMove(e) {
        if (!drag) return;
        const dx = e.clientX - drag.sx, dy = e.clientY - drag.sy;
        let { x, y, w, h } = drag.b;
        const minS = 32, R = dispW, B = dispH, a = opts.aspect;
        if (drag.mode === 'move') {
            x = clamp(x + dx, 0, R - w); y = clamp(y + dy, 0, B - h);
        } else {
            let nx = x, ny = y, nw = w, nh = h;
            if (drag.mode.includes('e')) nw = w + dx;
            if (drag.mode.includes('s')) nh = h + dy;
            if (drag.mode.includes('w')) { nw = w - dx; nx = x + dx; }
            if (drag.mode.includes('n')) { nh = h - dy; ny = y + dy; }
            if (a) { nh = nw / a; if (drag.mode.includes('n')) ny = y + (h - nh); }
            nw = Math.max(minS, nw); nh = Math.max(minS, nh);
            if (nx < 0) { nw += nx; nx = 0; if (a) nh = nw / a; }
            if (ny < 0) { nh += ny; ny = 0; if (a) nw = nh * a; }
            if (nx + nw > R) { nw = R - nx; if (a) nh = nw / a; }
            if (ny + nh > B) { nh = B - ny; if (a) nw = nh * a; }
            x = nx; y = ny; w = nw; h = nh;
        }
        setBox(x, y, w, h);
    }
    function onUp() { drag = null; document.removeEventListener('pointermove', onMove); document.removeEventListener('pointerup', onUp); }

    function confirm() {
        const b = getBox(), scale = natW / dispW;
        const sx = b.x * scale, sy = b.y * scale, sw = b.w * scale, sh = b.h * scale;
        const cap = opts.aspect ? 512 : 1280;
        let outW, outH;
        if (sw >= sh) { outW = Math.min(sw, cap); outH = outW * (sh / sw); }
        else { outH = Math.min(sh, cap); outW = outH * (sw / sh); }
        const canvas = document.createElement('canvas');
        canvas.width = Math.max(1, Math.round(outW)); canvas.height = Math.max(1, Math.round(outH));
        const ctx = canvas.getContext('2d');
        const type = opts.output === 'png' ? 'image/png' : 'image/jpeg';
        if (type === 'image/jpeg') { ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height); }
        ctx.drawImage(img, sx, sy, sw, sh, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(function (blob) {
            const ext = type === 'image/png' ? 'png' : 'jpg';
            const file = new File([blob], (opts.name || 'imagem') + '.' + ext, { type });
            const dt = new DataTransfer(); dt.items.add(file); inputEl.files = dt.files;
            setPreview(canvas.toDataURL(type, 0.92));
            resetRemoveFlag();
            close();
            if (opts.onDone) opts.onDone();
        }, type, 0.92);
    }

    function setPreview(url) {
        if (opts.previewId) {
            const p = el(opts.previewId);
            if (p) {
                if (p.tagName === 'IMG') { p.src = url; p.style.display = 'block'; }
                else { p.style.backgroundImage = 'url(' + url + ')'; p.style.backgroundSize = 'cover'; p.style.backgroundPosition = 'center'; }
            }
        }
        if (opts.placeholderId) { const ph = el(opts.placeholderId); if (ph) ph.style.display = 'none'; }
        if (opts.wrapperId) { const w = el(opts.wrapperId); if (w) w.style.display = ''; }
        if (opts.nameId) { const n = el(opts.nameId); if (n) n.textContent = 'Imagem ajustada'; }
    }
    function resetRemoveFlag() { if (opts.removeFlagId) { const f = el(opts.removeFlagId); if (f) f.value = '0'; } }

    function cancel() { if (inputEl) inputEl.value = ''; close(); }
    function close() { el('atrio-cropper').style.display = 'none'; }

    return { open, confirm, cancel };
})();

// Botão "Remover foto": limpa input/preview e marca a flag de remoção.
function atrioRemovePhoto(opts) {
    const input = document.getElementById(opts.inputId);
    if (input) input.value = '';
    const flag = document.getElementById(opts.removeFlagId);
    if (flag) flag.value = '1';
    const preview = document.getElementById(opts.previewId);
    if (preview && opts.placeholderId) {
        if (preview.tagName === 'IMG') preview.style.display = 'none';
        else { preview.style.backgroundImage = ''; }
        const ph = document.getElementById(opts.placeholderId);
        if (ph) ph.style.display = '';
    } else if (preview && preview.tagName === 'IMG') {
        preview.removeAttribute('src'); preview.style.display = 'none';
    }
    if (opts.wrapperId) { const w = document.getElementById(opts.wrapperId); if (w) w.style.display = 'none'; }
    if (opts.nameId) { const n = document.getElementById(opts.nameId); if (n) n.textContent = 'Nenhum arquivo selecionado'; }
}
</script>
