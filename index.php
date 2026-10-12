<?php
declare(strict_types=1);

$dataFile = __DIR__ . '/data.json';

$defaultLinks = [
  ['name' => 'GitHub', 'url' => 'https://github.com'],
  ['name' => 'HackerOne', 'url' => 'https://hackerone.com'],
  ['name' => 'Bugcrowd', 'url' => 'https://bugcrowd.com'],
  ['name' => 'YouTube', 'url' => 'https://youtube.com'],
  ['name' => 'Twitter/X', 'url' => 'https://x.com'],
  ['name' => 'Reddit', 'url' => 'https://reddit.com']
];

$defaultTools = [
  ['icon' => '🔍', 'title' => 'Recon', 'color' => '#58a6ff', 'items' => [
    ['n' => 'Subfinder', 'u' => 'https://github.com/projectdiscovery/subfinder'],
    ['n' => 'Amass', 'u' => 'https://github.com/owasp-amass/amass'],
    ['n' => 'httpx', 'u' => 'https://github.com/projectdiscovery/httpx'],
    ['n' => 'naabu', 'u' => 'https://github.com/projectdiscovery/naabu'],
    ['n' => 'Sublist3r', 'u' => 'https://github.com/aboul3la/Sublist3r']
  ]],
  ['icon' => '🛡️', 'title' => 'Fuzzing / Scanning', 'color' => '#bc8cff', 'items' => [
    ['n' => 'ffuf', 'u' => 'https://github.com/ffuf/ffuf'],
    ['n' => 'Nuclei', 'u' => 'https://github.com/projectdiscovery/nuclei'],
    ['n' => 'Nmap', 'u' => 'https://nmap.org'],
    ['n' => 'Arjun', 'u' => 'https://github.com/s0md3v/Arjun'],
    ['n' => 'feroxbuster', 'u' => 'https://github.com/epi052/feroxbuster']
  ]],
  ['icon' => '🐛', 'title' => 'Web / Exploitation', 'color' => '#f0883e', 'items' => [
    ['n' => 'Burp Suite', 'u' => 'https://portswigger.net/burp'],
    ['n' => 'OWASP ZAP', 'u' => 'https://www.zaproxy.org'],
    ['n' => 'Caido', 'u' => 'https://caido.io'],
    ['n' => 'sqlmap', 'u' => 'https://sqlmap.org'],
    ['n' => 'XSStrike', 'u' => 'https://github.com/s0md3v/XSStrike']
  ]],
  ['icon' => '📚', 'title' => 'Payloads & Referencias', 'color' => '#3fb950', 'items' => [
    ['n' => 'PayloadAllTheThings', 'u' => 'https://github.com/swisskyrepo/PayloadsAllTheThings'],
    ['n' => 'HackTricks', 'u' => 'https://book.hacktricks.xyz'],
    ['n' => 'PayloadBox', 'u' => 'https://github.com/payloadbox'],
    ['n' => 'PortSwigger Academy', 'u' => 'https://portswigger.net/web-security']
  ]],
  ['icon' => '🎯', 'title' => 'Plataformas', 'color' => '#f85149', 'items' => [
    ['n' => 'HackerOne', 'u' => 'https://hackerone.com'],
    ['n' => 'Bugcrowd', 'u' => 'https://bugcrowd.com'],
    ['n' => 'Intigriti', 'u' => 'https://intigriti.com'],
    ['n' => 'Synack', 'u' => 'https://synack.com'],
    ['n' => 'Immunefi', 'u' => 'https://immunefi.com']
  ]],
  ['icon' => '⚙️', 'title' => 'Utilidades', 'color' => '#8b949e', 'items' => [
    ['n' => 'CyberChef', 'u' => 'https://gchq.github.io/CyberChef'],
    ['n' => 'JWT.io', 'u' => 'https://jwt.io'],
    ['n' => 'DNSdumpster', 'u' => 'https://dnsdumpster.com'],
    ['n' => 'Shodan', 'u' => 'https://shodan.io']
  ]]
];

