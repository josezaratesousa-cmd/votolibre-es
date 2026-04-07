<?php
/**
 * VotoLibre — Feed público de ocurrencias
 * Estilo red social, mobile-first, con share a redes sociales.
 * Data source: /app/api/ocurrencias-public.php (CORS abierto)
 */
$highlightId = isset($_GET['id']) ? (int)$_GET['id'] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0d0d0d">
<title>VotoLibre — Ocurrencias en vivo</title>
<meta name="description" content="Reportes de observadores electorales en tiempo real. Vigilancia ciudadana del proceso electoral.">
<meta property="og:title" content="VotoLibre — Ocurrencias en vivo">
<meta property="og:description" content="Reportes de observadores electorales en tiempo real">
<meta property="og:image" content="https://votolibre.info/img/logo.png">
<meta property="og:url" content="https://votolibre.info/es/feed.php">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="https://votolibre.info/img/logo.png">
<style>
:root { --header-bg: rgba(13,13,13,0.92); }
:root[data-theme="light"] { --header-bg: rgba(255,255,255,0.92); --bg: #f5f5f7; --bg2: #ffffff; --bg3: #ececef; --border: #d1d1d6; --text: #1c1c1e; --text2: #6e6e73; --text3: #9a9aa0; }
:root {
    --vl: #FECE1A;
    --vl-dark: #d4a500;
    --bg: #0d0d0d;
    --bg2: #1a1a1a;
    --bg3: #242424;
    --border: #2d2d2d;
    --text: #ffffff;
    --text2: #a0a0a0;
    --text3: #666666;
    --error: #ef4444;
    --success: #22c55e;
    --safe-t: env(safe-area-inset-top, 0px);
    --safe-b: env(safe-area-inset-bottom, 0px);
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body {
    background: var(--bg);
    color: var(--text);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 15px;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
}
a { color: inherit; text-decoration: none; }
img { display: block; max-width: 100%; }

/* ── Header sticky ── */
.vl-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--header-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    padding: calc(var(--safe-t) + 14px) 16px 14px;
}
.vl-header-inner {
    max-width: 680px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 12px;
}
.vl-logo {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: none;
}
.vl-logo img { width: 100%; height: 100%; object-fit: contain; } .vl-logo svg { width: 26px; height: 26px; }
.vl-header-text { flex: 1; min-width: 0; }
.vl-header-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--vl);
    letter-spacing: -0.3px;
    line-height: 1.1;
}
.vl-header-sub {
    font-size: 0.72rem;
    color: var(--text2);
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.vl-live-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--success);
    animation: vl-pulse 2s ease-in-out infinite;
}
@keyframes vl-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}

#vl-theme-toggle { width: 36px; height: 36px; border-radius: 50%; background: var(--bg3); border: 1px solid var(--border); color: var(--text2); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; padding: 0; } #vl-theme-toggle:hover { background: var(--vl); color: #0d0d0d; border-color: var(--vl); transform: rotate(20deg); }
.vl-header-inner { gap: 10px; }

/* ── Filters ── */
.vl-filters {
    position: sticky;
    top: calc(var(--safe-t) + 70px);
    z-index: 99;
    background: var(--header-bg);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    padding: 10px 0;
}
.vl-filters-inner {
    max-width: 680px;
    margin: 0 auto;
    display: flex;
    gap: 8px;
    padding: 0 16px;
    overflow-x: auto;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.vl-filters-inner::-webkit-scrollbar { display: none; }
.vl-chip {
    flex-shrink: 0;
    padding: 7px 14px;
    border-radius: 18px;
    background: var(--bg3);
    color: var(--text2);
    font-size: 0.78rem;
    font-weight: 600;
    border: 1.5px solid transparent;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
    user-select: none;
}
.vl-chip:hover { background: var(--border); }
.vl-chip.active {
    background: var(--vl);
    color: #0d0d0d;
    border-color: var(--vl);
    font-weight: 700;
}

/* ── Feed container ── */
.vl-feed { max-width: 1600px; margin: 0 auto; padding: 16px 24px calc(var(--safe-b) + 40px); display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 18px; align-content: start; }
@media (max-width: 760px) { .vl-feed { padding: 12px; gap: 12px; } }
.vl-empty { grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--text3);
    font-size: 0.9rem;
}
.vl-empty svg { width: 52px; height: 52px; margin-bottom: 12px; opacity: 0.4; }
.vl-loading { grid-column: 1 / -1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px 0;
}
.vl-spinner {
    width: 32px; height: 32px;
    border: 3px solid var(--border);
    border-top-color: var(--vl);
    border-radius: 50%;
    animation: vl-spin 0.9s linear infinite;
}
@keyframes vl-spin { to { transform: rotate(360deg); } }

