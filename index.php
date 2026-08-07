<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = bin2hex(random_bytes(16));
}
$sessionId = $_SESSION['id'];

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action === 'stories') {
    header('Content-Type: application/json');
    $conn = connectDB();
    echo json_encode(getStories($conn));
    $conn->close();
    exit;
}

if ($action === 'scene') {
    header('Content-Type: application/json');
    $storyKey = $_GET['story'] ?? '';
    $sceneKey = $_GET['scene'] ?? 'start';
    $conn = connectDB();
    $scene = getScene($conn, $storyKey, $sceneKey);
    if (!$scene) { http_response_code(404); echo json_encode(['error'=>'Scène introuvable']); exit; }
    saveProgress($conn, $sessionId, $storyKey, $sceneKey);
    echo json_encode($scene);
    $conn->close();
    exit;
}

if ($action === 'choice' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $storyKey   = $data['story']   ?? '';
    $sceneKey   = $data['scene']   ?? '';
    $choiceLabel= $data['label']   ?? '';
    $isDeath    = $data['is_death']?? false;
    $conn = connectDB();
    logChoice($conn, $sessionId, $sceneKey, $choiceLabel);
    if ($isDeath) {
        incrementDeaths($conn, $sessionId);
        echo json_encode(['ok'=>true]);
    } else {
        $nextKey = $data['next_scene'] ?? 'start';
        if ($nextKey !== '__end__') {
            saveProgress($conn, $sessionId, $storyKey, $nextKey);
            $scene = getScene($conn, $storyKey, $nextKey);
            echo json_encode($scene ?: ['error'=>'Scène introuvable']);
        } else {
            echo json_encode(['end'=>true,'end_title'=>$data['end_title']??'Fin','end_text'=>$data['end_text']??'']);
        }
    }
    $conn->close();
    exit;
}

if ($action === 'reset') {
    $conn = connectDB();
    resetProgress($conn, $sessionId);
    $conn->close();
    session_destroy();
    header('Location: index.php');
    exit;
}