function readData(string $file): array {
  if (!is_file($file)) {
    return [];
  }
  $decoded = json_decode((string) file_get_contents($file), true);
  return is_array($decoded) ? $decoded : [];
}

function jsonResponse(array $payload, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  exit;
}

$api = isset($_GET['api']) ? (string) $_GET['api'] : '';

if ($api === 'get') {
  $stored = readData($dataFile);
  jsonResponse([
    'links' => isset($stored['links']) && is_array($stored['links']) ? $stored['links'] : $defaultLinks,
    'tools' => isset($stored['tools']) && is_array($stored['tools']) ? $stored['tools'] : $defaultTools,
    'defaults' => ['links' => $defaultLinks, 'tools' => $defaultTools]
  ]);
}

if ($api === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $body = json_decode((string) file_get_contents('php://input'), true);
  if (!is_array($body)) {
    jsonResponse(['ok' => false], 400);
  }
  $out = [
    'links' => isset($body['links']) && is_array($body['links']) ? $body['links'] : [],
    'tools' => isset($body['tools']) && is_array($body['tools']) ? $body['tools'] : []
  ];
  $written = file_put_contents($dataFile, json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), LOCK_EX);
  jsonResponse(['ok' => $written !== false]);
}

if ($api === 'reset') {
  if (is_file($dataFile)) {
    unlink($dataFile);
  }
  jsonResponse(['ok' => true]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="InfernoidPl4y">
<title>Inicio · InfernoidPl4y</title>
<style>
  :root {
    --bg: #0d1117;
    --panel: rgba(22, 27, 34, 0.75);
    --panel-border: rgba(88, 166, 255, 0.15);
    --text: #e6edf3;
    --muted: #8b949e;
    --accent: #58a6ff;
    --accent2: #bc8cff;
    --accent3: #3fb950;
    --danger: #f85149;
    --input-bg: rgba(22, 27, 34, 0.9);
    --hover: rgba(88, 166, 255, 0.12);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  html, body { height: 100%; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
    overflow-x: hidden;
  }
  #bg-canvas { position: fixed; inset: 0; z-index: 0; }
  .glow { position: fixed; z-index: 0; border-radius: 50%; filter: blur(120px); opacity: 0.35; pointer-events: none; }
  .glow-1 { width: 500px; height: 500px; background: #1f6feb; top: -150px; left: -100px; animation: float1 18s ease-in-out infinite; }
  .glow-2 { width: 450px; height: 450px; background: #8957e5; bottom: -150px; right: -100px; animation: float2 22s ease-in-out infinite; }
  .glow-3 { width: 350px; height: 350px; background: #238636; top: 40%; left: 60%; animation: float1 25s ease-in-out infinite reverse; opacity: 0.2; }
  @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(60px,40px)} }
  @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-50px,-40px)} }
  .container {
    position: relative; z-index: 1;
    max-width: 1000px; margin: 0 auto;
    padding: 40px 20px 60px;
    display: flex; flex-direction: column; align-items: center;
    min-height: 100%;
  }
  .clock-wrap { text-align: center; margin-bottom: 28px; }
  .clock {
    font-size: clamp(3.5rem, 10vw, 6rem);
    font-weight: 700; letter-spacing: -2px; line-height: 1;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
  }
  .date { color: var(--muted); font-size: 1.1rem; margin-top: 8px; text-transform: capitalize; }
  .search-wrap {
    width: 100%; max-width: 620px; margin-bottom: 36px;
    display: flex; flex-direction: column; gap: 10px; align-items: center;
  }
  .engine-bar { display: flex; gap: 6px; flex-wrap: wrap; justify-content: center; }
  .engine-btn {
    background: transparent; border: 1px solid transparent; color: var(--muted);
    padding: 5px 12px; border-radius: 20px; cursor: pointer; font-size: 0.82rem;
    transition: all 0.2s;
  }
  .engine-btn:hover { color: var(--text); background: var(--hover); }
  .engine-btn.active { color: var(--accent); border-color: var(--accent); background: var(--hover); }
  .search-box {
    width: 100%; display: flex; align-items: center;
    background: var(--input-bg); border: 1px solid var(--panel-border);
    border-radius: 14px; padding: 4px 4px 4px 16px;
    transition: border-color 0.25s, box-shadow 0.25s;
    backdrop-filter: blur(12px);
  }
  .search-box:focus-within { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(88,166,255,0.15); }
  .search-box svg { flex-shrink: 0; opacity: 0.5; }
  .search-box input {
    flex: 1; background: none; border: none; outline: none;
    color: var(--text); font-size: 1.05rem; padding: 12px 12px; min-width: 0;
  }
  .search-box input::placeholder { color: var(--muted); }
  .search-go {
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border: none; color: #fff; width: 42px; height: 42px; border-radius: 10px;
    cursor: pointer; display: grid; place-items: center; transition: transform 0.15s;
    flex-shrink: 0;
  }
  .search-go:hover { transform: scale(1.06); }
  .kbd-hint { color: var(--muted); font-size: 0.75rem; }
  .kbd-hint kbd {
    background: var(--panel); border: 1px solid var(--panel-border);
    padding: 1px 6px; border-radius: 5px; font-family: inherit;
  }
  .section { width: 100%; margin-bottom: 32px; }
  .section-head {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 14px; font-size: 0.95rem; color: var(--muted);
    text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
  }
  .section-head .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
  .head-btn {
    margin-left: auto; background: none; border: 1px solid var(--panel-border);
    color: var(--muted); width: 24px; height: 24px; border-radius: 6px; cursor: pointer;
    display: grid; place-items: center; font-size: 0.85rem; transition: all 0.15s;
  }
  .head-btn:hover { color: var(--text); border-color: var(--accent); background: var(--hover); }
  .quick-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
  .quick-card {
    background: var(--panel); border: 1px solid var(--panel-border);
    border-radius: 12px; padding: 16px 10px; text-align: center;
    text-decoration: none; color: var(--text); cursor: pointer;
    transition: transform 0.18s, border-color 0.2s, background 0.2s;
    backdrop-filter: blur(10px); position: relative; overflow: hidden;
  }
  .quick-card:hover { transform: translateY(-3px); border-color: var(--accent); background: var(--hover); }
  .quick-card img { width: 30px; height: 30px; border-radius: 7px; margin-bottom: 8px; }
  .quick-card .label { font-size: 0.82rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
  .card-btns {
    position: absolute; top: 4px; right: 4px; display: flex; gap: 2px;
    opacity: 0; transition: opacity 0.2s;
  }
  .quick-card:hover .card-btns { opacity: 1; }
  .card-btn {
    background: none; border: none; color: var(--muted); cursor: pointer;
    font-size: 0.8rem; padding: 1px 3px; border-radius: 4px; line-height: 1.2;
  }
  .card-btn:hover { color: var(--text); background: var(--hover); }
  .card-btn.danger:hover { color: var(--danger); }
  .add-card {
    background: transparent; border: 1px dashed var(--panel-border);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 6px; color: var(--muted); font-size: 1.4rem; min-height: 92px;
    border-radius: 12px; cursor: pointer; transition: all 0.2s;
  }
  .add-card:hover { border-color: var(--accent); color: var(--accent); background: var(--hover); }
  .add-card span { font-size: 0.78rem; }
  .tools-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 12px; }
  .tool-group {
    background: var(--panel); border: 1px solid var(--panel-border);
    border-radius: 12px; padding: 16px; backdrop-filter: blur(10px);
  }
  .tool-group h3 {
    font-size: 0.85rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;
    font-weight: 600;
  }
  .tool-group h3 .cat-icon { font-size: 1rem; }
  .group-actions { margin-left: auto; display: flex; gap: 2px; opacity: 0; transition: opacity 0.2s; }
  .tool-group:hover .group-actions { opacity: 1; }
  .group-actions button {
    background: none; border: none; color: var(--muted); cursor: pointer;
    font-size: 0.9rem; padding: 2px 5px; border-radius: 5px; line-height: 1.2;
  }
  .group-actions button:hover { color: var(--text); background: var(--hover); }
  .group-actions button.danger:hover { color: var(--danger); }
  .tool-list { list-style: none; display: flex; flex-direction: column; gap: 2px; }
  .tool-list li { position: relative; }
  .tool-list a {
    display: flex; align-items: center; gap: 8px; text-decoration: none;
    color: var(--muted); font-size: 0.85rem; padding: 5px 8px;
    border-radius: 7px; transition: all 0.15s;
  }
  .tool-list a:hover { color: var(--text); background: var(--hover); padding-left: 12px; }
  .tool-list a .arr { margin-left: auto; opacity: 0; transition: opacity 0.15s; }
  .tool-list a:hover .arr { opacity: 0.6; }
  .tool-btns {
    position: absolute; right: 4px; top: 50%; transform: translateY(-50%);
    display: flex; gap: 2px; opacity: 0; transition: opacity 0.15s;
  }
  .tool-list li:hover .tool-btns { opacity: 1; }
  .tool-btns button {
    background: var(--panel); border: none; color: var(--muted); cursor: pointer;
    font-size: 0.8rem; padding: 2px 5px; border-radius: 5px; line-height: 1.2;
  }
  .tool-btns button:hover { color: var(--text); background: var(--hover); }
  .tool-btns button.danger:hover { color: var(--danger); }
  .add-tool {
    width: 100%; background: transparent; border: 1px dashed var(--panel-border);
    color: var(--muted); padding: 6px; border-radius: 7px; cursor: pointer;
    font-size: 0.8rem; margin-top: 6px; transition: all 0.15s;
  }
  .add-tool:hover { border-color: var(--accent); color: var(--accent); background: var(--hover); }
  footer {
    margin-top: auto; padding-top: 30px; color: var(--muted);
    font-size: 0.78rem; text-align: center; opacity: 0.6;
  }
  .modal-bg {
    position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.6);
    display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px);
  }
  .modal-bg.open { display: flex; }
  .modal {
    background: #161b22; border: 1px solid var(--panel-border); border-radius: 14px;
    padding: 24px; width: 90%; max-width: 380px; display: flex; flex-direction: column; gap: 14px;
  }
  .modal h3 { font-size: 1rem; }
  .modal label { font-size: 0.8rem; color: var(--muted); display: flex; flex-direction: column; gap: 5px; }
  .modal input {
    background: var(--input-bg); border: 1px solid var(--panel-border); border-radius: 8px;
    color: var(--text); padding: 9px 11px; font-size: 0.9rem; outline: none;
  }
  .modal input:focus { border-color: var(--accent); }
  .modal input[type="color"] { padding: 4px; height: 40px; cursor: pointer; }
  .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; }
  .btn {
    padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.85rem;
    border: 1px solid var(--panel-border); background: transparent; color: var(--text);
    transition: all 0.15s;
  }
  .btn:hover { background: var(--hover); }
  .btn.primary { background: linear-gradient(135deg, var(--accent), var(--accent2)); border-color: transparent; color: #fff; }
  .btn.primary:hover { opacity: 0.9; }
  @media (max-width: 560px) {
    .quick-grid { grid-template-columns: repeat(auto-fill, minmax(95px, 1fr)); }
    .tools-grid { grid-template-columns: 1fr; }
    .container { padding: 24px 14px 40px; }
  }
</style>
</head>
<body>
<canvas id="bg-canvas"></canvas>
<div class="glow glow-1"></div>
<div class="glow glow-2"></div>
<div class="glow glow-3"></div>

<div class="container">
  <div class="clock-wrap">
    <div class="clock" id="clock">00:00</div>
    <div class="date" id="date"></div>
  </div>

  <div class="search-wrap">
    <div class="engine-bar" id="engine-bar"></div>
    <form class="search-box" id="search-form" autocomplete="off">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
      <input type="text" id="search-input" placeholder="Buscar en Google…" autofocus>
      <button class="search-go" type="submit" aria-label="Buscar">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </form>
    <div class="kbd-hint">Pulsa <kbd>/</kbd> para buscar</div>
  </div>

  <div class="section">
    <div class="section-head"><span class="dot" style="background:var(--accent)"></span> Enlaces rápidos</div>
    <div class="quick-grid" id="quick-grid"></div>
  </div>

  <div class="section">
    <div class="section-head">
      <span class="dot" style="background:var(--accent3)"></span> Bug Bounty · Tools
      <button class="head-btn" id="tools-reset" title="Restaurar categorías por defecto">↺</button>
    </div>
    <div class="tools-grid" id="tools-grid"></div>
  </div>

  <footer>InfernoidPl4y · Hecho para cazar bugs</footer>
</div>

<div class="modal-bg" id="link-modal">
  <div class="modal">
    <h3 id="link-title">Añadir enlace</h3>
    <label>Nombre <input id="link-name" placeholder="Ej. GitHub"></label>
    <label>URL <input id="link-url" placeholder="https://github.com"></label>
    <div class="modal-actions">
      <button class="btn" data-close="link-modal">Cancelar</button>
      <button class="btn primary" id="link-save">Guardar</button>
    </div>
  </div>
</div>

<div class="modal-bg" id="cat-modal">
  <div class="modal">
    <h3 id="cat-title">Añadir categoría</h3>
    <label>Icono <input id="cat-icon" placeholder="Ej. 🔍" maxlength="4"></label>
    <label>Nombre <input id="cat-name" placeholder="Ej. Recon"></label>
    <label>Color <input id="cat-color" type="color" value="#58a6ff"></label>
    <div class="modal-actions">
      <button class="btn" data-close="cat-modal">Cancelar</button>
      <button class="btn primary" id="cat-save">Guardar</button>
    </div>
  </div>
</div>

<div class="modal-bg" id="tool-modal">
  <div class="modal">
    <h3 id="tool-title">Añadir tool</h3>
    <label>Nombre <input id="tool-name" placeholder="Ej. Nuclei"></label>
    <label>URL <input id="tool-url" placeholder="https://github.com/projectdiscovery/nuclei"></label>
    <div class="modal-actions">
      <button class="btn" data-close="tool-modal">Cancelar</button>
      <button class="btn primary" id="tool-save">Guardar</button>
    </div>
  </div>
</div>

<script>
const clockEl = document.getElementById('clock');
const dateEl = document.getElementById('date');
function tick() {
  const now = new Date();
  clockEl.textContent = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
  dateEl.textContent = now.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}
tick();
setInterval(tick, 1000);

const ENGINES = [
  { id: 'google', name: 'Google', url: 'https://www.google.com/search?q=' },
  { id: 'ddg', name: 'DuckDuckGo', url: 'https://duckduckgo.com/?q=' },
  { id: 'bing', name: 'Bing', url: 'https://www.bing.com/search?q=' },
  { id: 'gh', name: 'GitHub', url: 'https://github.com/search?q=' },
  { id: 'so', name: 'StackOverflow', url: 'https://stackoverflow.com/search?q=' }
];
let engine = localStorage.getItem('ff_engine') || 'google';
const engineBar = document.getElementById('engine-bar');
const searchInput = document.getElementById('search-input');
function renderEngines() {
  engineBar.innerHTML = '';
  ENGINES.forEach(e => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'engine-btn' + (e.id === engine ? ' active' : '');
    b.textContent = e.name;
    b.onclick = () => {
      engine = e.id;
      localStorage.setItem('ff_engine', e.id);
      renderEngines();
      updatePlaceholder();
      searchInput.focus();
    };
    engineBar.appendChild(b);
  });
}
function updatePlaceholder() {
  const e = ENGINES.find(x => x.id === engine);
  searchInput.placeholder = 'Buscar en ' + e.name + '…';
}
renderEngines();
updatePlaceholder();
document.getElementById('search-form').addEventListener('submit', ev => {
  ev.preventDefault();
  const q = searchInput.value.trim();
  if (!q) return;
  const e = ENGINES.find(x => x.id === engine);
  window.location.href = e.url + encodeURIComponent(q);
});
document.addEventListener('keydown', ev => {
  if (ev.key === '/' && document.activeElement !== searchInput) {
    ev.preventDefault();
    searchInput.focus();
    searchInput.select();
  }
});

