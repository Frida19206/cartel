<?php /* CARTEL - Visual Novel PHP */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>CARTEL — Histoires Interactives 18+</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Raleway:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --red:#C41E3A;--red-dark:#8B1225;--gold:#A07840;
  --text:#EAE6DC;--dim:#7A7570;--black:#05050A;
  --blue:#1A2A5A;--blue2:#0D1A40;
}
html,body{width:100%;height:100%;overflow:hidden;background:#000;font-family:'Raleway',sans-serif}

/* ─── SCREENS ─── */
.screen{position:fixed;inset:0;transition:opacity .6s;z-index:10}
.screen.hidden{opacity:0;pointer-events:none}

/* ─── WARNING ─── */
#s-warning{
  background:linear-gradient(160deg,#06000E 0%,#100008 60%,#050010 100%);
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  text-align:center;padding:2rem;
}
.warn-title{
  font-family:'Cormorant Garamond',serif;font-size:clamp(3rem,10vw,6rem);
  font-weight:300;color:#fff;letter-spacing:.08em;margin-bottom:.3rem;
}
.warn-sub{color:var(--red);font-size:.75rem;letter-spacing:.4em;text-transform:uppercase;margin-bottom:3rem}
.warn-line{width:60px;height:1px;background:var(--red);margin:.8rem auto}
.warn-text{color:var(--dim);font-size:.85rem;line-height:1.8;max-width:340px;margin-bottom:2.5rem}
.warn-18{
  width:72px;height:72px;border:2px solid var(--red);border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  color:var(--red);font-size:1.4rem;font-weight:600;margin:0 auto 2.5rem;
}
.btn-main{
  background:var(--red);color:#fff;border:none;
  padding:1rem 3rem;font-family:'Raleway',sans-serif;font-size:.8rem;
  letter-spacing:.25em;text-transform:uppercase;cursor:pointer;
  transition:background .3s, transform .2s;
}
.btn-main:hover{background:#E02040;transform:scale(1.03)}

/* ─── MENU ─── */
#s-menu{
  background:linear-gradient(160deg,#06000E 0%,#0D0515 60%,#050010 100%);
  display:flex;flex-direction:column;align-items:center;overflow-y:auto;padding:3rem 1.5rem 4rem;
}
.menu-title{
  font-family:'Cormorant Garamond',serif;font-size:clamp(2.5rem,8vw,5rem);
  font-weight:300;color:#fff;letter-spacing:.12em;margin-bottom:.3rem;
}
.menu-sub{color:var(--dim);font-size:.7rem;letter-spacing:.35em;text-transform:uppercase;margin-bottom:3.5rem}
.stories-grid{
  display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:1.5rem;width:100%;max-width:900px;
}
.story-card{
  background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);
  padding:2rem 1.5rem 1.5rem;cursor:pointer;
  transition:all .3s;position:relative;overflow:hidden;
}
.story-card::before{
  content:'';position:absolute;inset:0;background:var(--card-color);
  opacity:0;transition:opacity .3s;
}
.story-card:hover{border-color:var(--card-color);transform:translateY(-4px)}
.story-card:hover::before{opacity:.08}
.story-num{
  font-size:.65rem;letter-spacing:.3em;color:var(--dim);text-transform:uppercase;margin-bottom:1.2rem
}
.story-card-title{
  font-family:'Cormorant Garamond',serif;font-size:2rem;font-weight:300;
  color:#fff;font-style:italic;margin-bottom:.5rem;position:relative;
}
.story-card-tag{
  font-size:.6rem;letter-spacing:.2em;color:var(--card-color);
  text-transform:uppercase;margin-bottom:1rem;position:relative;
}
.story-card-desc{
  font-size:.8rem;line-height:1.7;color:var(--dim);position:relative;margin-bottom:1.5rem
}
.story-card-btn{
  font-size:.65rem;letter-spacing:.25em;text-transform:uppercase;
  color:var(--card-color);border:1px solid var(--card-color);
  padding:.5rem 1.2rem;background:transparent;cursor:pointer;
  font-family:'Raleway',sans-serif;transition:all .3s;position:relative;
}
.story-card:hover .story-card-btn{background:var(--card-color);color:#fff}

/* ─── GAME ─── */
#s-game{display:flex;flex-direction:column;background:#000}

#game-bg{
  flex:1;position:relative;overflow:hidden;
  transition:background 1.2s ease, box-shadow 1.2s ease;
}
#game-bg::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:50%;
  background:linear-gradient(transparent,rgba(0,0,0,.85));
  pointer-events:none;
}

/* BG types */
.bg-cell{
  background:
    repeating-linear-gradient(90deg,transparent 0px,transparent 46px,rgba(0,0,0,.7) 46px,rgba(0,0,0,.7) 50px),
    linear-gradient(180deg,#12090A 0%,#0A0508 100%);
}
.bg-cell::before{
  content:'';position:absolute;top:20%;left:50%;transform:translateX(-50%);
  width:120px;height:160px;border:3px solid rgba(255,255,255,.06);
  box-shadow:inset 0 0 30px rgba(0,0,0,.8);
}
.bg-mansion{
  background:linear-gradient(160deg,#0A0514 0%,#14082A 60%,#080A18 100%);
  box-shadow:inset 0 0 150px rgba(80,20,100,.2);
}
.bg-mansion::before{
  content:'';position:absolute;bottom:25%;left:50%;transform:translateX(-50%);
  width:60%;height:1px;background:linear-gradient(90deg,transparent,rgba(160,120,64,.2),transparent);
}
.bg-interrogation{
  background:radial-gradient(ellipse 25% 60% at 50% 35%,rgba(220,190,100,.12) 0%,rgba(180,150,60,.04) 30%,transparent 70%),#030303;
}
.bg-interrogation::before{
  content:'';position:absolute;top:0;left:50%;transform:translateX(-50%);
  width:200px;height:300px;
  background:linear-gradient(180deg,rgba(220,190,100,.06) 0%,transparent 100%);
}
.bg-night{
  background:linear-gradient(180deg,#020810 0%,#060D1E 50%,#030608 100%);
}
.bg-night::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(2px 2px at 20% 30%,rgba(255,255,255,.4) 0%,transparent 100%),
             radial-gradient(2px 2px at 60% 20%,rgba(255,255,255,.3) 0%,transparent 100%),
             radial-gradient(1px 1px at 75% 45%,rgba(255,255,255,.25) 0%,transparent 100%),
             radial-gradient(2px 2px at 35% 60%,rgba(255,200,100,.2) 0%,transparent 100%),
             radial-gradient(1px 1px at 85% 25%,rgba(255,255,255,.3) 0%,transparent 100%);
}
.bg-warehouse{
  background:linear-gradient(180deg,#080808 0%,#0E0C06 100%);
  box-shadow:inset 0 0 200px rgba(0,0,0,.9);
}
.bg-warehouse::before{
  content:'';position:absolute;top:0;left:0;right:0;height:40%;
  background:repeating-linear-gradient(180deg,transparent 0px,transparent 30px,rgba(255,255,255,.015) 30px,rgba(255,255,255,.015) 32px);
}
.bg-office{
  background:linear-gradient(135deg,#06080E 0%,#0A0E18 100%);
}
.bg-blood{
  background:radial-gradient(ellipse at center,#1A0208 0%,#080005 60%,#030003 100%);
  box-shadow:inset 0 0 100px rgba(180,0,20,.1);
}
.bg-escape{
  background:linear-gradient(180deg,#040A06 0%,#080E0A 100%);
}

/* Shake animation */
@keyframes shake{
  0%,100%{transform:translate(0)}10%{transform:translate(-6px,-2px)}
  20%{transform:translate(6px,2px)}30%{transform:translate(-4px,3px)}
  40%{transform:translate(4px,-3px)}50%{transform:translate(-3px,1px)}
  60%{transform:translate(3px,2px)}70%{transform:translate(-2px,-1px)}
  80%{transform:translate(2px,1px)}90%{transform:translate(-1px,2px)}
}
.shake{animation:shake .5s ease}

/* Flash */
@keyframes flash-red{0%,100%{opacity:0}50%{opacity:.4}}
#flash-overlay{
  position:absolute;inset:0;background:#8B0000;pointer-events:none;
  opacity:0;z-index:5;
}
.flash-red{animation:flash-red .3s ease}

/* Slide in */
@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
.slide-in{animation:slideIn .5s cubic-bezier(.25,.46,.45,.94) forwards}

/* ─── TEXTBOX ─── */
#game-textbox{
  background:linear-gradient(180deg,rgba(5,5,15,.92) 0%,rgba(3,3,10,.98) 100%);
  border-top:1px solid rgba(255,255,255,.06);
  padding:1.2rem 1.5rem 1rem;min-height:220px;
  display:flex;flex-direction:column;position:relative;
}
#speaker-name{
  font-size:.65rem;letter-spacing:.3em;text-transform:uppercase;
  color:var(--story-color,var(--red));margin-bottom:.6rem;font-weight:500;
  min-height:1.2em;
}
#story-text{
  font-size:clamp(.85rem,2.5vw,.95rem);line-height:1.85;color:var(--text);
  flex:1;white-space:pre-line;min-height:80px;
}
#choices{
  display:flex;flex-direction:column;gap:.6rem;margin-top:1rem;
  opacity:0;transition:opacity .4s;
}
#choices.visible{opacity:1}
.choice-btn{
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);
  color:var(--text);padding:.75rem 1rem;text-align:left;cursor:pointer;
  font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.05em;
  border-left:2px solid var(--story-color,var(--red));
  transition:all .25s;display:flex;align-items:center;gap:.8rem;
}
.choice-btn::before{content:'›';color:var(--story-color,var(--red));font-size:1.1rem}
.choice-btn:hover{
  background:rgba(255,255,255,.08);border-color:var(--story-color,var(--red));
  transform:translateX(4px);
}
#tap-hint{
  position:absolute;bottom:.8rem;right:1rem;
  font-size:.6rem;letter-spacing:.15em;color:rgba(255,255,255,.2);
  text-transform:uppercase;animation:blink 1.5s infinite;
}
@keyframes blink{0%,100%{opacity:.2}50%{opacity:.6}}