/* ── Card (ocurrencia) ── */
.vl-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 16px;
    margin: 0;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-16px);
    transition: opacity 0.5s ease, transform 0.5s ease, border-color 0.3s;
}
.vl-card.vl-visible {
    opacity: 1;
    transform: translateY(0);
}
.vl-card.vl-highlight {
    border-color: var(--vl);
    box-shadow: 0 0 0 2px rgba(254,206,26,0.25), 0 8px 28px rgba(254,206,26,0.15);
}
.vl-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px 10px;
}
.vl-avatar-photo { background-size: cover !important; background-position: center !important; color: transparent !important; background-image: var(--selfie-url); }
.vl-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--vl), var(--vl-dark));
    color: #0d0d0d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1rem;
    flex-shrink: 0;
}
.vl-author { flex: 1; min-width: 0; }
.vl-author-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text);
}
.vl-author-meta {
    font-size: 0.7rem;
    color: var(--text3);
    margin-top: 2px;
}
.vl-header-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.vl-geo-btn { width: 34px; height: 34px; border-radius: 50%; background: var(--bg3); border: 1px solid var(--border); color: var(--text2); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; padding: 0; flex-shrink: 0; }
.vl-geo-btn:hover { background: var(--vl); color: #0d0d0d; border-color: var(--vl); transform: scale(1.05); }
.vl-geo-btn svg { width: 16px; height: 16px; }
.vl-cat-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 9px;
    border-radius: 10px;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    flex-shrink: 0;
}
.vl-cat-material { background: rgba(59,130,246,0.15); color: #60a5fa; }
.vl-cat-conflicto { background: rgba(239,68,68,0.15); color: #f87171; }
.vl-cat-personero { background: rgba(139,92,246,0.15); color: #a78bfa; }
.vl-cat-irregularidad { background: rgba(245,158,11,0.15); color: #fbbf24; }
.vl-cat-otro { background: rgba(107,114,128,0.15); color: #9ca3af; }
:root[data-theme="light"] .vl-cat-material{color:#1e40af}
:root[data-theme="light"] .vl-cat-conflicto{color:#991b1b}
:root[data-theme="light"] .vl-cat-personero{color:#5b21b6}
:root[data-theme="light"] .vl-cat-irregularidad{color:#92400e}
:root[data-theme="light"] .vl-cat-otro{color:#374151}
:root[data-theme="light"] .vl-card-text{color:#1c1c1e}
:root[data-theme="light"] .vl-card-coords{background:#fafafc}

.vl-card-text {
    padding: 4px 16px 12px;
    font-size: 0.92rem;
    line-height: 1.5;
    color: var(--text);
    white-space: pre-wrap;
    word-wrap: break-word;
}
.vl-card-photo { position: relative; width: 100%; height: 340px; background-color: var(--bg3); cursor: pointer; display: block; overflow: hidden; }
.vl-card-photo img { width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.4s ease; display: block; }
.vl-card-photo.loaded img { opacity: 1; }
.vl-card-photo.loaded .vl-shimmer { display: none; }
.vl-shimmer { position: absolute; inset: 0; background: linear-gradient(90deg, var(--bg3) 0%, var(--bg2) 50%, var(--bg3) 100%); background-size: 200% 100%; animation: vl-shimmer 1.4s ease-in-out infinite; }
@keyframes vl-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
.vl-card-photo.error .vl-shimmer { animation: none; background: var(--bg3); }
.vl-stamp-row { display: flex; align-items: center; gap: 8px; padding: 10px 16px; font-size: 0.72rem; color: #22c55e; background: rgba(34,197,94,0.06); border-top: 1px solid var(--border); cursor: pointer; transition: background 0.15s; font-weight: 600; }
.vl-stamp-row:hover { background: rgba(34,197,94,0.12); }
.vl-stamp-row span:first-of-type { flex: 1; }
.vl-stamp-arrow { color: var(--text3); font-size: 1rem; }
.vl-card-coords { padding: 8px 16px; font-size: 0.72rem; color: var(--text2); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; cursor: pointer; transition: color 0.15s; background: var(--bg); border-top: 1px solid var(--border); } .vl-card-coords:hover { color: var(--vl); }
.vl-card-location {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    font-size: 0.72rem;
    color: var(--text2);
    border-top: 1px solid var(--border);
}
.vl-card-location svg { width: 12px; height: 12px; opacity: 0.7; }

/* ── Share bar ── */
.vl-share-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 10px 10px;
    border-top: 1px solid var(--border);
    background: var(--bg2);
}
.vl-share-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 9px 4px;
    background: transparent;
    border: none;
    border-radius: 10px;
    color: var(--text2);
    font-size: 0.7rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    font-family: inherit;
}
.vl-share-btn:hover { background: var(--bg3); color: var(--text); }
.vl-share-btn svg { width: 15px; height: 15px; }
.vl-share-btn.twitter:hover { color: #1d9bf0; }
.vl-share-btn.facebook:hover { color: #1877f2; }
.vl-share-btn.whatsapp:hover { color: #25d366; }
.vl-share-btn.copy:hover { color: var(--vl); }
.vl-share-btn.copied { color: var(--success) !important; }

/* ── Photo lightbox ── */
.vl-lightbox { position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 1000; display: none; flex-direction: column; cursor: default; }
.vl-lightbox.open { display: flex; }
.vl-lightbox-header { display: flex; align-items: center; justify-content: space-between; padding: calc(var(--safe-t) + 14px) 16px 12px; background: rgba(0,0,0,0.6); border-bottom: 1px solid rgba(255,255,255,0.08); gap: 12px; }
.vl-lightbox-titlewrap { flex: 1; min-width: 0; }
.vl-lightbox-title { color: white; font-size: 0.95rem; font-weight: 700; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.vl-lightbox-meta { display: flex; align-items: center; gap: 8px; margin-top: 4px; }
.vl-lightbox-badge { background: var(--vl); color: #0d0d0d; font-size: 0.62rem; font-weight: 800; padding: 2px 7px; border-radius: 8px; font-family: ui-monospace, Menlo, monospace; }
.vl-lightbox-size { color: rgba(255,255,255,0.65); font-size: 0.66rem; font-weight: 600; }
.vl-lightbox-close { background: rgba(255,255,255,0.95); color: #0d0d0d; border: none; border-radius: 50%; width: 42px; height: 42px; min-width: 42px; font-size: 1.1rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.4); }
.vl-lightbox-body { flex: 1; display: flex; align-items: center; justify-content: center; padding: 16px; position: relative; overflow: hidden; }
.vl-lightbox-img { max-width: 100%; max-height: 100%; opacity: 0; transition: opacity 0.4s ease; border-radius: 8px; box-shadow: 0 4px 24px rgba(0,0,0,0.5); }
.vl-lightbox-img.loaded { opacity: 1; }
.vl-lightbox-loader { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; }
.vl-lightbox-spinner { width: 40px; height: 40px; border: 3px solid rgba(255,255,255,0.15); border-top-color: var(--vl); border-radius: 50%; animation: vl-spin 0.9s linear infinite; }
.vl-lightbox-loadtxt { color: rgba(255,255,255,0.7); font-size: 0.78rem; font-weight: 600; }
@keyframes vl-spin { to { transform: rotate(360deg); } }
.vl-lightbox-footer { padding: 14px 16px calc(var(--safe-b) + 16px); background: rgba(0,0,0,0.6); display: flex; gap: 10px; justify-content: center; }
.vl-lightbox-action { background: var(--vl); color: #0d0d0d; border: none; border-radius: 22px; padding: 0.7rem 1.4rem; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; gap: 0.45rem; font-family: inherit; }
.vl-lightbox-action:active { transform: scale(0.96); }
/* ── Toast ── */
.vl-toast {
    position: fixed;
    bottom: calc(var(--safe-b) + 24px);
    left: 50%;
    transform: translateX(-50%) translateY(16px);
    background: transparent;
    color: #0d0d0d;
    padding: 10px 20px;
    border-radius: 24px;
    font-size: 0.82rem;
    font-weight: 700;
    box-shadow: 0 8px 24px rgba(0,0,0,0.6);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    z-index: 2000;
}
.vl-toast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

/* ── Footer ── */
.vl-footer {
    text-align: center;
    padding: 30px 16px calc(var(--safe-b) + 30px);
    color: var(--text3);
    font-size: 0.7rem;
    border-top: 1px solid var(--border);
    margin-top: 20px;
}
.vl-footer a { color: var(--vl); }

@media (max-width: 480px) {
    .vl-header-title { font-size: 1.05rem; }
    .vl-share-btn { font-size: 0.65rem; padding: 9px 2px; }
    .vl-share-btn svg { width: 14px; height: 14px; }
}
</style>
</head>
<body>

<!-- Header -->
<header class="vl-header">
    <div class="vl-header-inner">
        <div class="vl-logo"><img src="https://votolibre.info/img/logo.png" alt="VotoLibre"></div>
        <div class="vl-header-text">
            <div class="vl-header-title">Ocurrencias</div>
            <div class="vl-header-sub">
                <span class="vl-live-dot"></span>
                <span id="vl-status">Ocurrencias en vivo</span>
            </div>
        </div>
        <button id="vl-theme-toggle" onclick="VLFeed.toggleTheme()" title="Tema"><svg id="vl-theme-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg></button>
    </div>
</header>

<!-- Filter chips -->
<nav class="vl-filters">
    <div class="vl-filters-inner" id="vl-filters">
        <button class="vl-chip active" data-filter="all">Todas</button>
        <button class="vl-chip" data-filter="material">📦 Material</button>
        <button class="vl-chip" data-filter="conflicto">⚠️ Conflictos</button>
        <button class="vl-chip" data-filter="personero">👤 Personeros</button>
        <button class="vl-chip" data-filter="irregularidad">🚨 Irregularidades</button>
        <button class="vl-chip" data-filter="otro">💬 Otras</button>
    </div>
</nav>

<!-- Feed -->
<main class="vl-feed" id="vl-feed">
    <div class="vl-loading"><div class="vl-spinner"></div></div>
</main>

<!-- Footer -->
<footer class="vl-footer">
    VotoLibre © 2026 · Observación electoral ciudadana<br>
    <a href="/es/">Volver al sitio principal</a>
</footer>

<!-- Lightbox -->
<div class="vl-lightbox" id="vl-lightbox">
    <div class="vl-lightbox-header">
        <div class="vl-lightbox-titlewrap">
            <div class="vl-lightbox-title" id="vl-lightbox-title">Foto</div>
            <div class="vl-lightbox-meta">
                <span class="vl-lightbox-badge" id="vl-lightbox-badge">cargando...</span>
                <span class="vl-lightbox-size" id="vl-lightbox-size"></span>
            </div>
        </div>
        <button class="vl-lightbox-close" onclick="VLFeed.closeLightbox()" aria-label="Cerrar">✕</button>
    </div>
    <div class="vl-lightbox-body">
        <div class="vl-lightbox-loader" id="vl-lightbox-loader">
            <div class="vl-lightbox-spinner"></div>
            <div class="vl-lightbox-loadtxt">Descargando imagen...</div>
        </div>
        <img class="vl-lightbox-img" id="vl-lightbox-img" alt="">
    </div>
    <div class="vl-lightbox-footer">
        <button class="vl-lightbox-action" onclick="VLFeed.downloadLightboxImage()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Descargar</button>
    </div>
</div>

<!-- Toast -->
<div class="vl-toast" id="vl-toast">Link copiado</div>

<script>
window.VLFeed = (function(){
    var API = 'https://votolibre.info/app/api/ocurrencias-public.php';
    var PAGE_URL = 'https://votolibre.info/es/feed.php';
    var POLL_MS = 30000;

    var state = {
        filter: 'all',
        seenIds: {},
        lastSeenIso: null,
        highlightId: <?php echo $highlightId ? (int)$highlightId : 'null'; ?>,
        pollTimer: null,
        newCount: 0,
    };

    var CAT = {
        material:      { label: 'Material',      icon: '📦' },
        conflicto:     { label: 'Conflicto',     icon: '⚠️' },
        personero:     { label: 'Personero',     icon: '👤' },
        irregularidad: { label: 'Irregularidad', icon: '🚨' },
        otro:          { label: 'Curiosidad',    icon: '💬' }
    };

    function escapeHtml(s) {
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(c){
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
    }

    function relativeTime(isoStr) {
        if (!isoStr) return '';
        var d = new Date(isoStr.replace(' ', 'T') + (isoStr.indexOf('Z')>-1 || isoStr.indexOf('+')>-1 ? '' : 'Z'));
        var diff = (Date.now() - d.getTime()) / 1000;
        if (diff < 60) return 'hace un momento';
        if (diff < 3600) return 'hace ' + Math.floor(diff/60) + ' min';
        if (diff < 86400) return 'hace ' + Math.floor(diff/3600) + ' h';
        if (diff < 604800) return 'hace ' + Math.floor(diff/86400) + ' d';
        return d.toLocaleDateString('es-PE', { day:'2-digit', month:'short' });
    }

    function getInitial(alias) {
        if (!alias || alias === 'Anónimo') return '?';
        return alias.trim().charAt(0).toUpperCase();
    }

    function buildShareUrl(id) {
        return PAGE_URL + '?id=' + id;
    }

    function buildShareText(o) {
        var cat = (CAT[o.categoria] || CAT.otro).label;
        var preview = (o.texto || '').slice(0, 120);
        if ((o.texto || '').length > 120) preview += '...';
        return '🗳️ VotoLibre · ' + cat + ' reportada por ' + o.alias + ':\n\n"' + preview + '"';
    }

    function buildCard(o) {
        var cat = CAT[o.categoria] || CAT.otro;
        var mesaTxt = o.mesa_numero ? 'Mesa ' + escapeHtml(String(o.mesa_numero)) : 'Reporte general';
        var ubic = o.ubicacion ? ' · ' + escapeHtml(o.ubicacion) : '';
        var isHighlight = state.highlightId && state.highlightId === o.id;

        var photoHtml = '';
        if (o.foto_path) {
            var thumbSrc = o.foto_path_thumb || o.foto_path;
            var fullSrc = o.stamping_image_url || o.foto_path;
            var titleAttr = escapeHtml((o.alias || 'Observador') + ' #' + o.id);
            photoHtml = '<div class="vl-card-photo vl-loading-photo" data-full="' + escapeHtml(fullSrc) + '" data-title="' + titleAttr + '" onclick="VLFeed.openLightbox(this.dataset.full, this.dataset.title)"><div class="vl-shimmer"></div><img loading="lazy" src="' + escapeHtml(thumbSrc) + '" alt="Foto de la ocurrencia" onload="this.parentElement.classList.add(&quot;loaded&quot;)" onerror="this.parentElement.classList.add(&quot;error&quot;)"></div>';
        }

        return '' +
        '<article class="vl-card ' + (isHighlight ? 'vl-highlight' : '') + '" data-id="' + o.id + '" data-cat="' + o.categoria + '">' +
            '<div class="vl-card-header">' +
                (o.selfie_path ? ('<div class="vl-avatar vl-avatar-photo" style="background-image:url(\'' + o.selfie_path.replace(/'/g,"%27") + '\')"></div>') : ('<div class="vl-avatar">' + escapeHtml(getInitial(o.alias)) + '</div>')) +
                '<div class="vl-author">' +
                    '<div class="vl-author-name">' + escapeHtml(o.alias) + '</div>' +
                    '<div class="vl-author-meta">' + relativeTime(o.created_at) + '</div>' +
                '</div>' +
                '<div class="vl-header-right">' + '<span class="vl-cat-badge vl-cat-' + o.categoria + '">' + cat.icon + ' ' + cat.label + '</span>' + ((o.geo_lat != null && o.geo_lng != null) ? ('<button class="vl-geo-btn" onclick="VLFeed.openMap(' + o.geo_lat + ',' + o.geo_lng + ')" title="Ver en mapa"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></button>') : '') + '</div>' +
            '</div>' +
            '<div class="vl-card-text">' + escapeHtml(o.texto) + '</div>' +
            photoHtml + ((o.geo_lat != null && o.geo_lng != null) ? ('<div class="vl-card-coords" onclick="VLFeed.openMap(' + o.geo_lat + ',' + o.geo_lng + ')" title="Ver en mapa">📍 ' + o.geo_lat.toFixed(6) + ', ' + o.geo_lng.toFixed(6) + '</div>') : '') + (o.stamping_uri ? ('<div class="vl-stamp-row" onclick="VLFeed.openStamp(this.dataset.uri)" data-uri="' + o.stamping_uri + '"><svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" width="14" height="14"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg><span>Atestado en Blockchain</span><span class="vl-stamp-arrow">→</span></div>') : '') +
            '<div class="vl-card-location">' +
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' +
                '<span>' + escapeHtml(mesaTxt) + ubic + '</span>' +
            '</div>' +
            '<div class="vl-share-bar">' +
                '<button class="vl-share-btn twitter" onclick="VLFeed.shareTwitter(' + o.id + ')">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' +
                    '<span>X</span>' +
                '</button>' +
                '<button class="vl-share-btn facebook" onclick="VLFeed.shareFacebook(' + o.id + ')">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' +
                    '<span>Facebook</span>' +
                '</button>' +
                '<button class="vl-share-btn whatsapp" onclick="VLFeed.shareWhatsApp(' + o.id + ')">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>' +
                    '<span>WhatsApp</span>' +
                '</button>' +
                '<button class="vl-share-btn copy" onclick="VLFeed.copyLink(' + o.id + ', this)">' +
                    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>' +
                    '<span>Copiar</span>' +
                '</button>' +
            '</div>' +
        '</article>';
    }

    function renderList(list, prepend) {
        var feed = document.getElementById('vl-feed');
        if (!list.length && !feed.querySelector('.vl-card')) {
            feed.innerHTML = '<div class="vl-empty">' +
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>' +
                '<div>No hay ocurrencias aún</div>' +
                '<div style="margin-top:6px;font-size:0.75rem;opacity:0.7;">Pronto aparecerán reportes en vivo</div>' +
            '</div>';
            return;
        }
        // Remove loader/empty state on first render
        var loader = feed.querySelector('.vl-loading, .vl-empty');
        if (loader) loader.remove();

        var html = '';
        var newCards = [];
        list.forEach(function(o){
            if (state.seenIds[o.id]) return;
            state.seenIds[o.id] = true;
            html += buildCard(o);
            newCards.push(o.id);
            if (!state.lastSeenIso || o.created_at > state.lastSeenIso) state.lastSeenIso = o.created_at;
        });
        if (!html) return;

        var wrap = document.createElement('div');
        wrap.innerHTML = html;
        var articles = wrap.querySelectorAll('article');

        if (prepend) {
            for (var i = articles.length - 1; i >= 0; i--) {
                feed.insertBefore(articles[i], feed.firstChild);
            }
        } else {
            articles.forEach(function(a){ feed.appendChild(a); });
        }

        // Trigger fade-in animation next tick
        setTimeout(function(){
            articles.forEach(function(a){ a.classList.add('vl-visible'); });
        }, 30);

        // Scroll to highlighted card on first load
        if (!prepend && state.highlightId) {
            setTimeout(function(){
                var el = document.querySelector('[data-id="' + state.highlightId + '"]');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 400);
        }
    }

    function fetchFeed(isPolling) {
        var url = API + '?limit=50';
        if (state.filter !== 'all') url += '&categoria=' + encodeURIComponent(state.filter);
        if (isPolling && state.lastSeenIso) url += '&since=' + encodeURIComponent(state.lastSeenIso);

        fetch(url)
            .then(function(r){ return r.json(); })
            .then(function(data){
                if (!data || !data.ok) return;
                var list = data.ocurrencias || [];
                if (isPolling && list.length) {
                    // Flash status
                    var status = document.getElementById('vl-status');
                    if (status) {
                        var prev = status.textContent;
                        status.textContent = list.length + ' nuevas · ahora';
                        setTimeout(function(){ status.textContent = prev; }, 3500);
                    }
                }
                renderList(list, isPolling);
            })
            .catch(function(e){
                console.warn('[VLFeed] fetch error:', e);
                var status = document.getElementById('vl-status');
                if (status) status.textContent = 'Sin conexión';
            });
    }

    function resetAndFetch() {
        state.seenIds = {};
        state.lastSeenIso = null;
        state.highlightId = null; // don't re-scroll on filter change
        var feed = document.getElementById('vl-feed');
        if (feed) feed.innerHTML = '<div class="vl-loading"><div class="vl-spinner"></div></div>';
        fetchFeed(false);
    }

    function initFilters() {
        var chips = document.querySelectorAll('#vl-filters .vl-chip');
        chips.forEach(function(chip){
            chip.addEventListener('click', function(){
                chips.forEach(function(c){ c.classList.remove('active'); });
                chip.classList.add('active');
                state.filter = chip.getAttribute('data-filter');
                resetAndFetch();
            });
        });
    }

    // ── Share actions ──
    function getOcurr(id) {
        // Look up the rendered card and extract data
        var el = document.querySelector('[data-id="' + id + '"]');
        if (!el) return null;
        var cat = el.getAttribute('data-cat');
        var alias = el.querySelector('.vl-author-name')?.textContent || 'Anónimo';
        var texto = el.querySelector('.vl-card-text')?.textContent || '';
        return { id: id, categoria: cat, alias: alias, texto: texto };
    }

    function shareTwitter(id) {
        var o = getOcurr(id); if (!o) return;
        var url = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(buildShareText(o)) + '&url=' + encodeURIComponent(buildShareUrl(id)) + '&hashtags=VotoLibre,ObservacionElectoral';
        window.open(url, '_blank', 'noopener,noreferrer');
    }

    function shareFacebook(id) {
        var url = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(buildShareUrl(id));
        window.open(url, '_blank', 'noopener,noreferrer', 'width=600,height=500');
    }

    function shareWhatsApp(id) {
        var o = getOcurr(id); if (!o) return;
        var msg = buildShareText(o) + '\n\n' + buildShareUrl(id);
        var url = 'https://wa.me/?text=' + encodeURIComponent(msg);
        window.open(url, '_blank', 'noopener,noreferrer');
    }

    function copyLink(id, btn) {
        var url = buildShareUrl(id);
        var tryNative = navigator.share && /iPhone|iPad|Android/i.test(navigator.userAgent);
        var o = getOcurr(id);
        if (tryNative && o) {
            navigator.share({
                title: 'VotoLibre — ' + (CAT[o.categoria] || CAT.otro).label,
                text: buildShareText(o),
                url: url
            }).catch(function(){ fallbackCopy(url, btn); });
        } else {
            fallbackCopy(url, btn);
        }
    }

    function fallbackCopy(text, btn) {
        var copied = false;
        try {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function(){
                    showCopiedFeedback(btn);
                });
                return;
            }
        } catch(e) {}
        // Legacy fallback
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try { copied = document.execCommand('copy'); } catch(e) {}
        document.body.removeChild(ta);
        if (copied) showCopiedFeedback(btn);
    }

    function showCopiedFeedback(btn) {
        if (btn) {
            btn.classList.add('copied');
            var span = btn.querySelector('span');
            var orig = span?.textContent;
            if (span) span.textContent = '¡Copiado!';
            setTimeout(function(){
                btn.classList.remove('copied');
                if (span) span.textContent = orig || 'Copiar';
            }, 1800);
        }
        showToast('Link copiado al portapapeles');
    }

    function showToast(msg) {
        var t = document.getElementById('vl-toast');
        if (!t) return;
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(function(){ t.classList.remove('show'); }, 2400);
    }

    function updateThemeIcon(t) { var i=document.getElementById('vl-theme-icon'); if(!i) return; i.innerHTML = (t === 'light') ? '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>' : '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>'; }
    function toggleTheme() { var cur = document.documentElement.getAttribute('data-theme'); var next = cur === 'light' ? 'dark' : 'light'; document.documentElement.setAttribute('data-theme', next); try { localStorage.setItem('vl-theme', next); } catch(e){} updateThemeIcon(next); }
    function openStamp(uri) { window.open(uri, '_blank', 'noopener,noreferrer'); }
    function openMap(lat, lng) {
        if (lat == null || lng == null) return;
        var url = 'https://www.google.com/maps?q=' + lat + ',' + lng + '&z=17';
        window.open(url, '_blank', 'noopener,noreferrer');
    }

    var currentLightboxUrl = null;
    var currentLightboxTitle = null;
    function openLightbox(src, title) {
        var lb = document.getElementById("vl-lightbox");
        var img = document.getElementById("vl-lightbox-img");
        var loader = document.getElementById("vl-lightbox-loader");
        var titleEl = document.getElementById("vl-lightbox-title");
        var badge = document.getElementById("vl-lightbox-badge");
        var sizeEl = document.getElementById("vl-lightbox-size");
        if (!lb || !img) return;
        currentLightboxUrl = src;
        currentLightboxTitle = title || "votolibre";
        if (titleEl) titleEl.textContent = title || "Foto";
        if (badge) badge.textContent = "cargando...";
        if (sizeEl) sizeEl.textContent = "";
        img.classList.remove("loaded");
        img.src = "";
        if (loader) loader.style.display = "flex";
        lb.classList.add("open");
        img.onload = function() {
            if (loader) loader.style.display = "none";
            img.classList.add("loaded");
            if (badge) { var mp = ((img.naturalWidth * img.naturalHeight) / 1000000).toFixed(1); badge.textContent = img.naturalWidth + "×" + img.naturalHeight + " (" + mp + " MP)"; }
        };
        img.onerror = function() { if (loader) loader.innerHTML = "<div style=\"color:#ef4444;\">Error al cargar la imagen</div>"; };
        fetch(src).then(function(r) { return r.blob(); }).then(function(b) {
            if (sizeEl) { var kb = b.size / 1024; sizeEl.textContent = kb >= 1024 ? (kb/1024).toFixed(1) + " MB" : Math.round(kb) + " KB"; }
            img.src = URL.createObjectURL(b);
        }).catch(function() { img.src = src; });
    }

    function downloadLightboxImage() {
        if (!currentLightboxUrl) return;
        fetch(currentLightboxUrl).then(function(r) { return r.blob(); }).then(function(b) {
            var a = document.createElement("a");
            a.href = URL.createObjectURL(b);
            a.download = (currentLightboxTitle || "votolibre") + ".jpg";
            a.click();
        }).catch(function() { alert("Error al descargar"); });
    }


    function closeLightbox() {
        document.getElementById('vl-lightbox')?.classList.remove('open');
    }

    function init() {
        try { var saved = localStorage.getItem('vl-theme'); if (saved) document.documentElement.setAttribute('data-theme', saved); updateThemeIcon(saved || 'dark'); } catch(e){}
        initFilters();
        fetchFeed(false);
        state.pollTimer = setInterval(function(){ fetchFeed(true); }, POLL_MS);
        // Close lightbox on click outside image
        document.getElementById('vl-lightbox')?.addEventListener('click', function(e){
            if (e.target.id === 'vl-lightbox') closeLightbox();
        });
        // ESC closes lightbox
        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeLightbox();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    return {
        shareTwitter: shareTwitter,
        shareFacebook: shareFacebook,
        shareWhatsApp: shareWhatsApp,
        copyLink: copyLink,
        openLightbox: openLightbox,
        downloadLightboxImage: downloadLightboxImage,
        openMap: openMap,
        openStamp: openStamp,
        toggleTheme: toggleTheme,
        closeLightbox: closeLightbox
    };
})();
</script>
</body>
</html>