let links = [];
let toolsData = [];
let defaultData = { links: [], tools: [] };

function persist() {
  fetch('?api=save', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ links, tools: toolsData })
  });
}
function saveLinks() { persist(); }
function saveTools() { persist(); }

function favicon(url) {
  try {
    const h = new URL(url).hostname;
    return 'https://icons.duckduckgo.com/ip3/' + h + '.ico';
  } catch {
    return '';
  }
}
function fallbackFavicon(img, url) {
  img.onerror = null;
  try {
    const h = new URL(url).hostname;
    img.src = 'https://www.google.com/s2/favicons?sz=64&domain=' + h;
  } catch {
    img.style.visibility = 'hidden';
  }
}

const quickGrid = document.getElementById('quick-grid');
function renderLinks() {
  quickGrid.innerHTML = '';
  links.forEach((l, i) => {
    const a = document.createElement('a');
    a.className = 'quick-card';
    a.href = l.url;
    a.title = l.url;
    a.target = '_blank';
    a.rel = 'noopener';
    const img = document.createElement('img');
    img.src = favicon(l.url);
    img.alt = '';
    img.loading = 'lazy';
    img.onerror = () => fallbackFavicon(img, l.url);
    const span = document.createElement('span');
    span.className = 'label';
    span.textContent = l.name;
    const btns = document.createElement('div');
    btns.className = 'card-btns';
    const ed = document.createElement('button');
    ed.className = 'card-btn';
    ed.textContent = '✎';
    ed.title = 'Editar';
    ed.onclick = ev => { ev.preventDefault(); ev.stopPropagation(); openLinkModal(i); };
    const del = document.createElement('button');
    del.className = 'card-btn danger';
    del.textContent = '✕';
    del.title = 'Eliminar';
    del.onclick = ev => { ev.preventDefault(); ev.stopPropagation(); links.splice(i, 1); saveLinks(); renderLinks(); };
    btns.append(ed, del);
    a.append(img, span, btns);
    quickGrid.appendChild(a);
  });
  const add = document.createElement('button');
  add.className = 'add-card';
  add.innerHTML = '+<span>Añadir</span>';
  add.onclick = () => openLinkModal(-1);
  quickGrid.appendChild(add);
}