/* ─── HUD ─── */
#game-hud{
  position:absolute;top:0;left:0;right:0;
  display:flex;justify-content:space-between;align-items:center;
  padding:.8rem 1rem;z-index:10;
}
.hud-story-title{
  font-family:'Cormorant Garamond',serif;font-size:.85rem;font-style:italic;
  color:rgba(255,255,255,.4);letter-spacing:.1em;
}
.hud-btn{
  background:rgba(0,0,0,.5);border:1px solid rgba(255,255,255,.1);
  color:rgba(255,255,255,.5);padding:.35rem .7rem;font-size:.65rem;
  cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.1em;
  transition:all .2s;
}
.hud-btn:hover{color:#fff;border-color:rgba(255,255,255,.3)}

/* ─── CHAPTER CARD ─── */
#chapter-card{
  position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  background:rgba(0,0,0,.9);z-index:20;opacity:0;pointer-events:none;
  transition:opacity .5s;flex-direction:column;text-align:center;
}
#chapter-card.visible{opacity:1;pointer-events:all}
#chapter-card .ch-num{
  font-size:.65rem;letter-spacing:.4em;color:var(--story-color,var(--red));
  text-transform:uppercase;margin-bottom:.8rem;
}
#chapter-card .ch-title{
  font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,6vw,3.5rem);
  font-weight:300;font-style:italic;color:#fff;
}
#chapter-card .ch-line{
  width:0;height:1px;background:var(--story-color,var(--red));
  margin:.8rem auto;transition:width 1s ease .3s;
}
#chapter-card.visible .ch-line{width:80px}

/* music btn */
#music-btn{
  position:fixed;bottom:1rem;left:1rem;z-index:100;
  background:rgba(0,0,0,.7);border:1px solid rgba(255,255,255,.1);
  color:rgba(255,255,255,.4);padding:.4rem .7rem;font-size:.6rem;
  cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.15em;
  transition:all .2s;
}
#music-btn:hover{color:#fff}

/* End card */
#end-card{
  position:absolute;inset:0;display:flex;align-items:center;justify-content:center;
  background:rgba(0,0,0,.95);z-index:25;opacity:0;pointer-events:none;
  transition:opacity .8s;flex-direction:column;text-align:center;padding:2rem;
}
#end-card.visible{opacity:1;pointer-events:all}
.end-symbol{color:var(--story-color,var(--red));font-size:2rem;margin-bottom:1.5rem}
.end-title{
  font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,5vw,3rem);
  font-weight:300;font-style:italic;color:#fff;margin-bottom:.8rem;
}
.end-text{color:var(--dim);font-size:.85rem;line-height:1.8;max-width:400px;margin-bottom:2rem}
</style>
</head>
<body>

<!-- FLASH -->
<div id="flash-overlay"></div>

<!-- WARNING SCREEN -->
<div id="s-warning" class="screen">
  <div class="warn-18">18+</div>
  <h1 class="warn-title">CARTEL</h1>
  <p class="warn-sub">Histoires Interactives</p>
  <div class="warn-line"></div>
  <p class="warn-text">
    Ce contenu est réservé aux adultes.<br>
    Violence, torture, enlèvement, thèmes sombres.<br>
    Œuvre de fiction destinée aux plus de 18 ans.
  </p>
  <button class="btn-main" onclick="enterSite()">J'ai 18 ans ou plus — Entrer</button>
</div>

<!-- MENU SCREEN -->
<div id="s-menu" class="screen hidden">
  <h1 class="menu-title">CARTEL</h1>
  <p class="menu-sub">Trois histoires. Un seul monde.</p>
  <div class="stories-grid">

    <div class="story-card" style="--card-color:#C41E3A" onclick="startStory('captive')">
      <div class="story-num">Histoire 01</div>
      <h2 class="story-card-title">CAPTIVE</h2>
      <p class="story-card-tag">★ Kidnapping • Survie • Tension</p>
      <p class="story-card-desc">Elle s'est réveillée dans une pièce qu'elle ne connaît pas. Ses ravisseurs ne l'ont pas tuée — ce qui signifie qu'ils ont besoin d'elle. Elle doit trouver pourquoi. Et sortir avant qu'ils changent d'avis.</p>
      <button class="story-card-btn">Jouer cette histoire</button>
    </div>

    <div class="story-card" style="--card-color:#8B5A00" onclick="startStory('interrogation')">
      <div class="story-num">Histoire 02</div>
      <h2 class="story-card-title">SANG FROID</h2>
      <p class="story-card-tag">★ Interrogatoire • Pression • Moral</p>
      <p class="story-card-desc">48 heures. Un prisonnier. Une information qui peut tout changer. Damián a les outils, le temps, et les méthodes. La question n'est pas s'il obtiendra la réponse — mais ce qu'il sera prêt à faire pour l'avoir.</p>
      <button class="story-card-btn">Jouer cette histoire</button>
    </div>

    <div class="story-card" style="--card-color:#1A6B8B" onclick="startStory('journalist')">
      <div class="story-num">Histoire 03</div>
      <h2 class="story-card-title">LA VÉRITÉ SAIGNE</h2>
      <p class="story-card-tag">★ Enquête • Danger • Découverte</p>
      <p class="story-card-desc">Elena Moreau pensait enquêter sur des disparitions. Elle a trouvé quelque chose de bien plus profond — et bien plus sanglant. Maintenant quelqu'un sait qu'elle sait. Et le compte à rebours a commencé.</p>
      <button class="story-card-btn">Jouer cette histoire</button>
    </div>

  </div>
</div>

<!-- GAME SCREEN -->
<div id="s-game" class="screen hidden">
  <div id="game-bg">
    <div id="game-hud">
      <span class="hud-story-title" id="hud-title"></span>
      <div style="display:flex;gap:.5rem">
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
      <button class="btn-main" onclick="showMenu()">← Choisir une autre histoire</button>
    </div>
  </div>
  <div id="game-textbox">
    <div id="speaker-name"></div>
    <div id="story-text"></div>
    <div id="choices"></div>
    <div id="tap-hint">Toucher pour continuer</div>
  </div>
</div>

<button id="music-btn" onclick="toggleMusic()">♪ Musique</button>

