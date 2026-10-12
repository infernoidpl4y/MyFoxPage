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
    transition: background-color 0.4s ease;
  }
  #bg-canvas { position: fixed; inset: 0; z-index: 0; }
  .glow { position: fixed; z-index: 0; border-radius: 50%; filter: blur(120px); opacity: var(--glow-opacity, 0.35); pointer-events: none; transition: background 0.5s ease; }
  .glow-1 { width: 500px; height: 500px; background: #1f6feb; top: -150px; left: -100px; animation: float1 18s ease-in-out infinite; }
  .glow-2 { width: 450px; height: 450px; background: #8957e5; bottom: -150px; right: -100px; animation: float2 22s ease-in-out infinite; }
  .glow-3 { width: 350px; height: 350px; background: #238636; top: 40%; left: 60%; animation: float1 25s ease-in-out infinite reverse; opacity: calc(var(--glow-opacity, 0.35) * 0.55); }
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
  .theme-fab {
    position: fixed; bottom: 24px; right: 24px; z-index: 40;
    width: 48px; height: 48px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border: none; cursor: pointer; color: #fff;
    display: grid; place-items: center;
    box-shadow: 0 4px 18px rgba(0,0,0,0.4);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
  }
  .theme-fab:hover { transform: scale(1.08) rotate(12deg); box-shadow: 0 6px 24px rgba(0,0,0,0.5); }
  .theme-panel {
    position: fixed; bottom: 84px; right: 24px; z-index: 40;
    background: var(--panel); border: 1px solid var(--panel-border);
    border-radius: 16px; padding: 16px; width: 268px;
    backdrop-filter: blur(18px);
    box-shadow: 0 10px 44px rgba(0,0,0,0.45);
    opacity: 0; visibility: hidden; transform: translateY(12px) scale(0.96);
    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
  }
  .theme-panel.open { opacity: 1; visibility: visible; transform: none; }
  .theme-panel-title {
    font-size: 0.78rem; text-transform: uppercase; letter-spacing: 1px;
    color: var(--muted); margin-bottom: 12px; font-weight: 600;
  }
  .theme-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .theme-item {
    display: flex; flex-direction: column; align-items: center; gap: 7px;
    padding: 10px 6px; border-radius: 10px; cursor: pointer;
    border: 1px solid transparent; transition: all 0.15s ease;
  }
  .theme-item:hover { background: var(--hover); }
  .theme-item.active { border-color: var(--accent); background: var(--hover); }
  .theme-swatch {
    width: 100%; height: 32px; border-radius: 8px;
  }
  .theme-name { font-size: 0.72rem; color: var(--text); }
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
      <span class="dot" style="background:var(--accent3)"></span> My Tools
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

<button class="theme-fab" id="theme-fab" title="Cambiar tema" aria-label="Cambiar tema">
  <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>
</button>
<div class="theme-panel" id="theme-panel">
  <div class="theme-panel-title">Temas</div>
  <div class="theme-grid" id="theme-grid"></div>
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

const THEMES = {
  cosmos: {
    name: 'Cosmos',
    vars: { bg: '#0d1117', panel: 'rgba(22,27,34,0.75)', panelBorder: 'rgba(88,166,255,0.15)', text: '#e6edf3', muted: '#8b949e', accent: '#58a6ff', accent2: '#bc8cff', accent3: '#3fb950', danger: '#f85149', inputBg: 'rgba(22,27,34,0.9)', hover: 'rgba(88,166,255,0.12)', glowOpacity: 0.35 },
    glows: ['#1f6feb', '#8957e5', '#238636'],
    particle: '88,166,255'
  },
  neon: {
    name: 'Neon',
    vars: { bg: '#08060f', panel: 'rgba(18,14,32,0.75)', panelBorder: 'rgba(0,240,255,0.18)', text: '#eae6ff', muted: '#8a83b0', accent: '#00f0ff', accent2: '#ff2fd6', accent3: '#7cff6b', danger: '#ff4d6d', inputBg: 'rgba(18,14,32,0.9)', hover: 'rgba(0,240,255,0.12)', glowOpacity: 0.4 },
    glows: ['#00f0ff', '#ff2fd6', '#7cff6b'],
    particle: '0,240,255'
  },
  emerald: {
    name: 'Emerald',
    vars: { bg: '#07110c', panel: 'rgba(12,26,18,0.75)', panelBorder: 'rgba(52,211,153,0.16)', text: '#e3f5ec', muted: '#7fae97', accent: '#34d399', accent2: '#22d3ee', accent3: '#a3e635', danger: '#f87171', inputBg: 'rgba(12,26,18,0.9)', hover: 'rgba(52,211,153,0.12)', glowOpacity: 0.32 },
    glows: ['#059669', '#0891b2', '#65a30d'],
    particle: '52,211,153'
  },
  crimson: {
    name: 'Crimson',
    vars: { bg: '#12070a', panel: 'rgba(28,12,16,0.75)', panelBorder: 'rgba(248,113,113,0.16)', text: '#fbe6e9', muted: '#b08288', accent: '#f87171', accent2: '#fb923c', accent3: '#fbbf24', danger: '#ef4444', inputBg: 'rgba(28,12,16,0.9)', hover: 'rgba(248,113,113,0.12)', glowOpacity: 0.34 },
    glows: ['#dc2626', '#ea580c', '#b45309'],
    particle: '248,113,113'
  },
  sunset: {
    name: 'Sunset',
    vars: { bg: '#150a08', panel: 'rgba(30,16,12,0.75)', panelBorder: 'rgba(251,146,60,0.16)', text: '#fceee3', muted: '#b58a72', accent: '#fb923c', accent2: '#f472b6', accent3: '#facc15', danger: '#f87171', inputBg: 'rgba(30,16,12,0.9)', hover: 'rgba(251,146,60,0.12)', glowOpacity: 0.34 },
    glows: ['#f97316', '#ec4899', '#eab308'],
    particle: '251,146,60'
  },
  amethyst: {
    name: 'Amethyst',
    vars: { bg: '#0f0816', panel: 'rgba(24,14,36,0.75)', panelBorder: 'rgba(168,120,255,0.16)', text: '#efe6ff', muted: '#9c86be', accent: '#a878ff', accent2: '#e879f9', accent3: '#60a5fa', danger: '#fb7185', inputBg: 'rgba(24,14,36,0.9)', hover: 'rgba(168,120,255,0.12)', glowOpacity: 0.36 },
    glows: ['#7c3aed', '#c026d3', '#2563eb'],
    particle: '168,120,255'
  },
  aurora: {
    name: 'Aurora',
    vars: { bg: '#061016', panel: 'rgba(10,24,32,0.75)', panelBorder: 'rgba(45,212,191,0.16)', text: '#dff5f1', muted: '#75a89f', accent: '#2dd4bf', accent2: '#38bdf8', accent3: '#a78bfa', danger: '#fb7185', inputBg: 'rgba(10,24,32,0.9)', hover: 'rgba(45,212,191,0.12)', glowOpacity: 0.32 },
    glows: ['#14b8a6', '#0ea5e9', '#8b5cf6'],
    particle: '45,212,191'
  },
  slate: {
    name: 'Slate',
    vars: { bg: '#0b0e13', panel: 'rgba(20,24,30,0.78)', panelBorder: 'rgba(148,163,184,0.16)', text: '#e2e8f0', muted: '#94a3b8', accent: '#94a3b8', accent2: '#cbd5e1', accent3: '#64748b', danger: '#f87171', inputBg: 'rgba(20,24,30,0.9)', hover: 'rgba(148,163,184,0.12)', glowOpacity: 0.28 },
    glows: ['#475569', '#334155', '#1e293b'],
    particle: '148,163,184'
  },
  light: {
    name: 'Light',
    vars: { bg: '#f2f5f9', panel: 'rgba(255,255,255,0.82)', panelBorder: 'rgba(15,23,42,0.08)', text: '#182230', muted: '#64748b', accent: '#2563eb', accent2: '#9333ea', accent3: '#16a34a', danger: '#dc2626', inputBg: 'rgba(255,255,255,0.92)', hover: 'rgba(37,99,235,0.08)', glowOpacity: 0.3 },
    glows: ['#93c5fd', '#d8b4fe', '#86efac'],
    particle: '100,130,200'
  }
};
let particleRGB = '88,166,255';
function applyTheme(key) {
  const t = THEMES[key] || THEMES.cosmos;
  const r = document.documentElement.style;
  r.setProperty('--bg', t.vars.bg);
  r.setProperty('--panel', t.vars.panel);
  r.setProperty('--panel-border', t.vars.panelBorder);
  r.setProperty('--text', t.vars.text);
  r.setProperty('--muted', t.vars.muted);
  r.setProperty('--accent', t.vars.accent);
  r.setProperty('--accent2', t.vars.accent2);
  r.setProperty('--accent3', t.vars.accent3);
  r.setProperty('--danger', t.vars.danger);
  r.setProperty('--input-bg', t.vars.inputBg);
  r.setProperty('--hover', t.vars.hover);
  r.setProperty('--glow-opacity', t.vars.glowOpacity);
  document.querySelector('.glow-1').style.background = t.glows[0];
  document.querySelector('.glow-2').style.background = t.glows[1];
  document.querySelector('.glow-3').style.background = t.glows[2];
  particleRGB = t.particle;
  localStorage.setItem('ff_theme', key);
  document.querySelectorAll('.theme-item').forEach(el => el.classList.toggle('active', el.dataset.key === key));
}
const themeGrid = document.getElementById('theme-grid');
Object.entries(THEMES).forEach(([key, t]) => {
  const item = document.createElement('div');
  item.className = 'theme-item';
  item.dataset.key = key;
  const sw = document.createElement('div');
  sw.className = 'theme-swatch';
  sw.style.background = 'linear-gradient(135deg, ' + t.vars.accent + ', ' + t.vars.accent2 + ')';
  sw.style.boxShadow = 'inset 0 0 0 2px ' + t.vars.bg;
  const nm = document.createElement('div');
  nm.className = 'theme-name';
  nm.textContent = t.name;
  item.append(sw, nm);
  item.onclick = () => applyTheme(key);
  themeGrid.appendChild(item);
});
const themeFab = document.getElementById('theme-fab');
const themePanel = document.getElementById('theme-panel');
themeFab.onclick = ev => { ev.stopPropagation(); themePanel.classList.toggle('open'); };
document.addEventListener('click', ev => {
  if (!themePanel.contains(ev.target) && ev.target !== themeFab) themePanel.classList.remove('open');
});
document.addEventListener('keydown', ev => {
  if (ev.key === 'Escape') themePanel.classList.remove('open');
});
applyTheme(localStorage.getItem('ff_theme') || 'cosmos');

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
    ctx.fillStyle = 'rgba(' + particleRGB + ',0.35)';
    ctx.fill();
    for (let j = i + 1; j < particles.length; j++) {
      const q = particles[j];
      const d = Math.hypot(p.x - q.x, p.y - q.y);
      if (d < 130) {
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(q.x, q.y);
        ctx.strokeStyle = 'rgba(' + particleRGB + ',' + (0.12 * (1 - d / 130)) + ')';
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