const toolsGrid = document.getElementById('tools-grid');
function renderTools() {
  toolsGrid.innerHTML = '';
  toolsData.forEach((g, gi) => {
    const div = document.createElement('div');
    div.className = 'tool-group';
    const h3 = document.createElement('h3');
    const icon = document.createElement('span');
    icon.className = 'cat-icon';
    icon.textContent = g.icon;
    h3.appendChild(icon);
    h3.appendChild(document.createTextNode(' ' + g.title));
    const actions = document.createElement('div');
    actions.className = 'group-actions';
    const addT = document.createElement('button');
    addT.textContent = '＋';
    addT.title = 'Añadir tool';
    addT.onclick = () => openToolModal(gi);
    const editC = document.createElement('button');
    editC.textContent = '✎';
    editC.title = 'Editar categoría';
    editC.onclick = () => openCatModal(gi);
    const delC = document.createElement('button');
    delC.className = 'danger';
    delC.textContent = '✕';
    delC.title = 'Eliminar categoría';
    delC.onclick = () => { toolsData.splice(gi, 1); saveTools(); renderTools(); };
    actions.append(addT, editC, delC);
    h3.appendChild(actions);
    div.appendChild(h3);
    const ul = document.createElement('ul');
    ul.className = 'tool-list';
    g.items.forEach((it, ti) => {
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = it.u;
      a.target = '_blank';
      a.rel = 'noopener';
      a.appendChild(document.createTextNode(it.n));
      const arr = document.createElement('span');
      arr.className = 'arr';
      arr.textContent = '↗';
      a.appendChild(arr);
      const btns = document.createElement('div');
      btns.className = 'tool-btns';
      const ed = document.createElement('button');
      ed.textContent = '✎';
      ed.title = 'Editar';
      ed.onclick = ev => { ev.preventDefault(); ev.stopPropagation(); openToolModal(gi, ti); };
      const del = document.createElement('button');
      del.className = 'danger';
      del.textContent = '✕';
      del.title = 'Eliminar';
      del.onclick = ev => { ev.preventDefault(); ev.stopPropagation(); g.items.splice(ti, 1); saveTools(); renderTools(); };
      btns.append(ed, del);
      li.append(a, btns);
      ul.appendChild(li);
    });
    const addLi = document.createElement('li');
    const addRow = document.createElement('button');
    addRow.className = 'add-tool';
    addRow.textContent = '+ Añadir tool';
    addRow.onclick = () => openToolModal(gi);
    addLi.appendChild(addRow);
    ul.appendChild(addLi);
    div.appendChild(ul);
    toolsGrid.appendChild(div);
  });
  const addCat = document.createElement('button');
  addCat.className = 'add-card';
  addCat.innerHTML = '+<span>Añadir categoría</span>';
  addCat.onclick = () => openCatModal(-1);
  toolsGrid.appendChild(addCat);
}