<script>
// ════════════════════════════════════════════════════
// STORY DATA
// ════════════════════════════════════════════════════
const STORIES = {

captive: {
  title: 'CAPTIVE',
  color: '#C41E3A',
  scenes: {

    start: {
      bg:'cell', chapter:'I', chTitle:'Le Réveil', speaker:null,
      text:`Douleur.

C'est la première chose. Avant la lumière, avant les sons — la douleur sourde à l'arrière du crâne comme l'écho d'un impact.

Tu ouvres les yeux. Le plafond est en béton brut, haut et froid. L'air sent le renfermé et quelque chose d'autre — du métal. Du sang, peut-être. Le tien.

Tu es allongée sur un lit de camp. Tes mains ne sont pas attachées.

Pas encore.`,
      choices:[
        {label:"Rester immobile. Analyser la pièce.", next:'cap_listen'},
        {label:"Tenter de te lever immédiatement.", next:'cap_stand'}
      ]
    },

    cap_listen: {
      bg:'cell', speaker:'SARA',
      text:`Tu contrôles ta respiration. Tu fais semblant de dormir.

Des pas dehors. Lourds, réguliers — un garde qui fait sa ronde. Tu comptes : sept minutes entre chaque passage.

La pièce : quatre mètres sur six, peut-être. Une fenêtre haute avec des barreaux épais. À travers les barreaux — des arbres. La nuit. Tu es loin de la ville. Loin de tout.

Une porte en métal massif. Verrouillée de l'extérieur.

Ils t'ont enlevée. Ils ne t'ont pas tuée. Ce qui signifie qu'ils ont besoin de quelque chose que tu as — ou que tu sais.

Réfléchis, Sara.`,
      choices:[
        {label:"Inspecter chaque centimètre de la pièce.", next:'cap_inspect'},
        {label:"Attendre. Voir qui vient.", next:'cap_wait'}
      ]
    },

    cap_stand: {
      bg:'cell', speaker:'SARA', shake:true,
      text:`Tu te redresses trop vite.

La douleur explose dans ton crâne. Tu dois t'appuyer contre le mur pour ne pas tomber. Du sang frais sur tes doigts quand tu portes la main à ta nuque — une coupure, pas profonde mais récente.

Ils t'ont frappée. Ou tu es tombée. Ou les deux.

Par la fenêtre à barreaux tu vois des pins, une route en gravier, et la nuit profonde. Aucune lumière à l'horizon. Tu n'es plus en ville.

La porte s'ouvre.`,
      choices:[
        {label:"Reculer. Montrer ta blessure.", next:'cap_matteo'},
        {label:"Rester debout. Ne pas plier.", next:'cap_matteo_defiant'}
      ]
    },

    cap_inspect: {
      bg:'cell', speaker:'SARA',
      text:`Tu travailles méthodiquement, sans bruit.

Le lit est boulonné au sol. Les barreaux sont soudés, pas vissés — impossible à démonter à mains nues. Mais sous le lit, dans l'angle mort de la caméra que tu as repérée dans le coin supérieur gauche, il y a quelque chose.

Un éclat de métal. Un fragment de l'ancien verrou, peut-être. À peine cinq centimètres, mais le bord est tranchant.

Tu le glisses dans ta manche.

C'est en ce moment que la porte s'ouvre.`,
      choices:[
        {label:"Faire face à celui qui entre.", next:'cap_matteo_weapon'},
        {label:"Feindre de dormir.", next:'cap_matteo'}
      ]
    },

    cap_wait: {bg:'cell', speaker:null, text:`Tu attends.

Vingt minutes. Peut-être trente.

Et puis — des pas différents. Plus lents. Pas le garde. Quelqu'un d'autre. Quelqu'un qui n'est pas pressé, parce qu'il sait que tu n'as nulle part où aller.

Le verrou claque. La porte s'ouvre.

La lumière du couloir découpe la silhouette d'un homme dans l'encadrement.

Grand. Épaules larges. Un dossier sous le bras.

Il s'arrête sur le seuil et te regarde — comme s'il évaluait quelque chose.`, choices:[{label:"Voir son visage.",next:'cap_matteo'}]},

    cap_matteo: {
      bg:'mansion', speaker:'MATTEO',
      text:`Il entre. Il pose le dossier sur l'unique chaise de la pièce et reste debout — il ne s'assoie pas, ne s'approche pas. Il maintient la distance.

Son visage est celui d'un homme qui a pris des décisions difficiles toute sa vie et qui a appris à vivre avec.

— Sara Navarro. Vingt-six ans. Fille de Carlos Navarro, PDG de NarraCorp. Tu travailles pour ton père depuis deux ans — accès aux serveurs principaux, aux contrats, aux virements.

Il pose une photo sur le lit. Toi, dans ton bureau. Prise de loin, il y a quelques semaines.

— Tu as quelque chose que nous voulons. Donne-le nous. Tu rentres chez toi.

Un silence.

— Ou tu ne rentres pas.`,
      choices:[
        {label:'"Qui vous a dit que j\'avais accès aux serveurs ?"', next:'cap_probe'},
        {label:'"Allez vous faire foutre."', next:'cap_defiance'}
      ]
    },

    cap_matteo_defiant: {
      bg:'cell', speaker:'MATTEO', flash:true,
      text:`Il entre et s'arrête net en te voyant debout, la mâchoire serrée, du sang séché dans le cou.

Quelque chose traverse ses yeux — pas de l'admiration. De la réévaluation.

— Intéressant, dit-il simplement.

Il s'avance d'un pas. Tu ne recules pas. Il s'arrête à un mètre.

— La plupart des gens pleurent. Ou supplient. Ou les deux.

Il pose un dossier sur le lit sans te quitter des yeux.

— Sara Navarro. Vingt-six ans. Fille de Carlos Navarro. Tu as les codes d'accès aux serveurs de NarraCorp.

Il incline légèrement la tête.

— Donne-les nous. Et tout ça s'arrête.`,
      choices:[
        {label:'"Vous ne savez pas à qui vous avez affaire."', next:'cap_probe'},
        {label:'"Jamais."', next:'cap_defiance'}
      ]
    },

    cap_matteo_weapon: {
      bg:'cell', speaker:'MATTEO', shake:true, flash:true,
      text:`L'homme entre. Tu attends qu'il soit à deux mètres.

Alors tu frappes.

L'éclat de métal dans ta manche. Tu vises sa gorge. Il réagit une demi-seconde avant toi — sa main droite bloque ton bras avec une efficacité mécanique, et en une rotation, tu te retrouves le dos au mur, son avant-bras contre ta poitrine.

Il n'a pas l'air surpris. Pas même irrité.

— Bien essayé, dit-il. Sa voix est calme, presque appréciative.

L'éclat de métal tombe sur le sol. Il le ramasse, le regarde une seconde, et le glisse dans sa poche.

— Maintenant qu'on sait que tu n'es pas du genre à attendre passivement... parlons.`,
      choices:[{label:"Écouter ce qu'il a à dire.", next:'cap_matteo'}]
    },

    cap_probe: {
      bg:'mansion', speaker:'MATTEO',
      text:`Il ne répond pas immédiatement. Il s'assoie sur la chaise, croise les jambes — décontracté, ou simulant de l'être.

— Tu veux savoir qui parle. C'est intelligent. Ça veut dire que tu cherches les angles.

Il te regarde.

— Quelqu'un de proche de ton père. Quelqu'un qui a décidé que les avantages de notre collaboration valaient plus que sa loyauté familiale.

Il laisse ça poser.

— Tu as des codes. Ton père a des actifs. Nous avons besoin d'un transfert qui reste... discret. En échange, NarraCorp continue à exister et toi avec.

Trahison. Quelqu'un dans le cercle intime de ton père a vendu l'opération.

Qui ?`,
      choices:[
        {label:'"Je veux parler à mon père d\'abord."', next:'cap_father'},
        {label:"Feindre d'accepter pour gagner du temps.", next:'cap_feint'}
      ]
    },

    cap_defiance: {
      bg:'cell', speaker:'MATTEO', flash:true,
      text:`Il ne réagit pas comme tu l'espérais — pas de colère, pas de violence immédiate.

Il hoche simplement la tête, comme si ta réponse confirmait quelque chose qu'il savait déjà.

— D'accord.

Il se lève, ramasse son dossier.

— Tu as faim ? On t'apportera de l'eau dans une heure.

Il sort. La porte se referme.

Ce silence est pire que des cris.

Tu restes seule avec le bruit de ton cœur et la compréhension croissante que cet homme a fait ça avant. Qu'il sait exactement combien de temps les gens résistent avant de changer d'avis.

La nuit va être longue.`,
      choices:[{label:"Tenir bon.", next:'cap_night'},{label:"Réfléchir à une autre stratégie.", next:'cap_feint'}]
    },

    cap_father: {
      bg:'mansion', speaker:'MATTEO',
      text:`Il sort son téléphone. Sans un mot, il compose un numéro et te tend l'appareil.

La sonnerie. Une fois. Deux fois.

— Sara ?

La voix de ton père. Épuisée, brisée d'une façon que tu ne lui as jamais entendue.

— Papa... je...

— Fais ce qu'ils disent. S'il te plaît. Je t'en supplie.

La ligne coupe.

Matteo reprend le téléphone. Il n'a pas l'air satisfait, ni cruel. Il a l'air d'un homme qui fait son travail.

— Il sait que tu es en sécurité pour l'instant. La suite dépend de toi.`,
      choices:[
        {label:"Donner les codes.", next:'cap_end_give'},
        {label:"Négocier des conditions.", next:'cap_negotiate'}
      ]
    },

    cap_feint: {
      bg:'cell', speaker:'SARA',
      text:`— D'accord, tu dis. Je vais vous donner ce que vous voulez.

Matteo te regarde longtemps. Trop longtemps. Il sait que tu mens — ou il se demande si tu mens — et cette incertitude est ton seule avantage.

— Bien, dit-il enfin.

Mais il ne te donne pas accès à un ordinateur. Il ne te libère pas. Il appelle un garde, murmure quelque chose, et la porte se referme.

Ils vont attendre. Vérifier. Et pendant ce temps...

Tu as un fragment de métal caché. Tu as compté les rondes du garde. Tu as repéré la caméra.

La nuit n'est pas finie.`,
      choices:[{label:"Tenter une évasion cette nuit.", next:'cap_escape'},{label:"Attendre le bon moment.", next:'cap_night'}]
    },

    cap_night: {
      bg:'cell', speaker:'SARA',
      text:`3h17 du matin.

La ronde du garde vient de passer. Sept minutes.

Tu as desserré le boulon du pied de lit pendant les dernières heures — lentement, millimètre par millimètre, dans les moments où la caméra balayait l'autre angle.

Le métal est dans ta main. Pas une arme terrible. Mais une arme.

Ton cœur bat trop fort. Tu respires trop vite. Tu dois contrôler ça.

Quatre secondes pour inspirer. Quatre pour retenir. Quatre pour expirer.

OK.

La porte a un angle mort si tu te positionnes à gauche quand elle s'ouvre.

Quand le garde revient dans sept minutes — c'est maintenant ou jamais.`,
      choices:[
        {label:"Frapper le garde à son entrée.", next:'cap_escape'},
        {label:"Passer derrière lui quand la porte s'ouvre.", next:'cap_escape_stealth'}
      ]
    },

    cap_escape: {
      bg:'escape', speaker:null, shake:true, flash:true,
      text:`Il ouvre la porte. Tu attends une seconde — juste une.

Puis tout est mouvement.

Le pied de lit s'abat sur son avant-bras. Il jure. Tu passes sous son bras, dans le couloir — tu cours sans regarder en arrière parce que si tu regardes en arrière tu t'arrêtes et si tu t'arrêtes c'est fini.

Couloir. Droite. Escalier.

Une alarme. Aiguë, violente — elle te déchire les tympans.

En bas. Une autre porte. Dehors — l'air froid de la nuit te happe.

Tu cours dans les pins, les branches qui fouettent ton visage, le sol inégal sous tes pieds.

Derrière toi, des voix. Des lampes torches balaient les arbres.

Tu cours.`,
      choices:[{label:"Continuer à fuir — vers la route.", next:'cap_end_escape'},{label:"Te cacher dans les bois.", next:'cap_hide'}]
    },

    cap_escape_stealth: {
      bg:'escape', speaker:'SARA',
      text:`Il entre. La porte commence à se refermer.

Tu passes dans son dos — un souffle, une ombre. Tes semelles font zéro bruit sur le béton.

Couloir. Tu marches vite, pas de course — la course fait du bruit. Une porte de service sur ta gauche, l'odeur de l'extérieur qui filtre en dessous.

Tu l'ouvres lentement. Centimètre par centimètre.

La nuit. Les pins. Une route de gravier qui part vers ce qui ressemble à une nationale au loin.

Pas d'alarme. Pas encore.

Tu marches dans les arbres, parallèle à la route, là où les ombres sont les plus épaisses.`,
      choices:[{label:"Atteindre la route.", next:'cap_end_escape'}]
    },

    cap_hide: {
      bg:'escape', speaker:'SARA',
      text:`Tu t'aplatit derrière un rocher, dans la fougère.

Les lampes passent à deux mètres. Tu retiens ta respiration jusqu'à ce que les points lumineux s'éloignent.

Silence. Puis — des pas proches. Très proches.

— Je sais que tu es là.

La voix de Matteo. Directement au-dessus de toi.

Un long silence.

— Tu es plus rapide que je pensais.

Il s'agenouille. Pas pour t'attraper — pour s'asseoir sur le rocher, comme si vous aviez rendez-vous ici.

— Il y a une route à deux kilomètres à l'est. Si tu arrives avant que les autres te trouvent...

Il se relève.

— Mais tu dois savoir que ce que tu as vu dans cette chambre — le dossier — c'est la surface.`,
      choices:[
        {label:"Fuir maintenant pendant qu'il parle.", next:'cap_end_escape'},
        {label:'"Qu\'est-ce que vous voulez vraiment dire ?"', next:'cap_end_twist'}
      ]
    },

    cap_negotiate: {
      bg:'mansion', speaker:'SARA',
      text:`— Je veux des garanties. Pas de la voix de mon père au téléphone. Des garanties réelles.

Matteo t'observe.

— Par exemple ?

— Vous me montrez sortir. Un accord écrit. Et vous me dites qui vous a vendu l'information sur moi — parce que si cette personne peut vous vendre ma vie, elle peut en vendre d'autres.

Il reste silencieux un très long moment.

— Tu n'as pas peur, dit-il enfin. Ou tu es très douée pour le cacher.

— J'ai peur, tu réponds. Ça ne change rien à ce que je viens de dire.

Quelque chose dans son expression change, imperceptiblement.`,
      choices:[{label:"Attendre sa réponse.", next:'cap_end_deal'}]
    },

    cap_end_give: {bg:'night', speaker:null,
      text:`Tu donnes les codes.

Matteo vérifie. Il hoche la tête à quelqu'un hors champ.

Deux heures plus tard, une voiture s'arrête sur une route que tu ne reconnais pas. Ils t'abandonnent là avec ton téléphone et cinq cents euros en cash.

Ton père répond à la première sonnerie.

Tu es libre. NarraCorp a perdu quarante millions en quelques heures.

Et quelque part dans le cercle intime de ton père, quelqu'un sait exactement ce qui s'est passé. Et est satisfait.

Tu as survécu. Mais l'histoire n'est pas finie.`, choices:[{label:"Fin — L'Accord",next:'__end__',endTitle:"Survivante",endText:"Tu as sacrifié quelque chose pour sortir vivante. Ce quelque chose reviendra te hanter."}]},

    cap_end_escape: {bg:'night', speaker:null,
      text:`La nationale. Les phares d'un camion.

Tu t'avances sur la route, les bras levés. Le camion freine dans un grincement de pneumatiques.

Le chauffeur — un homme d'une soixantaine d'années, air épuisé — te regarde à travers le pare-brise avec une expression qui oscille entre la surprise et la pitié.

Il t'emmène. Sans question. Avec une bouteille d'eau et une couverture de l'habitacle passager.

Tu n'appelles pas la police. Pas encore. Tu as besoin de comprendre qui, dans ton entourage, t'a vendue. Et pour ça tu as besoin de temps.

Tu as les codes. Et maintenant tu as quelque chose de plus précieux : tu sais que quelqu'un veut les voler.`, choices:[{label:"Fin — L'Évasion",next:'__end__',endTitle:"En fuite",endText:"Tu t'es échappée. Mais les questions qui t'ont mis là ne sont pas résolues. Elles commencent."}]},

    cap_end_deal: {bg:'mansion', speaker:null,
      text:`Il revient une heure plus tard.

— Un nom, dit-il. En échange des codes et de ta liberté.

Il pose une feuille sur le lit. Un nom. Quelqu'un que tu connais.

Quelqu'un en qui tu avais confiance.

La trahison a un visage. Elle a une histoire avec toi, des dîners de famille, des anniversaires.

Tu signes l'accord qu'il te tend.

Deux jours plus tard, tu es chez toi. Les codes ont été changés. La personne qui t'a vendue a disparu — dans les sens du terme que tu préfères ne pas préciser.

Et Matteo... tu penses à lui plus que tu ne devrais.`, choices:[{label:"Fin — Le Pacte",next:'__end__',endTitle:"Le Pacte",endText:"Tu as obtenu ta liberté. Mais le monde dans lequel tu as mis un pied ne te relâchera pas aussi facilement."}]},

    cap_end_twist: {bg:'night', speaker:null,
      text:`Il reste immobile dans l'obscurité.

— Ton père n'est pas une victime dans cette histoire, dit-il. Il savait que nous allions venir. Il a négocié ta présence ici comme monnaie d'échange pour effacer ses propres dettes.

Un silence qui dure trop longtemps.

— Je t'ai donné cette opportunité de t'échapper parce que... tu méritais de savoir la vérité avant de décider.

Il sort un téléphone de sa poche. Il le pose sur le rocher entre vous.

— À toi de choisir qui tu appelles maintenant.

La nuit autour de vous est absolue. Les voix des hommes qui te cherchent s'éloignent.

Tu regardes le téléphone.`, choices:[{label:"Fin — La Vérité",next:'__end__',endTitle:"Ce que tu ne savais pas",endText:"Certaines vérités libèrent. D'autres emprisonnent d'une façon que les barreaux ne peuvent pas."}]}
  }
},

// ════════════════════════════════════════════════
interrogation: {
  title: 'SANG FROID',
  color: '#A06820',
  scenes: {

    start: {
      bg:'office', chapter:'I', chTitle:'48 Heures', speaker:null,
      text:`La salle de briefing sent le café froid et la tension.

Ton supérieur — Reyes — pose une photo sur la table devant toi. Un homme. Quarante ans. Visage ordinaire. Le genre de visage qu'on oublie.

— Sebastian Narvez. Logisticien pour le Cartel Solano. On l'a chopé hier soir. Il sait où passe le prochain convoi — trois cents kilos, vendredi matin.

Il te regarde.

— Tu as quarante-huit heures, Damián. La méthode, c'est toi qui choisis.

La méthode.

Ces deux mots contiennent tout ce que tu ne dis pas à tes proches.`,
      choices:[
        {label:"Regarder le dossier complet sur Narvez.", next:'inter_study'},
        {label:"Y aller directement. Voir l'homme.", next:'inter_first'}
      ]
    },

    inter_study: {
      bg:'office', speaker:'DAMIÁN',
      text:`Tu lis tout.

Narvez. Quarante-deux ans. Né à Cali. Une fille — huit ans. Sa femme est décédée il y a trois ans, cancer. Il vit seul avec l'enfant chez sa belle-mère depuis.

Ce n'est pas un fanatique. Ce n'est pas un idéologue. C'est un homme qui a besoin d'argent et qui est trop profond pour sortir.

Ses points de pression sont clairs : la fille. La culpabilité. La peur de l'abandon.

Ces informations sont des outils. Tu les ranges dans un coin de ton esprit, derrière une porte que tu tries de garder fermée.

Quand tu entres dans la salle, tu sais exactement ce que tu vas faire.`,
      choices:[{label:"Entrer dans la salle d'interrogatoire.", next:'inter_enter'}]
    },

    inter_first: {
      bg:'interrogation', speaker:null,
      text:`La salle est petite. Quatre mètres sur quatre. Une table. Deux chaises. Une lampe qui éclaire trop fort.

Sebastian Narvez est assis, les poignets attachés à l'anneau central de la table. Il lève les yeux quand tu entres.

Il a l'air épuisé. Il a été arrêté la nuit dernière — probablement pas dormi, probablement peu nourri. Ses yeux sont rouges.

Mais ils ne tremblent pas.

Tu t'assieds en face de lui. Tu poses le dossier sur la table sans l'ouvrir.

Silence.

C'est toi qui choisis quand ça commence.`,
      choices:[
        {label:"Commencer par le silence. Le laisser mariner.", next:'inter_silence'},
        {label:"Attaque directe. Les noms, les dates, le convoi.", next:'inter_direct'}
      ]
    },

    inter_enter: {bg:'interrogation', speaker:null, text:`Il lève les yeux quand tu entres.

L'espace d'une seconde — une seule — il cherche quelque chose dans ton visage. Une indication de ce qui va venir.

Tu ne lui en donnes pas.

Tu t'assieds. Tu poses le dossier fermé sur la table. Pendant une longue minute tu ne dis rien. Tu le regardes simplement.

— Je sais pour ta fille, tu dis enfin. Valentina. Elle va avoir neuf ans en mars.

Quelque chose se contracte dans son visage. Imperceptible. Mais là.

Tu tiens ton angle.`, choices:[{label:"Continuer.", next:'inter_silence'}]},

    inter_silence: {
      bg:'interrogation', speaker:'DAMIÁN',
      text:`Vingt minutes de silence.

C'est plus long que la plupart des gens ne le croient. La plupart des gens pensent que le silence est confortable. Ce n'est pas vrai.

Le silence dans une pièce comme celle-ci est une chose vivante. Il grossit. Il remplit chaque coin. Il commence à peser sur les épaules.

Narvez résiste bien. Mieux que tu ne l'attendais. Il regarde la table, les murs, ses mains. Pas toi.

Éviter le regard, c'est une forme de dialogue.

— Tu sais ce qui me manque dans ce métier ? tu demandes enfin, d'une voix parfaitement calme. Le temps. Il n'y en a jamais assez.

Un pause.

— Moi, j'en ai quarante-six heures. Et toi tu n'en as que autant que je veux bien t'en laisser.`,
      choices:[
        {label:"Sortir son dossier familial. Montrer la photo de sa fille.", next:'inter_family'},
        {label:"Changer de tactique. Proposer un deal.", next:'inter_deal'}
      ]
    },

    inter_direct: {
      bg:'interrogation', speaker:'DAMIÁN', flash:true,
      text:`— Vendredi. Le convoi. Où.

Narvez ne répond pas.

— Je répète. Vendredi. Le convoi. Où est le point de transfert ?

— Je sais pas de quoi vous parlez.

C'est dit trop calmement. Il se l'est répété. Une phrase préparée.

Tu te lèves. Tu contournes la table lentement. Tu t'appuies contre le mur derrière lui.

— Tu as une fille. Elle s'appelle Valentina. Elle a huit ans. Elle vit avec ta belle-mère dans le quartier nord.

Sa respiration change.

— Nous n'allons pas là, dit-il. Sa voix est différente maintenant.

— J'espère que tu as raison.`,
      choices:[
        {label:"Continuer la pression psychologique.", next:'inter_family'},
        {label:"Reculer. Proposer un accord.", next:'inter_deal'}
      ]
    },

    inter_family: {
      bg:'interrogation', speaker:'DAMIÁN',
      text:`Tu poses la photo de Valentina sur la table. Huit ans. Un sourire avec un dent manquante. Une robe jaune.

Il ne la regarde pas. Il regarde un point au-dessus de ta tête.

— Je ne fais pas ça pour te menacer, tu dis. Je le fais pour que tu comprennes ce que tu as à perdre.

— Je comprends.

— Non. Tu crois comprendre. Les gens croient comprendre jusqu'au moment où ils comprennent vraiment.

Tu prends la photo. Tu la ranges.

— Si tu me donnes ce dont j'ai besoin, Valentina grandira avec un père. Peut-être pas libre. Mais vivant. Et présent. À travers des barreaux, mais présent.

Il serre les mâchoires. Un muscle se contracte dans sa joue.

Tu attends.`,
      choices:[
        {label:"Laisser ce silence faire son travail.", next:'inter_hour24'},
        {label:"Escalader. Passer aux méthodes physiques.", next:'inter_physical'}
      ]
    },

    inter_deal: {
      bg:'interrogation', speaker:'DAMIÁN',
      text:`— Je vais être honnête avec toi.

Il lève les yeux. Les gens regardent toujours quand tu dis ça. Parce que dans des salles comme celle-ci, l'honnêteté est rare.

— Je n'ai rien à gagner à te briser. Ça prend du temps et ça donne des informations de mauvaise qualité. Ce que je veux, c'est une information précise. Un point. Une date. Peut-être un nom.

Tu te lèves, tu t'approches.

— En échange : on retire ta fille de la liste de contacts. On efface les trois dernières années de ton casier de notre registre. Et quand tu sortiras — parce que tu sortiras — il n'y aura personne qui t'attendra pour régler des comptes.

Il te regarde longuement.

— Comment je sais que c'est vrai ?

— Tu ne le sais pas. C'est ça, le deal.`,
      choices:[
        {label:"Attendre sa réponse.", next:'inter_hour24'},
        {label:"Ajouter de la pression. Le temps presse.", next:'inter_pressure'}
      ]
    },

    inter_physical: {
      bg:'blood', speaker:'DAMIÁN', flash:true, shake:true,
      text:`Il parle vingt-deux heures après le début.

Ce qui s'est passé entre les deux, tu n'en parleras pas. Ni maintenant, ni plus tard. C'est la règle que tu t'es donnée — pas pour te protéger légalement, mais pour te protéger d'autre chose.

Ce que tu peux dire : Narvez a tenu. Longtemps. Plus longtemps que beaucoup.

Et quand il a parlé, il a pleuré. Pas de douleur. De honte. Ou peut-être les deux.

Tu sors de la salle. L'air du couloir est normal — température normale, lumière normale. Tu t'appuies contre le mur.

Reyes arrive. Il lit le rapport dans tes mains.

— Bien joué.

Tu ne dis rien.`,
      choices:[
        {label:"Accepter le résultat.", next:'inter_end_hard'},
        {label:"Questionner si ça valait le prix.", next:'inter_end_doubt'}
      ]
    },

    inter_hour24: {
      bg:'interrogation', speaker:null,
      text:`Heure vingt-quatre.

Narvez n'a pas dormi. Il a reçu de l'eau, un sandwich qu'il n'a pas touché. Il est toujours assis, les épaules de plus en plus basses.

Tu entres à nouveau. Tu poses deux tasses de café sur la table. Tu pousses l'une vers lui.

Il te regarde, méfiant.

— Je ne vais pas te remercier, dit-il.

— Je n'attends pas de remerciements.

Il prend le café. Ses mains tremblent légèrement.

Un long silence confortable — le premier depuis vingt-quatre heures.

— Si je parle, dit-il enfin sans te regarder, ma fille est protégée. Vraiment.`,
      choices:[
        {label:'"Vraiment."', next:'inter_breaking'},
        {label:"Honnêteté : tu ne peux pas le garantir à cent pour cent.", next:'inter_breaking_honest'}
      ]
    },

    inter_pressure: {
      bg:'interrogation', speaker:'DAMIÁN', flash:true,
      text:`— Il reste vingt-deux heures. Après ça, mon supérieur prend le relais. Et lui... il n'a pas les mêmes règles que moi.

Ce n'est pas un mensonge. Reyes a des méthodes que tu refuses d'utiliser.

Narvez ferme les yeux.

— Et si je vous donne quelque chose de partiel ?

— Partiel ne suffit pas.

— Et si c'est tout ce que j'ai ?

Tu te penches légèrement.

— Alors tu ferais mieux de commencer à parler maintenant. Parce que dans vingt-deux heures tu n'auras plus rien à négocier — et tu auras tout donné de toute façon.

Il regarde ses mains enchaînées. Très longtemps.`, choices:[{label:"Attendre.", next:'inter_breaking'}]},

    inter_breaking: {
      bg:'interrogation', speaker:'NARVEZ',
      text:`Il parle.

D'abord lentement. Une adresse. Un horaire. Un nom de contact.

Tu prends des notes sans l'interrompre.

Puis plus vite — comme si maintenant que la digue a cédé, il ne peut plus s'arrêter. Les détails, les quantités, les visages.

Quand il s'arrête, la salle est silencieuse.

Il fixe la table.

— C'est fini maintenant ? murmure-t-il.

Tu rassembles tes notes. Tu ranges ton stylo.

— Oui, tu réponds.

Et c'est la première fois depuis quarante-huit heures que tu lui dis la vérité sans calcul derrière.`,
      choices:[{label:"Sortir. Transmettre l'information.", next:'inter_end_clean'}]
    },

    inter_breaking_honest: {
      bg:'interrogation', speaker:'DAMIÁN',
      text:`Tu marques une pause.

— Non. Je ne peux pas te garantir ça à cent pour cent. Les protections que je peux offrir ont des limites que je ne contrôle pas entièrement.

Il t'observe — surpris, peut-être, par cette réponse.

— Ce que je peux te garantir : je ferai tout ce qui est en mon pouvoir. Et tu auras ma parole, pas celle d'une institution.

— La parole d'un homme qui m'a enfermé ici depuis vingt-quatre heures.

— Oui. Cette parole-là.

Un silence.

— C'est drôle, dit-il enfin. C'est la seule chose que tu m'aies dite depuis hier qui sonnait vraiment.

Il regarde ses mains.

— Il passe à Zaragoza. Vendredi à l'aube. Entrepôt frigorifique près du port.`, choices:[{label:"Fin de l'interrogatoire.", next:'inter_end_clean'}]},

    inter_end_clean: {bg:'office', speaker:null,
      text:`Vendredi, à l'aube, l'opération intercepte le convoi à Zaragoza.

Trois cents kilos. Huit arrestations. Zéro victime parmi les forces de l'ordre.

Reyes t'appelle. Il est satisfait.

Dans le rapport officiel, la méthode d'extraction de l'information est décrite en termes neutres. C'est la façon dont ça fonctionne.

Tu ne lis pas les rapports officiels.

Ce que tu portes, tu le portes seul. C'est aussi la façon dont ça fonctionne.`, choices:[{label:"Fin — La Confession",next:'__end__',endTitle:"Ce que ça coûte",endText:"L'opération est un succès. Les statistiques ne disent pas ce qui se passe entre les lignes."}]},

    inter_end_hard: {bg:'office', speaker:null,
      text:`Le convoi est intercepté. L'opération réussit.

Dans la semaine qui suit, tu bois plus que d'habitude. Tu dors mal. Tu te réveilles à 3h du matin avec l'image des mains de Narvez qui tremblaient.

Tu as fait ton travail.

C'est ce que tu te répètes.

Ce n'est jamais tout à fait assez.`, choices:[{label:"Fin — Le Prix",next:'__end__',endTitle:"Le Prix du Résultat",endText:"Certains succès ne ressemblent pas à des victoires de l'intérieur."}]},

    inter_end_doubt: {bg:'office', speaker:null,
      text:`Tu passes la nuit à écrire. Pas un rapport. Une lettre que tu n'enverras jamais.

À qui ? Tu ne sais pas. Peut-être à Narvez. Peut-être à toi-même d'il y a dix ans, qui pensait que cette vie aurait des contours clairs.

L'information était bonne. Le convoi a été intercepté. Tu as sauvé des vies — c'est ce que Reyes dira. C'est ce que les chiffres montreront.

Les chiffres ne montrent pas les visages.

Tu glisses la lettre dans un tiroir que tu ne rouvres jamais.`, choices:[{label:"Fin — L'Ambiguïté",next:'__end__',endTitle:"Sans réponse simple",endText:"Il n'y a pas de case à cocher ici. Juste une question que tu porteras."}]}
  }
},

// ════════════════════════════════════════════════
journalist: {
  title: 'LA VÉRITÉ SAIGNE',
  color: '#1A6B8B',
  scenes: {

    start: {
      bg:'night', chapter:'I', chTitle:'Le Tip Anonyme', speaker:null,
      text:`23h41. Barcelone.

Un message arrive sur le téléphone crypté que tu utilises depuis que ta source principale a été retrouvée dans le port avec les poignets ligotés.

Pas de nom. Pas de numéro. Juste une adresse et deux mots.

VENEZ SEULE.

Tu es Elena Moreau. Tu enquêtes sur les disparitions dans le quartier portuaire depuis trois semaines. Tu as publié deux articles. Les deux t'ont valu une convocation au commissariat et un appel de ton rédacteur en chef qui t'a demandé de "mesurer les risques".

Tu n'es pas douée pour mesurer les risques.

Tu prends ton appareil photo. Tu sors.`,
      choices:[
        {label:"Aller à l'adresse seule, maintenant.", next:'journ_go'},
        {label:"Appeler un contact d'abord pour couvrir tes arrières.", next:'journ_backup'}
      ]
    },

    journ_backup: {
      bg:'night', speaker:'ELENA',
      text:`Tu appelles Marco — photographe, ami depuis sept ans, discret.

— Adresse. Si je ne t'envoie pas un message dans deux heures, tu la donnes à Sébastien et à la police. Dans cet ordre.

— Elena...

— Dans cet ordre, Marco.

Tu raccroches avant qu'il puisse argumenter.

C'est la seule sécurité que tu peux te permettre sans effrayer la source.

Tu montes dans le taxi.`,
      choices:[{label:"Arriver à l'adresse.", next:'journ_arrive'}]
    },

    journ_go: {bg:'night', speaker:'ELENA', text:`Tu y vas seule. Vraiment seule — pas de message à laisser, pas de contact prévenu.

Dans le taxi, tu notes l'adresse sur un carnet et tu laisses le carnet dans ta poche de veste. Si quelque chose tourne mal, la police finira par le trouver.

C'est la façon dont les journalistes meurent — pas par ignorance, mais par entêtement.

Tu sais ça. Tu y vas quand même.`, choices:[{label:"Arriver à l'adresse.", next:'journ_arrive'}]},

    journ_arrive: {
      bg:'warehouse', speaker:null,
      text:`L'adresse est un entrepôt frigorifique désaffecté dans la zone industrielle du port.

La porte de service est ouverte. À l'intérieur, l'odeur — froide, chimique, et quelque chose en dessous que tu reconnais sans vouloir le nommer.

Une lampe torche est posée sur une caisse. Dirigée vers le fond.

Tu avances.

Ce que tu trouves au fond de l'entrepôt va changer quelque chose en toi de façon permanente.

Tu sors ton appareil photo. Tes mains ne tremblent pas encore.`,
      choices:[
        {label:"Photographier tout. Méticuleusement.", next:'journ_evidence'},
        {label:"Chercher d'abord si quelqu'un est encore là.", next:'journ_search'}
      ]
    },

    journ_search: {
      bg:'warehouse', speaker:'ELENA', flash:true,
      text:`Tu balais la pièce avec la lampe.

Dans le coin, quelque chose bouge.

Un homme. Recroquevillé contre le mur, les bras autour des genoux. Il lève les yeux quand ta lampe l'atteint — des yeux qui ont vu des choses, beaucoup trop de choses, et qui essaient encore de comprendre lesquelles sont réelles.

Il a peut-être quarante ans. Il en paraît soixante.

— Sont partis, murmure-t-il en espagnol. Il y a... je sais pas. Longtemps.

Tu t'agenouilles devant lui.

— Tu es en sécurité. Je suis journaliste. Comment tu t'appelles ?`,
      choices:[
        {label:"Rester avec lui. L'écouter.", next:'journ_survivor'},
        {label:"Appeler les secours en premier.", next:'journ_call_first'}
      ]
    },

    journ_survivor: {
      bg:'warehouse', speaker:null,
      text:`Il s'appelle Rafa. Trente-huit ans, ancien docker.

Il parle pendant vingt minutes. Sa voix est plate — pas par manque d'émotion, mais parce que l'émotion est quelque chose qui viendra plus tard, quand il sera en sécurité et que son cerveau aura digéré ce que son corps a subi.

Il parle des hommes qui sont venus. Des questions posées encore et encore. De ce qui se passait quand les réponses ne venaient pas, ou ne venaient pas assez vite.

Il parle des autres — ceux qui étaient là avant lui. Ceux qui ne sont pas repartis.

Tu enregistres tout. Tu hais que tu enregistres tout.

— Tu as vu leurs visages ? tu demandes enfin.

— Un seul. Un seul qui ne portait pas de cagoule.

Il te décrit un visage. Et cette description, tu la connais.`,
      choices:[{label:"Lui demander de répéter la description.", next:'journ_vega_link'}]
    },

    journ_evidence: {
      bg:'warehouse', speaker:'ELENA',
      text:`Tu photographies tout.

Les traces sur le sol en béton. Les anneaux fixés aux murs — récemment installés, les vis encore brillantes. Les restes de matériel médical — des seringues, une bouteille de sérum, quelque chose qui ressemble à du matériel de chirurgie de terrain.

Et dans l'angle le plus sombre — des papiers. Un carnet, à moitié brûlé. Des noms, des dates, des montants. 

Tu prends ton téléphone. Tu photographies chaque page visible. Ta main tremble maintenant — légèrement, mais là.

Quelqu'un a utilisé cet endroit régulièrement. Pas une fois. Des dizaines de fois.

Et les noms sur le carnet — tu en reconnais deux. L'un est un homme politique. L'autre est une figure connue du monde des affaires barcelonais.

Le troisième nom, sur la couverture intérieure, est écrit plus grand.`,
      choices:[{label:"Lire le nom.", next:'journ_vega_name'}]
    },

    journ_vega_name: {
      bg:'warehouse', speaker:'ELENA',
      text:`R. VEGA.

Deux lettres. Un nom.

Tu as entendu ce nom une fois, il y a six mois, dans une conversation que tu n'aurais pas dû entendre dans le couloir d'une préfecture. Une conversation entre deux inspecteurs qui parlaient à voix basse et s'arrêtaient quand quelqu'un passait.

Tu ranges le carnet dans ton sac. Tu sais que tu n'aurais pas dû. Tu le fais quand même.

C'est à ce moment que ton téléphone sonne. Numéro inconnu.

Tu décroches.

La voix est masculine, calme, légèrement teintée d'un accent espagnol.

— Je sais que vous êtes dans l'entrepôt, señorita Moreau. Ne prenez rien d'autre. Et ne publiez pas encore.

Un silence.

— Pas encore. Nous devons parler.`,
      choices:[
        {label:'"Qui êtes-vous ?"', next:'journ_call_response'},
        {label:"Raccrocher et sortir de là immédiatement.", next:'journ_flee'}
      ]
    },

    journ_vega_link: {
      bg:'warehouse', speaker:'ELENA',
      text:`Il répète. Lentement.

Grand. Quarante ans environ. Des yeux sombres, un visage sans émotion visible.

Tu connais ce visage. Tu l'as vu en photo — une seule, sur un document confidentiel qu'une source t'avait montré il y a trois mois avant de disparaître elle-même.

Rafael Vega.

Un nom sans dossier public. Sans passé officiel. Sans présence dans les bases de données accessibles.

Un fantôme qui apparaît dans les marges d'autres enquêtes. Jamais au centre. Toujours à la périphérie.

Tu sors. Rafa est dans ta voiture, enveloppé dans ta veste. Tu appelles les secours.

Et ton téléphone sonne.

Numéro inconnu.`,
      choices:[{label:"Répondre.", next:'journ_call_response'}]
    },

    journ_call_first: {bg:'warehouse', speaker:'ELENA', text:`Tu appelles les secours.

Pendant que tu attends, tu sors ton appareil et tu travailles vite — dix minutes, autant de photos que possible.

Quand les ambulanciers arrivent, tu leur remets l'homme. Tu donnes ton identité — journaliste, témoin, pas suspecte. Ils ont l'habitude.

Dans ta voiture, tu regardes les photos sur ton écran. Parmi elles : une page de carnet partiellement visible. Un nom.

R. VEGA.`, choices:[{label:"Continuer l'enquête.", next:'journ_vega_name'}]},

    journ_call_response: {
      bg:'night', speaker:'VOIX',
      text:`— Qui êtes-vous ?

— Quelqu'un qui a intérêt à ce que vous n'alliez pas plus loin ce soir. Et quelqu'un qui a peut-être intérêt à ce que vous ayez plus d'informations avant de publier.

— Ce sont deux choses contradictoires.

— Non. Ce sont deux façons d'arriver au même endroit.

Un silence.

— L'entrepôt que vous venez de visiter — ce n'était pas de Vega. C'était de son ennemi. Quelqu'un qui utilise les mêmes méthodes et qui a intérêt à ce que l'opinion publique fasse la confusion.

Ta main serre le téléphone.

— Pourquoi je vous croirais ?

— Vous ne me croyez pas. Pas encore. Mais vous allez vérifier ce que je vous dis. Et quand vous aurez vérifié...

— Un endroit. Ce soir. Minuit.

L'adresse qu'il donne est celle d'un café que tu connais bien. Un lieu public.`,
      choices:[
        {label:"Y aller.", next:'journ_meet'},
        {label:"Refuser. Publier maintenant.", next:'journ_publish'}
      ]
    },

    journ_flee: {
      bg:'night', speaker:'ELENA', shake:true,
      text:`Tu sors de l'entrepôt en courant.

Le téléphone dans une main, les photos dans l'autre. Tu n'appelles pas la police — pas encore. Pas avant d'avoir une copie de tout sur un serveur sécurisé.

Dans le taxi, tu envoies les photos à Marco. Puis tu envoies le carnet de l'entrepôt — tu l'avais pris.

Ton téléphone sonne à nouveau. Numéro inconnu.

Tu décroches cette fois.

— Señorita Moreau. Vous avez pris le carnet.

Un silence.

— C'est courageux. Ou imprudent. La frontière est mince.

Sa voix est calme. Dangereusement calme.

— Je vous suggère de ne pas publier ce soir. Pas parce que je vous en empêcherai. Parce que vous ne savez pas encore ce que vous avez.`, choices:[{label:"Écouter.", next:'journ_call_response'}]},

    journ_meet: {
      bg:'mansion', speaker:null,
      text:`Le café est fermé. Mais la porte est ouverte.

À l'intérieur, un homme est assis à une table au fond, dos au mur. Devant lui : deux tasses de café.

Grand. Des épaules sous un costume gris nuit. Des yeux sombres qui ne clignotent pas.

Tu t'assieds en face de lui. Tu ne touches pas le café.

Il te regarde comme s'il avait déjà calculé trois versions de cette conversation.

— Rafael Vega, tu dis.

— Elena Moreau, répond-il simplement.

Un silence.

— L'entrepôt appartient aux frères Salazar. Ce sont mes ennemis. Et ils cherchent à créer une guerre en faisant porter ma signature sur leurs actes.

Il pose sur la table un dossier mince.

— Voici les preuves que je dispose. Je vous les donne.

— Pourquoi ?

— Parce que si vous publiez ce soir avec les mauvaises informations, vous aidez ceux qui m'ont tendu ce piège. Et moi j'ai d'autres ennemis à combattre.`,
      choices:[
        {label:"Prendre le dossier. Vérifier.", next:'journ_end_deal'},
        {label:'"Je n\'aide pas les criminels. Même contre d\'autres criminels."', next:'journ_end_defiance'}
      ]
    },

    journ_publish: {
      bg:'office', speaker:'ELENA', flash:true,
      text:`Tu publies à 2h du matin.

L'article est en ligne à 2h17. À 3h, il a cinq mille partages. À 6h, les grandes agences reprennent.

À 7h, ton appartement est visité. Tu n'y étais pas — tu avais dormi chez Marco, instinct ou paranoïa.

Rien n'est volé. Mais quelqu'un a lu tes notes. Quelqu'un sait que tu as plus que ce que tu as publié.

Ton téléphone sonne. Encore.

— Vous avez publié.

— C'est mon métier.

— Oui. Maintenant voilà ce que vous ne savez pas encore : le nom que vous avez trouvé dans ce carnet — Vega — c'est aussi le nom d'un informateur sous protection policière depuis deux ans.

Un silence.

— Vous venez de griller une source de la DGSI.`, choices:[{label:"Fin — La Publication",next:'__end__',endTitle:"Le Prix de la Vérité",endText:"Tu as publié. La vérité était réelle — mais incomplète. Et les conséquences, toi seule les porteras."}]},

    journ_end_deal: {bg:'mansion', speaker:null,
      text:`Tu passes deux jours à vérifier chaque élément du dossier.

Il dit la vérité — du moins, la partie vérifiable.

Ton article paraît une semaine plus tard. Il est différent de celui que tu aurais écrit cette nuit-là. Plus complexe. Plus précis. Plus dangereux pour les bonnes personnes.

Les frères Salazar sont arrêtés trente-six heures après la publication.

Vega... Vega reste où Vega a toujours été. Dans l'ombre. Hors d'atteinte.

Il t'envoie un message. Une seule ligne.

"Bon travail, señorita Moreau."

Tu ne réponds pas. Mais tu gardes le message.`, choices:[{label:"Fin — L'Accord",next:'__end__',endTitle:"La Source",endText:"Tu as publié la vérité. Quelqu'un d'autre t'a aidée à la trouver. Ces deux choses coexistent inconfortablement."}]},

    journ_end_defiance: {bg:'night', speaker:null,
      text:`Tu te lèves. Tu prends ton sac.

— Je vais publier. Avec ce que j'ai. Si vous êtes innocent de ce qui s'est passé dans cet entrepôt, vous pouvez le prouver vous-même, dans un tribunal.

Tu marches vers la sortie.

— Señorita Moreau.

Tu t'arrêtes mais tu ne te retournes pas.

— Je respecte ça. Plus que vous ne le croiriez.

Tu sors dans la nuit barcelonaise.

Ton article paraît le lendemain. Il provoque une enquête officielle — la première vraie enquête en trois ans sur les disparitions portuaires.

Tu ne sais pas si Vega était coupable de ce soir-là. Mais tu sais ce que tu as vu. Et ce que tu as vu était réel.`, choices:[{label:"Fin — La Ligne",next:'__end__',endTitle:"Le Refus",endText:"Tu n'as pas plié. C'est ce qui définit ce métier. Et ce qui le rend dangereux."}]}
  }
}

}; // end STORIES