if ($action === 'stats') {
    header('Content-Type: application/json');
    $conn = connectDB();
    echo json_encode(getPlayerStats($conn, $sessionId) ?: ['death_count'=>0,'total_choices'=>0]);
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
<title>CARTEL</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Raleway:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--red:#C41E3A;--gold:#A07840;--text:#EAE6DC;--dim:#7A7570}
html,body{width:100%;height:100%;overflow:hidden;background:#000;font-family:'Raleway',sans-serif}
.screen{position:fixed;inset:0;transition:opacity .5s;z-index:10}
.screen.hidden{opacity:0;pointer-events:none}

#s-loading{background:#000;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem}
.loading-title{font-family:'Cormorant Garamond',serif;font-size:3rem;font-weight:300;color:#fff;letter-spacing:.12em;animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:.3}50%{opacity:1}}
.loading-sub{color:var(--dim);font-size:.7rem;letter-spacing:.3em;text-transform:uppercase}

#s-menu{background:linear-gradient(160deg,#06000E,#100008,#050010);display:flex;flex-direction:column;align-items:center;overflow-y:auto;padding:3rem 1.5rem 4rem}
.menu-title{font-family:'Cormorant Garamond',serif;font-size:clamp(3rem,10vw,6rem);font-weight:300;color:#fff;letter-spacing:.12em;margin-bottom:.3rem}
.menu-sub{color:var(--dim);font-size:.7rem;letter-spacing:.35em;text-transform:uppercase;margin-bottom:.6rem}
.stats-bar{color:rgba(255,255,255,.2);font-size:.65rem;letter-spacing:.2em;margin-bottom:2.5rem}
.stories-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;width:100%;max-width:900px}
.story-card{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);padding:2rem 1.5rem 1.5rem;cursor:pointer;transition:all .3s;position:relative;overflow:hidden}
.story-card::before{content:'';position:absolute;inset:0;background:var(--cc);opacity:0;transition:opacity .3s}
.story-card:hover{border-color:var(--cc);transform:translateY(-4px)}
.story-card:hover::before{opacity:.08}
.sc-num{font-size:.65rem;letter-spacing:.3em;color:var(--dim);text-transform:uppercase;margin-bottom:1.2rem}
.sc-title{font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:300;color:#fff;font-style:italic;margin-bottom:.5rem;position:relative}
.sc-tag{font-size:.6rem;letter-spacing:.2em;color:var(--cc);text-transform:uppercase;margin-bottom:1rem;position:relative}
.sc-desc{font-size:.8rem;line-height:1.7;color:var(--dim);position:relative;margin-bottom:1.5rem}
.sc-btn{font-size:.65rem;letter-spacing:.25em;text-transform:uppercase;color:var(--cc);border:1px solid var(--cc);padding:.5rem 1.2rem;background:transparent;cursor:pointer;font-family:'Raleway',sans-serif;transition:all .3s;position:relative}
.story-card:hover .sc-btn{background:var(--cc);color:#fff}

#s-game{display:flex;flex-direction:column;height:100vh;background:#000}
#game-bg{flex:0 0 52%;position:relative;overflow:hidden;background-size:cover;background-position:center;transition:background-image .8s}
#game-bg::after{content:'';position:absolute;bottom:0;left:0;right:0;height:60%;background:linear-gradient(transparent,rgba(0,0,0,.96));pointer-events:none}
#game-bg::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.42);pointer-events:none}
#game-hud{position:absolute;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:center;padding:.8rem 1rem;z-index:10}
.hud-title{font-family:'Cormorant Garamond',serif;font-size:.85rem;font-style:italic;color:rgba(255,255,255,.4);letter-spacing:.1em}
.hud-btns{display:flex;gap:.5rem}
.hud-btn{background:rgba(0,0,0,.6);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.5);padding:.35rem .7rem;font-size:.65rem;cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.1em;transition:all .2s}
.hud-btn:hover{color:#fff;border-color:rgba(255,255,255,.3)}

#chapter-card{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.92);z-index:20;opacity:0;pointer-events:none;transition:opacity .5s;flex-direction:column;text-align:center}
#chapter-card.visible{opacity:1;pointer-events:all}
.ch-num{font-size:.65rem;letter-spacing:.4em;color:var(--story-color,var(--red));text-transform:uppercase;margin-bottom:.8rem}
.ch-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,6vw,3.5rem);font-weight:300;font-style:italic;color:#fff}
.ch-line{width:0;height:1px;background:var(--story-color,var(--red));margin:.8rem auto;transition:width 1s ease .3s}
#chapter-card.visible .ch-line{width:80px}

#game-textbox{flex:1;background:linear-gradient(180deg,rgba(4,4,14,.97),rgba(2,2,8,1));border-top:1px solid rgba(255,255,255,.08);padding:1rem 1.5rem 1rem;display:flex;flex-direction:column;overflow-y:auto;gap:.5rem}
#speaker-name{font-size:.62rem;letter-spacing:.3em;text-transform:uppercase;color:var(--story-color,var(--red));font-weight:500;min-height:1em;flex-shrink:0}
#story-text{font-size:clamp(.82rem,2.2vw,.92rem);line-height:1.88;color:var(--text);white-space:pre-line;flex-shrink:0}
#choices{display:flex;flex-direction:column;gap:.5rem;flex-shrink:0;opacity:0;transition:opacity .5s;margin-top:.3rem;padding-top:.8rem;border-top:1px solid rgba(255,255,255,.06)}
#choices.visible{opacity:1}
.choice-btn{background:transparent;border:none;border-left:2px solid var(--story-color,var(--red));color:rgba(215,210,200,.8);padding:.6rem 1rem;text-align:left;cursor:pointer;font-family:'Raleway',sans-serif;font-size:.79rem;letter-spacing:.04em;transition:all .22s;display:flex;align-items:flex-start;gap:.7rem;line-height:1.5}
.choice-btn::before{content:'›';color:var(--story-color,var(--red));font-size:1rem;flex-shrink:0;margin-top:.05rem}
.choice-btn:hover{background:rgba(255,255,255,.05);padding-left:1.3rem;color:#fff}
#tap-hint{font-size:.58rem;letter-spacing:.15em;color:rgba(255,255,255,.18);text-transform:uppercase;animation:blink 1.5s infinite;text-align:right;flex-shrink:0}
@keyframes blink{0%,100%{opacity:.2}50%{opacity:.6}}

#end-card,#death-card{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:25;opacity:0;pointer-events:none;transition:opacity .8s;flex-direction:column;text-align:center;padding:2rem}
#end-card.visible,#death-card.visible{opacity:1;pointer-events:all}
#end-card{background:rgba(0,0,0,.95)}
#death-card{background:radial-gradient(ellipse at center,#1a0005,#000)}
.end-symbol{color:var(--story-color,var(--red));font-size:2rem;margin-bottom:1.5rem}
.end-title{font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,5vw,3rem);font-weight:300;font-style:italic;color:#fff;margin-bottom:.8rem}
.death-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,6vw,4rem);font-weight:300;color:#C41E3A;letter-spacing:.1em;margin-bottom:.8rem}
.end-text{color:var(--dim);font-size:.85rem;line-height:1.8;max-width:400px;margin-bottom:2rem}
.death-text{color:#8A4050;font-size:.85rem;line-height:1.8;max-width:400px;margin-bottom:2rem}
.death-skull{font-size:3rem;margin-bottom:1rem;filter:drop-shadow(0 0 20px rgba(200,0,30,.4))}
.card-btns{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center}
.btn-main{background:var(--red);color:#fff;border:none;padding:1rem 2.5rem;font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.25em;text-transform:uppercase;cursor:pointer;transition:all .3s}
.btn-main:hover{background:#E02040;transform:scale(1.03)}
.btn-ghost{background:transparent;color:rgba(255,255,255,.4);border:1px solid rgba(255,255,255,.2);padding:1rem 2.5rem;font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.25em;text-transform:uppercase;cursor:pointer;transition:all .3s}
.btn-ghost:hover{color:#fff;border-color:rgba(255,255,255,.5)}

#flash-overlay{position:fixed;inset:0;background:#8B0000;pointer-events:none;opacity:0;z-index:50}
@keyframes flash{0%,100%{opacity:0}50%{opacity:.5}}
.flash-red{animation:flash .3s ease}
@keyframes shake{0%,100%{transform:translate(0)}15%{transform:translate(-6px,-2px)}30%{transform:translate(6px,2px)}45%{transform:translate(-4px,3px)}60%{transform:translate(4px,-3px)}}
.shake{animation:shake .5s ease}
@keyframes fadeSlide{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.fade-slide{animation:fadeSlide .5s ease forwards}
#music-btn{position:fixed;bottom:1rem;left:1rem;z-index:100;background:rgba(0,0,0,.7);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.4);padding:.4rem .7rem;font-size:.6rem;cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.15em;transition:all .2s}
#music-btn:hover{color:#fff}
</style>
</head>
<body>
<div id="flash-overlay"></div>

<div id="s-loading" class="screen">
  <div class="loading-title">CARTEL</div>
  <div class="loading-sub">Chargement...</div>
</div>

<div id="s-menu" class="screen hidden">
  <h1 class="menu-title">CARTEL</h1>
  <p class="menu-sub">Trois histoires. Fais les bons choix. Ou meurs.</p>
  <p class="stats-bar" id="stats-bar"></p>
  <div class="stories-grid" id="stories-grid"></div>
</div>

<div id="s-game" class="screen hidden">
  <div id="game-bg">
    <div id="game-hud">
      <span class="hud-title" id="hud-title"></span>
      <div class="hud-btns">
        <button class="hud-btn" id="pause-btn" onclick="togglePause()">⏸</button>
        <button class="hud-btn" onclick="showMenu()">◂ Menu</button>
      </div>
    </div>
    <div id="chapter-card">
      <div class="ch-num" id="ch-num"></div>
      <div class="ch-title" id="ch-title"></div>
      <div class="ch-line"></div>
    </div>
    <div id="end-card">
      <div class="end-symbol">✦</div>
      <div class="end-title" id="end-title"></div>
      <div class="end-text" id="end-text"></div>
      <div class="card-btns">
        <button class="btn-main" onclick="showMenu()">← Autres histoires</button>
      </div>
    </div>
    <div id="death-card">
      <div class="death-skull">✝</div>
      <div class="death-title">TU ES MORTE</div>
      <div class="death-text" id="death-msg"></div>
      <div class="card-btns">
        <button class="btn-main" onclick="retryScene()">↺ Réessayer</button>
        <button class="btn-ghost" onclick="showMenu()">◂ Menu</button>
      </div>
    </div>
  </div>
  <div id="game-textbox">
    <div id="speaker-name"></div>
    <div id="story-text"></div>
    <div id="choices"></div>
    <div id="tap-hint">Toucher pour continuer ›</div>
  </div>
</div>

<button id="music-btn" onclick="toggleMusic()">♪ OFF</button>

<script>
const BG = {
  cell:          'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1920&q=90',
  mansion:       'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1920&q=90',
  warehouse:     'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1920&q=90',
  interrogation: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1920&q=90',
  night:         'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1920&q=90',
  office:        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=90',
  escape:        'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=1920&q=90',
  blood:         'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1920&q=90',
};

let currentStory = null, currentScene = null, lastSceneKey = 'start';
let paused = false, typing = false, typeTimer = null, pausedText = '', pausedIdx = 0, pausedChoices = null;
let audioCtx = null, musicOn = false, musicInterval = null;

async function fetchStories() {
  const r = await fetch('index.php?action=stories');
  return await r.json();
}

async function fetchScene(storyKey, sceneKey) {
  const r = await fetch(`index.php?action=scene&story=${storyKey}&scene=${sceneKey}`);
  return await r.json();
}

async function sendChoice(data) {
  const r = await fetch('index.php?action=choice', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify(data)
  });
  return await r.json();
}

async function fetchStats() {
  const r = await fetch('index.php?action=stats');
  return await r.json();
}

async function initMenu() {
  const [stories, stats] = await Promise.all([fetchStories(), fetchStats()]);
  const grid = document.getElementById('stories-grid');
  grid.innerHTML = '';
  stories.forEach((s, i) => {
    grid.innerHTML += `
      <div class="story-card" style="--cc:${s.color}" onclick="startStory('${s.story_key}','${s.color}')">
        <div class="sc-num">Histoire 0${i+1}</div>
        <h2 class="sc-title">${s.title}</h2>
        <p class="sc-tag">${s.tag || ''}</p>
        <p class="sc-desc">${s.description || ''}</p>
        <button class="sc-btn">Jouer</button>
      </div>`;
  });
  if (stats) {
    const bar = document.getElementById('stats-bar');
    bar.textContent = stats.death_count > 0
      ? `${stats.death_count} mort${stats.death_count > 1 ? 's' : ''} · ${stats.total_choices || 0} choix effectués`
      : '';
  }
  document.getElementById('s-loading').classList.add('hidden');
  document.getElementById('s-menu').classList.remove('hidden');
}

async function startStory(storyKey, color) {
  currentStory = storyKey;
  document.documentElement.style.setProperty('--story-color', color);
  document.getElementById('hud-title').textContent = storyKey.toUpperCase();
  document.getElementById('s-menu').classList.add('hidden');
  document.getElementById('s-game').classList.remove('hidden');
  document.getElementById('end-card').classList.remove('visible');
  document.getElementById('death-card').classList.remove('visible');
  const scene = await fetchScene(storyKey, 'start');
  renderScene(scene);
}

function renderScene(scene) {
  if (!scene || scene.error) return;
  lastSceneKey = scene.scene_key;
  const bg = document.getElementById('game-bg');
  bg.style.backgroundImage = `linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('${BG[scene.bg] || BG.night}')`;
  bg.style.backgroundSize = 'cover';
  bg.style.backgroundPosition = 'center';
  if (scene.shake) { setTimeout(()=>bg.classList.add('shake'),100); setTimeout(()=>bg.classList.remove('shake'),700); }
  if (scene.flash) flashRed();
  if (scene.chapter) showChapter(scene.chapter, scene.chapter_title || '');
  document.getElementById('speaker-name').textContent = scene.speaker || '';
  document.getElementById('choices').innerHTML = '';
  document.getElementById('choices').classList.remove('visible');
  document.getElementById('tap-hint').style.display = 'block';
  document.getElementById('game-textbox').classList.remove('fade-slide');
  void document.getElementById('game-textbox').offsetWidth;
  document.getElementById('game-textbox').classList.add('fade-slide');
  const delay = scene.chapter ? 2800 : 0;
  setTimeout(() => typeText(scene.text_content, scene.choices), delay);
}

function showChapter(n, t) {
  const c = document.getElementById('chapter-card');
  document.getElementById('ch-num').textContent = 'Chapitre ' + n;
  document.getElementById('ch-title').textContent = t;
  c.classList.add('visible');
  setTimeout(() => c.classList.remove('visible'), 2500);
}

function flashRed() {
  const f = document.getElementById('flash-overlay');
  f.classList.add('flash-red');
  setTimeout(() => f.classList.remove('flash-red'), 400);
}

function typeText(text, choices) {
  const el = document.getElementById('story-text');
  el.textContent = '';
  typing = true; pausedText = text; pausedIdx = 0; pausedChoices = choices;
  function next() {
    if (paused) return;
    if (!typing) { el.textContent = text; document.getElementById('tap-hint').style.display='none'; showChoices(choices); return; }
    if (pausedIdx < text.length) {
      el.textContent += text[pausedIdx++];
      const ch = text[pausedIdx-1];
      const d = ch==='.'||ch==='!'||ch==='?' ? 130 : ch===','||ch===';' ? 70 : ch==='\n' ? 50 : 22;
      typeTimer = setTimeout(next, d);
    } else {
      typing = false;
      document.getElementById('tap-hint').style.display = 'none';
      showChoices(choices);
    }
  }
  next();
}

function skipTyping() {
  if (typing && !paused) {
    typing = false; clearTimeout(typeTimer);
    document.getElementById('story-text').textContent = pausedText;
    document.getElementById('tap-hint').style.display = 'none';
    showChoices(pausedChoices);
  }
}

function togglePause() {
  paused = !paused;
  document.getElementById('pause-btn').textContent = paused ? '▶' : '⏸';
  if (paused) { clearTimeout(typeTimer); if (musicOn) clearInterval(musicInterval); }
  else {
    if (musicOn) { playChord(); musicInterval = setInterval(playChord, 7000); }
    if (typing) resumeType();
  }
}

function resumeType() {
  const el = document.getElementById('story-text');
  function next() {
    if (paused) return;
    if (!typing) { showChoices(pausedChoices); return; }
    if (pausedIdx < pausedText.length) {
      el.textContent += pausedText[pausedIdx++];
      const ch = pausedText[pausedIdx-1];
      const d = ch==='.'||ch==='!'||ch==='?' ? 130 : ch===','||ch===';' ? 70 : ch==='\n' ? 50 : 22;
      typeTimer = setTimeout(next, d);
    } else { typing = false; document.getElementById('tap-hint').style.display='none'; showChoices(pausedChoices); }
  }
  next();
}

function showChoices(choices) {
  if (!choices || !choices.length) return;
  const el = document.getElementById('choices');
  el.innerHTML = '';
  choices.forEach(c => {
    const btn = document.createElement('button');
    btn.className = 'choice-btn';
    btn.textContent = c.label;
    btn.onclick = () => handleChoice(c);
    el.appendChild(btn);
  });
  el.classList.add('visible');
}

async function handleChoice(c) {
  document.getElementById('choices').classList.remove('visible');
  const result = await sendChoice({
    story: currentStory,
    scene: lastSceneKey,
    label: c.label,
    is_death: !!c.is_death,
    next_scene: c.next_scene,
    end_title: c.end_title,
    end_text: c.end_text
  });
  if (c.is_death) {
    document.getElementById('death-msg').textContent = c.death_msg || 'Tu n\'aurais pas dû.';
    document.getElementById('death-card').classList.add('visible');
  } else if (result.end) {
    document.getElementById('end-title').textContent = result.end_title || 'Fin';
    document.getElementById('end-text').textContent = result.end_text || '';
    document.getElementById('end-card').classList.add('visible');
  } else if (result.error) {
    console.error(result.error);
  } else {
    renderScene(result);
  }
}

function retryScene() {
  document.getElementById('death-card').classList.remove('visible');
  fetchScene(currentStory, lastSceneKey).then(renderScene);
}

async function showMenu() {
  document.getElementById('s-game').classList.add('hidden');
  document.getElementById('end-card').classList.remove('visible');
  document.getElementById('death-card').classList.remove('visible');
  document.getElementById('chapter-card').classList.remove('visible');
  document.getElementById('s-menu').classList.remove('hidden');
  const stats = await fetchStats();
  if (stats && stats.death_count > 0) {
    document.getElementById('stats-bar').textContent =
      `${stats.death_count} mort${stats.death_count>1?'s':''} · ${stats.total_choices||0} choix effectués`;
  }
}

function initAudio() { if (audioCtx) return; audioCtx = new (window.AudioContext||window.webkitAudioContext)(); }
function playNote(f,t,d,v=.07) {
  const o=audioCtx.createOscillator(),g=audioCtx.createGain(),fi=audioCtx.createBiquadFilter();
  fi.type='lowpass';fi.frequency.value=1100;o.type='triangle';o.frequency.value=f;
  g.gain.setValueAtTime(0,t);g.gain.linearRampToValueAtTime(v,t+.04);g.gain.exponentialRampToValueAtTime(.001,t+d);
  o.connect(fi);fi.connect(g);g.connect(audioCtx.destination);o.start(t);o.stop(t+d+.1);
}
const PROGS=[[110,138.59,164.81,220,261.63],[98,123.47,146.83,196,246.94],[116.54,146.83,174.61,233.08]];
function playChord() {
  if(!audioCtx||!musicOn||paused)return;
  const p=PROGS[Math.floor(Math.random()*PROGS.length)],now=audioCtx.currentTime;
  [...p].sort(()=>Math.random()-.5).forEach((f,i)=>{
    const off=i*.4+Math.random()*.2;
    playNote(f,now+off,5+Math.random()*3);
    if(Math.random()>.5)playNote(f*2,now+off+.8,3,.025);
    if(i===0){setTimeout(()=>{const tb=document.getElementById('game-textbox');tb.style.borderTopColor='rgba(255,255,255,.14)';setTimeout(()=>tb.style.borderTopColor='rgba(255,255,255,.08)',400)},off*1000);}
  });
}
function toggleMusic() {
  initAudio(); musicOn=!musicOn;
  document.getElementById('music-btn').textContent=musicOn?'♪ ON':'♪ OFF';
  if(musicOn){playChord();musicInterval=setInterval(playChord,7000);}else clearInterval(musicInterval);
}

document.getElementById('s-game').addEventListener('click', e => {
  if (!['choice-btn','hud-btn','btn-main','btn-ghost'].some(c=>e.target.classList.contains(c))) skipTyping();
});

initMenu();
</script>
</body>
</html>