document.getElementById('tools-reset').onclick = () => {
  toolsData = JSON.parse(JSON.stringify(defaultData.tools));
  saveTools();
  renderTools();
};

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('[data-close]').forEach(b => {
  b.onclick = () => closeModal(b.dataset.close);
});
document.querySelectorAll('.modal-bg').forEach(m => {
  m.onclick = ev => { if (ev.target === m) m.classList.remove('open'); };
});
document.addEventListener('keydown', ev => {
  if (ev.key === 'Escape') {
    document.querySelectorAll('.modal-bg.open').forEach(m => m.classList.remove('open'));
  }
});

const linkTitle = document.getElementById('link-title');
const linkName = document.getElementById('link-name');
const linkUrl = document.getElementById('link-url');
let editLink = -1;
function openLinkModal(i = -1) {
  editLink = i;
  linkTitle.textContent = i >= 0 ? 'Editar enlace' : 'Añadir enlace';
  linkName.value = i >= 0 ? links[i].name : '';
  linkUrl.value = i >= 0 ? links[i].url : '';
  openModal('link-modal');
  linkName.focus();
}
document.getElementById('link-save').onclick = () => {
  const name = linkName.value.trim();
  let url = linkUrl.value.trim();
  if (!name || !url) return;
  if (!/^https?:\/\//i.test(url)) url = 'https://' + url;
  if (editLink >= 0) links[editLink] = { name, url };
  else links.push({ name, url });
  saveLinks();
  renderLinks();
  closeModal('link-modal');
};

const catTitle = document.getElementById('cat-title');
const catIcon = document.getElementById('cat-icon');
const catName = document.getElementById('cat-name');
const catColor = document.getElementById('cat-color');
let editCat = -1;
function openCatModal(i = -1) {
  editCat = i;
  catTitle.textContent = i >= 0 ? 'Editar categoría' : 'Añadir categoría';
  catIcon.value = i >= 0 ? toolsData[i].icon : '📁';
  catName.value = i >= 0 ? toolsData[i].title : '';
  catColor.value = i >= 0 ? toolsData[i].color : '#58a6ff';
  openModal('cat-modal');
  catName.focus();
}
document.getElementById('cat-save').onclick = () => {
  const icon = catIcon.value.trim() || '📁';
  const title = catName.value.trim();
  const color = catColor.value;
  if (!title) return;
  if (editCat >= 0) toolsData[editCat] = { ...toolsData[editCat], icon, title, color };
  else toolsData.push({ icon, title, color, items: [] });
  saveTools();
  renderTools();
  closeModal('cat-modal');
};

const toolTitle = document.getElementById('tool-title');
const toolName = document.getElementById('tool-name');
const toolUrl = document.getElementById('tool-url');
let toolCat = -1;
let editTool = -1;
function openToolModal(gi, ti = -1) {
  toolCat = gi;
  editTool = ti;
  toolTitle.textContent = ti >= 0 ? 'Editar tool' : 'Añadir tool';
  toolName.value = ti >= 0 ? toolsData[gi].items[ti].n : '';
  toolUrl.value = ti >= 0 ? toolsData[gi].items[ti].u : '';
  openModal('tool-modal');
  toolName.focus();
}
document.getElementById('tool-save').onclick = () => {
  const n = toolName.value.trim();
  let u = toolUrl.value.trim();
  if (!n || !u || toolCat < 0) return;
  if (!/^https?:\/\//i.test(u)) u = 'https://' + u;
  if (editTool >= 0) toolsData[toolCat].items[editTool] = { n, u };
  else toolsData[toolCat].items.push({ n, u });
  saveTools();
  renderTools();
  closeModal('tool-modal');
};

async function loadData() {
  const r = await fetch('?api=get');
  const d = await r.json();
  links = Array.isArray(d.links) ? d.links : [];
  toolsData = Array.isArray(d.tools) ? d.tools : [];
  defaultData = d.defaults || { links: [], tools: [] };
  renderLinks();
  renderTools();
}
loadData();

const canvas = document.getElementById('bg-canvas');
const ctx = canvas.getContext('2d');
let W, H;
const particles = [];
const COUNT = 60;
function resize() {
  W = canvas.width = window.innerWidth;
  H = canvas.height = window.innerHeight;
}
function rand(a, b) { return Math.random() * (b - a) + a; }
resize();
window.addEventListener('resize', resize);
for (let i = 0; i < COUNT; i++) {
  particles.push({ x: rand(0, W), y: rand(0, H), vx: rand(-0.3, 0.3), vy: rand(-0.3, 0.3), r: rand(0.6, 2) });
}
function draw() {
  ctx.clearRect(0, 0, W, H);
  for (let i = 0; i < particles.length; i++) {
    const p = particles[i];
    p.x += p.vx;
    p.y += p.vy;
    if (p.x < 0 || p.x > W) p.vx *= -1;
    if (p.y < 0 || p.y > H) p.vy *= -1;
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(88,166,255,0.35)';
    ctx.fill();
    for (let j = i + 1; j < particles.length; j++) {
      const q = particles[j];
      const d = Math.hypot(p.x - q.x, p.y - q.y);
      if (d < 130) {
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(q.x, q.y);
        ctx.strokeStyle = 'rgba(88,166,255,' + (0.12 * (1 - d / 130)) + ')';
        ctx.lineWidth = 1;
        ctx.stroke();
      }
    }
  }
  requestAnimationFrame(draw);
}
draw();
</script>
</body>
</html>