// ════════════════════════════════════════════════
// AUDIO ENGINE — Piano Ambient
// ════════════════════════════════════════════════
let audioCtx = null, musicOn = false, musicInterval = null;

function initAudio() {
  if (audioCtx) return;
  audioCtx = new (window.AudioContext || window.webkitAudioContext)();
}

function playPianoNote(freq, startTime, duration, vol = 0.08) {
  const osc = audioCtx.createOscillator();
  const gain = audioCtx.createGain();
  const filter = audioCtx.createBiquadFilter();
  filter.type = 'lowpass'; filter.frequency.value = 1200;
  osc.type = 'triangle';
  osc.frequency.value = freq;
  gain.gain.setValueAtTime(0, startTime);
  gain.gain.linearRampToValueAtTime(vol, startTime + 0.04);
  gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration);
  osc.connect(filter); filter.connect(gain); gain.connect(audioCtx.destination);
  osc.start(startTime); osc.stop(startTime + duration + 0.1);
}

const CHORD_PROGRESSIONS = [
  [110, 138.59, 164.81, 220, 261.63],
  [98, 123.47, 146.83, 196, 246.94],
  [116.54, 146.83, 174.61, 233.08, 293.66],
  [103.83, 130.81, 155.56, 207.65, 261.63]
];

function playAmbientChord() {
  if (!audioCtx || !musicOn) return;
  const prog = CHORD_PROGRESSIONS[Math.floor(Math.random() * CHORD_PROGRESSIONS.length)];
  const now = audioCtx.currentTime;
  const shuffled = [...prog].sort(() => Math.random() - 0.5);
  shuffled.forEach((freq, i) => {
    const offset = i * 0.4 + Math.random() * 0.2;
    playPianoNote(freq, now + offset, 5 + Math.random() * 3, 0.05 + Math.random() * 0.04);
    if (Math.random() > 0.5) playPianoNote(freq * 2, now + offset + 0.8, 3, 0.02);
  });
}

function toggleMusic() {
  initAudio();
  musicOn = !musicOn;
  document.getElementById('music-btn').textContent = musicOn ? '♪ ON' : '♪ OFF';
  if (musicOn) {
    playAmbientChord();
    musicInterval = setInterval(playAmbientChord, 7000);
  } else {
    clearInterval(musicInterval);
  }
}

// ════════════════════════════════════════════════
// GAME ENGINE
// ════════════════════════════════════════════════
let currentStory = null, currentScene = null, typing = false, typeTimer = null;

function enterSite() {
  document.getElementById('s-warning').classList.add('hidden');
  document.getElementById('s-menu').classList.remove('hidden');
}

function showMenu() {
  document.getElementById('s-game').classList.add('hidden');
  document.getElementById('s-menu').classList.remove('hidden');
  document.getElementById('end-card').classList.remove('visible');
  document.getElementById('chapter-card').classList.remove('visible');
}

function startStory(id) {
  currentStory = STORIES[id];
  document.getElementById('s-menu').classList.add('hidden');
  document.getElementById('s-game').classList.remove('hidden');
  document.getElementById('hud-title').textContent = currentStory.title;
  document.documentElement.style.setProperty('--story-color', currentStory.color);
  loadScene('start');
}

function loadScene(sceneId) {
  if (sceneId === '__end__') return; // handled by choice click
  currentScene = sceneId;
  const s = currentStory.scenes[sceneId];
  if (!s) return;

  // Background
  const bg = document.getElementById('game-bg');
  bg.className = 'bg-' + (s.bg || 'night');
  if (s.shake) { setTimeout(() => bg.classList.add('shake'), 100); setTimeout(() => bg.classList.remove('shake'), 700); }
  if (s.flash) flashRed();

  // Chapter card
  if (s.chapter) showChapter(s.chapter, s.chTitle || '');

  // Speaker
  document.getElementById('speaker-name').textContent = s.speaker || '';

  // Text
  document.getElementById('choices').innerHTML = '';
  document.getElementById('choices').classList.remove('visible');
  document.getElementById('tap-hint').style.display = 'block';

  if (s.chapter) {
    setTimeout(() => typeText(s.text, s.choices), 2800);
  } else {
    typeText(s.text, s.choices);
  }

  // Slide animation
  document.getElementById('game-textbox').classList.remove('slide-in');
  void document.getElementById('game-textbox').offsetWidth;
  document.getElementById('game-textbox').classList.add('slide-in');
}

function showChapter(num, title) {
  const card = document.getElementById('chapter-card');
  document.getElementById('ch-num').textContent = 'Chapitre ' + num;
  document.getElementById('ch-title').textContent = title;
  card.classList.add('visible');
  setTimeout(() => card.classList.remove('visible'), 2500);
}

function flashRed() {
  const f = document.getElementById('flash-overlay');
  f.classList.add('flash-red');
  setTimeout(() => f.classList.remove('flash-red'), 400);
}

function typeText(text, choices) {
  const el = document.getElementById('story-text');
  el.textContent = '';
  typing = true;
  let i = 0;
  const speed = 22;

  function next() {
    if (!typing) { el.textContent = text; showChoices(choices); return; }
    if (i < text.length) {
      el.textContent += text[i++];
      typeTimer = setTimeout(next, speed);
    } else {
      typing = false;
      document.getElementById('tap-hint').style.display = 'none';
      showChoices(choices);
    }
  }
  next();
}

function skipTyping() {
  if (typing) {
    typing = false;
    clearTimeout(typeTimer);
  }
}

function showChoices(choices) {
  if (!choices || !choices.length) return;
  const el = document.getElementById('choices');
  el.innerHTML = '';
  choices.forEach(c => {
    const btn = document.createElement('button');
    btn.className = 'choice-btn';
    btn.textContent = c.label;
    btn.onclick = () => choiceClick(c);
    el.appendChild(btn);
  });
  el.classList.add('visible');
}

function choiceClick(choice) {
  if (choice.next === '__end__') {
    showEnd(choice.endTitle, choice.endText);
  } else {
    loadScene(choice.next);
  }
}

function showEnd(title, text) {
  const card = document.getElementById('end-card');
  document.getElementById('end-title').textContent = title || 'Fin';
  document.getElementById('end-text').textContent = text || '';
  card.classList.add('visible');
}

// Click to skip typing
document.getElementById('s-game').addEventListener('click', e => {
  if (!e.target.classList.contains('choice-btn') &&
      !e.target.classList.contains('hud-btn') &&
      !e.target.classList.contains('btn-main')) {
    skipTyping();
  }
});
</script>
</body>
</html>
