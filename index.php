<?php ?><!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
<title>CARTEL</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Raleway:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--red:#C41E3A;--gold:#A07840;--text:#EAE6DC;--dim:#7A7570;--black:#05050A}
html,body{width:100%;height:100%;overflow:hidden;background:#000;font-family:'Raleway',sans-serif}
.screen{position:fixed;inset:0;transition:opacity .5s;z-index:10}
.screen.hidden{opacity:0;pointer-events:none}

#s-menu{
  background:linear-gradient(160deg,#06000E,#100008,#050010);
  display:flex;flex-direction:column;align-items:center;overflow-y:auto;padding:3rem 1.5rem 4rem
}
.menu-title{font-family:'Cormorant Garamond',serif;font-size:clamp(3rem,10vw,6rem);font-weight:300;color:#fff;letter-spacing:.12em;margin-bottom:.3rem}
.menu-sub{color:var(--dim);font-size:.7rem;letter-spacing:.35em;text-transform:uppercase;margin-bottom:3.5rem}
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

#s-game{display:flex;flex-direction:column;background:#000}
#game-bg{flex:1;position:relative;overflow:hidden;background-size:cover;background-position:center;transition:background-image .8s}
#game-bg::after{content:'';position:absolute;bottom:0;left:0;right:0;height:55%;background:linear-gradient(transparent,rgba(0,0,0,.92));pointer-events:none}
#game-bg::before{content:'';position:absolute;inset:0;background:rgba(0,0,0,.45);pointer-events:none}

#game-hud{position:absolute;top:0;left:0;right:0;display:flex;justify-content:space-between;align-items:center;padding:.8rem 1rem;z-index:10}
.hud-title{font-family:'Cormorant Garamond',serif;font-size:.85rem;font-style:italic;color:rgba(255,255,255,.4);letter-spacing:.1em}
.hud-btns{display:flex;gap:.5rem}
.hud-btn{background:rgba(0,0,0,.6);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.5);padding:.35rem .7rem;font-size:.65rem;cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.1em;transition:all .2s}
.hud-btn:hover{color:#fff;border-color:rgba(255,255,255,.3)}

#game-textbox{background:linear-gradient(180deg,rgba(5,5,15,.93),rgba(3,3,10,.99));border-top:1px solid rgba(255,255,255,.06);padding:1.2rem 1.5rem 1rem;min-height:220px;display:flex;flex-direction:column;position:relative}
#speaker-name{font-size:.65rem;letter-spacing:.3em;text-transform:uppercase;color:var(--story-color,var(--red));margin-bottom:.6rem;font-weight:500;min-height:1.2em}
#story-text{font-size:clamp(.85rem,2.5vw,.95rem);line-height:1.9;color:var(--text);flex:1;white-space:pre-line;min-height:80px}
#choices{display:flex;flex-direction:column;gap:.6rem;margin-top:1rem;opacity:0;transition:opacity .4s}
#choices.visible{opacity:1}
.choice-btn{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.1);color:var(--text);padding:.75rem 1rem;text-align:left;cursor:pointer;font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.05em;border-left:2px solid var(--story-color,var(--red));transition:all .25s;display:flex;align-items:center;gap:.8rem}
.choice-btn::before{content:'›';color:var(--story-color,var(--red));font-size:1.1rem}
.choice-btn:hover{background:rgba(255,255,255,.08);border-color:var(--story-color,var(--red));transform:translateX(4px)}
#tap-hint{position:absolute;bottom:.8rem;right:1rem;font-size:.6rem;letter-spacing:.15em;color:rgba(255,255,255,.2);text-transform:uppercase;animation:blink 1.5s infinite}
@keyframes blink{0%,100%{opacity:.2}50%{opacity:.6}}

#chapter-card{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.92);z-index:20;opacity:0;pointer-events:none;transition:opacity .5s;flex-direction:column;text-align:center}
#chapter-card.visible{opacity:1;pointer-events:all}
.ch-num{font-size:.65rem;letter-spacing:.4em;color:var(--story-color,var(--red));text-transform:uppercase;margin-bottom:.8rem}
.ch-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,6vw,3.5rem);font-weight:300;font-style:italic;color:#fff}
.ch-line{width:0;height:1px;background:var(--story-color,var(--red));margin:.8rem auto;transition:width 1s ease .3s}
#chapter-card.visible .ch-line{width:80px}

#end-card,#death-card{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:25;opacity:0;pointer-events:none;transition:opacity .8s;flex-direction:column;text-align:center;padding:2rem}
#end-card.visible,#death-card.visible{opacity:1;pointer-events:all}
#end-card{background:rgba(0,0,0,.95)}
#death-card{background:radial-gradient(ellipse at center,#1a0005,#000)}
.end-symbol{color:var(--story-color,var(--red));font-size:2rem;margin-bottom:1.5rem}
.end-title,.death-title{font-family:'Cormorant Garamond',serif;font-size:clamp(1.8rem,5vw,3rem);font-weight:300;font-style:italic;color:#fff;margin-bottom:.8rem}
.death-title{color:#C41E3A;font-size:clamp(2rem,6vw,4rem);letter-spacing:.1em}
.end-text,.death-text{color:var(--dim);font-size:.85rem;line-height:1.8;max-width:400px;margin-bottom:2rem}
.death-text{color:#8A4050}
.death-skull{font-size:3rem;margin-bottom:1rem;filter:drop-shadow(0 0 20px rgba(200,0,30,.4))}
.card-btns{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center}
.btn-main{background:var(--red);color:#fff;border:none;padding:1rem 2.5rem;font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.25em;text-transform:uppercase;cursor:pointer;transition:all .3s}
.btn-main:hover{background:#E02040;transform:scale(1.03)}
.btn-ghost{background:transparent;color:rgba(255,255,255,.4);border:1px solid rgba(255,255,255,.2);padding:1rem 2.5rem;font-family:'Raleway',sans-serif;font-size:.8rem;letter-spacing:.25em;text-transform:uppercase;cursor:pointer;transition:all .3s}
.btn-ghost:hover{color:#fff;border-color:rgba(255,255,255,.5)}

#flash-overlay{position:fixed;inset:0;background:#8B0000;pointer-events:none;opacity:0;z-index:50}
@keyframes flash{0%,100%{opacity:0}50%{opacity:.5}}
.flash-red{animation:flash .3s ease}
@keyframes shake{0%,100%{transform:translate(0)}15%{transform:translate(-6px,-2px)}30%{transform:translate(6px,2px)}45%{transform:translate(-4px,3px)}60%{transform:translate(4px,-3px)}75%{transform:translate(-2px,1px)}90%{transform:translate(2px,1px)}}
.shake{animation:shake .5s ease}
@keyframes fadeSlide{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.fade-slide{animation:fadeSlide .6s ease forwards}

#music-btn{position:fixed;bottom:1rem;left:1rem;z-index:100;background:rgba(0,0,0,.7);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.4);padding:.4rem .7rem;font-size:.6rem;cursor:pointer;font-family:'Raleway',sans-serif;letter-spacing:.15em;transition:all .2s}
#music-btn:hover{color:#fff}
</style>
</head>
<body>
<div id="flash-overlay"></div>

<div id="s-menu" class="screen">
  <h1 class="menu-title">CARTEL</h1>
  <p class="menu-sub">Trois histoires. Fais les bons choix. Ou meurs.</p>
  <div class="stories-grid">
    <div class="story-card" style="--cc:#C41E3A" onclick="startStory('captive')">
      <div class="sc-num">Histoire 01</div>
      <h2 class="sc-title">CAPTIVE</h2>
      <p class="sc-tag">★ Survie · Kidnapping · Mort possible</p>
      <p class="sc-desc">Elle s'est réveillée ligotée dans le noir. Elle ne sait pas où elle est. Elle ne sait pas pourquoi. Mais elle sait une chose — si elle ne trouve pas une sortie avant l'aube, il n'y en aura plus.</p>
      <button class="sc-btn">Jouer</button>
    </div>
    <div class="story-card" style="--cc:#8B5A00" onclick="startStory('interrogation')">
      <div class="sc-num">Histoire 02</div>
      <h2 class="sc-title">SANG FROID</h2>
      <p class="sc-tag">★ Interrogatoire · Pression · Sans pitié</p>
      <p class="sc-desc">Damián n'est pas un homme. C'est une machine conçue pour briser les autres. Quarante-huit heures. Un prisonnier. Une information. Il n'y a pas de limites dans cette pièce.</p>
      <button class="sc-btn">Jouer</button>
    </div>
    <div class="story-card" style="--cc:#1A5A8B" onclick="startStory('journalist')">
      <div class="sc-num">Histoire 03</div>
      <h2 class="sc-title">LA VÉRITÉ SAIGNE</h2>
      <p class="sc-tag">★ Enquête · Danger · Pas de retour</p>
      <p class="sc-desc">Elena Moreau a trouvé quelque chose qu'elle n'aurait jamais dû trouver. Maintenant quelqu'un veut s'assurer qu'elle ne pourra jamais le publier. Ni elle, ni personne.</p>
      <button class="sc-btn">Jouer</button>
    </div>
  </div>
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
    <div id="tap-hint">Toucher pour continuer</div>
  </div>
</div>

<button id="music-btn" onclick="toggleMusic()">♪ OFF</button>

<script>
const BG = {
  cell:     'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1920&q=90',
  mansion:  'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1920&q=90',
  warehouse:'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1920&q=90',
  interrogation:'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1920&q=90',
  night:    'https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1920&q=90',
  office:   'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=90',
  escape:   'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=1920&q=90',
  blood:    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=1920&q=90&sat=-100&hue=0',
};

const STORIES = {

captive:{
  title:'CAPTIVE',color:'#C41E3A',
  scenes:{
    start:{
      bg:'cell',chapter:'I',chTitle:'Les Ténèbres',speaker:null,
      text:`Du sang dans la bouche.

C'est la première sensation. Avant la douleur. Avant la panique. Le goût métallique, chaud, qui te dit que quelque chose de grave s'est déjà passé.

Tu ouvres les yeux.

Le noir. Complet. Ta respiration s'emballe — mauvaise idée, le tissu autour de ta tête colle à ta bouche quand tu aspires. Un sac. Ils t'ont mis un sac sur la tête.

Tes mains sont liées derrière ton dos. Tes chevilles aussi. Quelque chose de froid sous toi — du béton.

Tu ne sais pas combien de temps tu as été inconsciente. Tu ne sais pas où tu es. Tu ne sais pas qui a fait ça.

Mais tu es vivante.

Pour l'instant.`,
      choices:[
        {label:'Rester immobile. Écouter avant d\'agir.',next:'cap_listen'},
        {label:'Te débattre. Appeler au secours.',die:true,deathMsg:'Tu as crié. Quelqu\'un est entré. La crosse du pistolet contre ta tempe a été la dernière chose que tu as sentie.'}
      ]
    },
    cap_listen:{
      bg:'cell',speaker:'SARA',
      text:`Tu te forces à ne pas bouger.

Quatre secondes pour inspirer. Quatre pour retenir. Quatre pour expirer.

Tu écoutes.

Des voix. Loin, atténuées par une porte ou un mur épais. Espagnol. Au moins deux hommes. L'un d'eux rit de quelque chose. L'autre ne rit pas.

Des pas à intervalles réguliers dehors. Un garde. Toutes les... cinq minutes, peut-être six.

Tu travailles sur tes liens. Lentement. Sans faire de bruit. Tes poignets saignent avant que le noeud cède.

Quand tu retires le sac, tu vois la pièce : quatre mètres sur quatre. Une ampoule nue au plafond. Une porte en métal sans poignée côté intérieur. Une caméra dans l'angle supérieur droit — le voyant rouge clignote.

Ils te regardent.

Alors tu fais ce que tu aurais dû faire dès le départ : tu te rassieds, tu recroise les mains dans le dos, et tu attends.`,
      choices:[
        {label:'Chercher un angle mort à la caméra.',next:'cap_camera'},
        {label:'Regarder fixement la caméra pour montrer que tu sais.',next:'cap_stare'}
      ]
    },
    cap_camera:{
      bg:'cell',speaker:'SARA',
      text:`La caméra couvre les trois quarts de la pièce.

Mais dans l'angle opposé au bas — sous le lit de camp — il y a un triangle d'ombre d'environ soixante centimètres. Invisible depuis l'objectif.

Tu te déplaces vers le lit. Tu t'accroupis. Dans cet espace minuscule tu examines le sol, les murs.

Une brique légèrement déplacée. Quelqu'un a fait ça avant toi.

Tu l'extrais.

Derrière : une lame de couteau brisée. Rouillée, coupante sur un côté. Et gravé dans la brique, en lettres minuscules :

LA FENÊTRE DU COULOIR S'OUVRE DE L'EXTÉRIEUR.

Quelqu'un d'autre a été ici. Et a survécu assez longtemps pour laisser un message.

La porte s'ouvre.`,
      choices:[
        {label:'Cacher la lame dans ta manche avant qu\'il entre.',next:'cap_hide_blade'},
        {label:'Remettre la brique en place — garder le secret.',next:'cap_matteo_armed'}
      ]
    },
    cap_stare:{
      bg:'cell',speaker:null,
      text:`Tu regardes la caméra. Droit dans l'objectif. Sans ciller.

Trente secondes plus tard, la porte s'ouvre.

L'homme qui entre est grand. Costume sombre. Le genre de visage qu'on oublie exprès — pas de trait distinctif, pas de signe particulier. Conçu pour ne pas être décrit dans un témoignage.

Il s'arrête sur le seuil. Vous vous regardez.

— Tu es plus calme que je pensais.

Sa voix est neutre. Pas cruelle. Pas aimable. Neutre, comme un outil.

— Les hystériques nous fatiguent, reprend-il. Les calmes nous inquiètent.

Il entre. Il laisse la porte ouverte derrière lui — un piège, peut-être, pour voir si tu cours.

Tu ne cours pas.`,
      choices:[
        {label:'"Qu\'est-ce que vous voulez ?"',next:'cap_matteo_talk'},
        {label:'Garder le silence total.',next:'cap_matteo_silence'}
      ]
    },
    cap_hide_blade:{
      bg:'cell',speaker:'SARA',
      text:`Tu glisses la lame dans ta manche gauche une demi-seconde avant que la porte s'ouvre.

L'homme qui entre est différent de ce à quoi tu t'attendais. Pas un garde. Pas un homme de main. Quelqu'un d'autre — plus jeune, trop bien habillé pour cet endroit, avec des yeux qui ont appris à ne rien montrer.

Il pose une chaise face à toi et s'assoie à l'envers, les bras croisés sur le dossier.

— Sara Navarro. Architecte. Vingt-huit ans. Aucun casier. Aucun contact notable dans le milieu.

Il te regarde.

— Tu n'aurais pas dû être dans cette voiture ce soir-là. C'est une erreur. Notre erreur.

Un silence. Puis :

— Mais maintenant tu l'as vu. Et on ne peut pas défaire ça.`,
      choices:[
        {label:'"Qu\'est-ce que j\'ai vu ?"',next:'cap_wrong_place'},
        {label:'Attaquer maintenant — tu as la lame.',die:true,deathMsg:'Tu n\'étais pas assez proche. Il a réagi en une fraction de seconde. La lame est tombée. Lui, non.'}
      ]
    },
    cap_matteo_armed:{
      bg:'cell',speaker:'SARA',
      text:`La brique est remise en place. La lame est dans ta paume fermée derrière ton dos quand il entre.

Il ne ressemble pas à ce que tu imaginais. Trop jeune. Trop propre. Le genre d'homme qui lit des rapports plutôt que de les écrire avec le sang des autres.

Il s'assoie. Il pose un téléphone sur ses genoux — ton téléphone.

— Tu as appelé quelqu'un avant qu'on te prenne, dit-il. Le numéro a été identifié et coupé.

Il te regarde.

— Qui étais-tu en train d'appeler ?`,
      choices:[
        {label:'Mentir : "Ma sœur. Un appel de routine."',next:'cap_lie_sister'},
        {label:'La vérité : "La police."',die:true,deathMsg:'"La police." Il a hoché la tête, presque triste. Puis il a fait un signe à quelqu\'un hors champ. Tu n\'as pas eu le temps de comprendre ce qui se passait.'}
      ]
    },
    cap_matteo_talk:{
      bg:'cell',speaker:'MATTEO',
      text:`— Ce que nous voulons ?

Il s'avance d'un pas. Ses chaussures sont impeccables — détail absurde que ton cerveau enregistre parce que le reste est trop pour être traité d'un coup.

— Ton père travaillait pour nous. Pendant six ans. Et la semaine dernière, il a décidé de s'arrêter. D'une façon qui nous pose un problème.

Il s'accroupit devant toi pour être à ta hauteur. De près ses yeux sont sombres et vides comme des puits avec le fond brisé.

— Toi, tu es la façon dont on s'assure qu'il change d'avis.

Un silence qui pèse des tonnes.

— Et si tu n'as plus de valeur comme levier de pression...

Il n'a pas besoin de finir la phrase.`,
      choices:[
        {label:'"Mon père ne cédera pas pour moi."',next:'cap_bluff'},
        {label:'Demander à parler à ton père.',next:'cap_father_call'}
      ]
    },
    cap_matteo_silence:{
      bg:'cell',speaker:'MATTEO',
      text:`Tu ne dis rien.

Il observe ça avec quelque chose qui ressemble à du respect, s'il en est capable.

— Le silence, dit-il enfin, c'est soit de la sagesse, soit de la stupidité. Les deux ont le même visage.

Il s'avance. Il prend ton menton dans sa main — pas violemment, mais sans te laisser le choix non plus — et te force à le regarder.

— Tu vas parler. Tout le monde parle. La question c'est quand et dans quel état.

Il relâche.

— Je reviendrai dans une heure. Profite du silence pendant qu'il est encore confortable.`,
      choices:[
        {label:'Utiliser cette heure pour explorer la pièce.',next:'cap_camera'},
        {label:'Préparer ce que tu vas lui dire.',next:'cap_bluff'}
      ]
    },
    cap_wrong_place:{
      bg:'warehouse',speaker:'SARA',
      text:`— Tu étais dans la mauvaise voiture au mauvais moment, dit-il. C'est tout.

Ce n'est pas tout. Tu le sais. L'expression qui a traversé son visage quand tu as posé la question — trop rapide, trop contrôlée — te dit qu'il y a autre chose.

— Qu'est-ce que j'ai vu dans cette voiture ?

Il se lève. Il tourne le dos.

— Des visages. Des noms. Des choses qui ne devraient pas exister sur aucune photo, dans aucune mémoire.

Il se retourne.

— Mon patron veut t'éliminer. Je pense qu'il y a une autre option. Mais tu dois me faire confiance.

Dans cette pièce, avec ces liens aux poignets et la lame contre ta peau — faire confiance à quelqu'un est la chose la plus dangereuse que tu puisses faire.`,
      choices:[
        {label:'"Pourquoi vous aiderait-il à me sauver ?"',next:'cap_why_help'},
        {label:'Faire confiance. Accepter son aide.',next:'cap_escape_together'},
        {label:'Refuser. Tu te sors de là seule.',die:true,deathMsg:'Tu t\'es levée trop vite. La porte était verrouillée. Ils t\'ont entendue. Il n\'y a pas eu de deuxième chance.'}
      ]
    },
    cap_lie_sister:{
      bg:'cell',speaker:'SARA',
      text:`— Ma sœur. Un appel de routine.

Il te regarde pendant longtemps. Assez longtemps pour que tu commences à te demander si ton visage trahit quelque chose.

— Tu n'as pas de sœur.

Il pose le téléphone sur le sol et le pousse vers toi avec le pied.

— Essaie encore.

La lame dans ta paume est froide. L'homme devant toi ne l'est pas — il est chaud, vivant, et il occupe tout l'espace de la pièce sans même bouger.

— On a tout le temps, dit-il.`,
      choices:[
        {label:'"Un ami. Je lui avais dit de prévenir la police si je n\'appelais pas."',next:'cap_bluff'},
        {label:'Attaquer maintenant — il est assez proche.',die:true,deathMsg:'Il était plus rapide. Il avait prévu ça. Peut-être depuis le début.'}
      ]
    },
    cap_bluff:{
      bg:'cell',speaker:'SARA',
      text:`— Mon père ne cédera pas pour moi. Vous misez sur quelqu'un qui n'existe plus.

L'homme marque une pause.

C'est un bluff. Un bluff total. Ton père te céderait le monde entier si on le lui demandait — tu le sais, lui le sait, et ce type probablement aussi. Mais le bluff n'est pas là pour être cru. Il est là pour acheter du temps.

— Intéressant, dit-il enfin.

Il se lève. Il marche vers la porte.

— Dans ce cas, tu n'as aucune valeur pour nous.

La porte reste ouverte. Une seconde. Deux.

Il n'a pas bougé.

— À moins que tu aies quelque chose d'autre à nous offrir.`,
      choices:[
        {label:'"Je connais l\'identité de la taupe dans votre organisation."',next:'cap_gamble'},
        {label:'Garder le silence — ne pas surenchérir.',next:'cap_wait_night'}
      ]
    },
    cap_father_call:{
      bg:'mansion',speaker:'SARA',
      text:`La ligne met quatre secondes à sonner.

Ton père décroche à la première.

— Sara.

Un mot. Juste ton prénom. Mais dans sa voix il y a dix ans de secrets qu'il ne t'a jamais dits, et tu les entends tous d'un coup.

— Papa... tu savais.

Pas une question. Tu l'entends dans sa respiration — il ne nie pas.

— Fais ce qu'ils disent, murmure-t-il. S'il te plaît. Fais juste ce qu'ils disent.

L'homme reprend le téléphone. Coupe la communication.

Dans le silence qui suit, quelque chose se reconstruit en toi à la place de ce qui vient de mourir.

La peur, tu la connais. La trahison, c'est nouveau.

Et la trahison — ça rend les gens imprévisibles.`,
      choices:[
        {label:'Utiliser cette rage pour réfléchir — pas pour agir.',next:'cap_gamble'},
        {label:'Craquer. Laisser voir la douleur.',die:true,deathMsg:'Il a vu la fissure. Il a appuyé dessus jusqu\'à ce que tu lui donnes tout ce qu\'il voulait. Après ça, tu n\'avais plus aucune valeur.'}
      ]
    },
    cap_gamble:{
      bg:'mansion',speaker:'SARA',
      text:`— Je connais l'identité de la taupe dans votre organisation.

Ce n'est pas vrai.

Mais il ne le sait pas. Et dans les deux secondes qui suivent — pendant qu'il te regarde avec ces yeux vides et froids qui ne clignent pas assez — tu vois quelque chose traverser son visage.

Pas de la peur. Quelque chose de pire.

De l'incertitude.

— Continue, dit-il.

Sa voix est la même. Son corps est le même. Mais la balance a bougé, imperceptiblement. Tu tiens quelque chose maintenant — même si ce quelque chose n'existe pas.

— Je continue quand je suis dehors.`,
      choices:[
        {label:'Tenir cette position. Ne rien lâcher.',next:'cap_end_escape'},
        {label:'Donner un faux nom — maintenir l\'illusion.',next:'cap_end_deal'}
      ]
    },
    cap_wait_night:{
      bg:'cell',speaker:'SARA',
      text:`Tu ne réponds rien.

Il repart. La porte se ferme.

La nuit dans cette pièce est interminable. Les bruits changent — les voix s'éloignent, une radio quelque part joue quelque chose à bas volume, les rondes du garde deviennent moins régulières vers 3h.

Tu travailles. Sans bruit. Sans lumière. Tes doigts mémorisent chaque centimètre de béton et de métal que tu peux atteindre.

À 4h17, tu as deux choses : un câble électrique arraché du montant du lit et une idée qui va soit te sortir de là, soit te tuer.

Une troisième option n'existe pas.`,
      choices:[
        {label:'Passer à l\'action.',next:'cap_night_escape'},
        {label:'Attendre encore — trop risqué.',next:'cap_end_give'}
      ]
    },
    cap_why_help:{
      bg:'warehouse',speaker:'MATTEO',
      text:`— Parce que j'en ai assez.

C'est dit si simplement que ça sonne faux. Et en même temps — personne n'invente quelque chose d'aussi banal.

— Mon patron élimine tout le monde qui sait trop de choses. Dans six mois, peut-être moins, je saurai trop de choses.

Il te regarde.

— Toi tu as vu des visages. Moi j'ai les noms, les comptes, les preuves. Ensemble on peut sortir d'ici et on peut enterrer ces gens pour de bon.

Tu évalues. Il a l'air sincère. Les gens sincères meurent souvent les premiers dans ce monde.

— Ou tu m'utilises pour sortir et tu disparais, tu dis.

Un silence.

— Oui, dit-il. Ou ça.`,
      choices:[
        {label:'Accepter le deal — tu n\'as pas le choix.',next:'cap_escape_together'},
        {label:'Négocier : lui d\'abord, toi ensuite.',next:'cap_escape_together'}
      ]
    },
    cap_escape_together:{
      bg:'escape',speaker:null,shake:true,
      text:`Il sort par la porte de service à 4h du matin.

Toi trois minutes après, par la même porte, dans la direction opposée.

Le plan tient sur cinq étapes et une promesse que ni l'un ni l'autre ne sait s'il sera encore en vie pour honorer.

Tu cours dans les pins. L'air est froid et humide et tu respires trop vite mais tu t'en fous — tu respires, c'est déjà tout.

Derrière toi, une alarme. Puis deux voix. Puis rien.

Puis les phares d'une voiture sur la nationale.

Tu t'arrêtes au bord de la route, les pieds dans la boue, le coeur à cent soixante.

Tu es vivante.

Tu ne sais pas pour combien de temps.`,
      choices:[{label:'Fin — L\'Évasion',next:'__end__',endTitle:'Vivante',endText:'Tu es sortie. La personne qui t\'a aidée a peut-être fait de même. Ou pas. Le monde dans lequel tu as mis les pieds ne te relâchera pas aussi facilement.'}]
    },
    cap_night_escape:{
      bg:'escape',speaker:'SARA',shake:true,flash:true,
      text:`Le câble électrique claque contre la poignée extérieure au moment où le garde ouvre.

Il trébuche. Tu passes.

Couloir. Gauche. Escalier de service. La fenêtre du couloir — celle dont la brique t'avait parlé — s'ouvre avec une pression sur le montant supérieur gauche.

L'air de la nuit.

Tu tombes à deux mètres cinquante sur de l'herbe mouillée. Ton genou gauche dit quelque chose que tu n'as pas le temps d'écouter.

Tu cours.

Derrière toi l'alarme se déclenche — stridente, violente. Des cris. Des torches électriques qui balayent les pins.

Tu cours jusqu'à ce que tes jambes refusent de continuer.

Jusqu'à ce que la nationale apparaisse entre les arbres comme une promesse.`,
      choices:[{label:'Fin — La Fugitive',next:'__end__',endTitle:'En Fuite',endText:'Tu t\'es échappée. Ce qu\'ils te voulaient, tu ne le sais toujours pas. Et cette ignorance, la nuit, te brûle autant que la peur.'}]
    },
    cap_end_escape:{bg:'night',speaker:null,text:`Une heure plus tard, tu es dans une voiture de police.

Quarante-huit heures plus tard, les hommes qui t'ont enlevée sont en garde à vue. Trente et un d'entre eux.

Ton père est parmi eux.

Tu n'as pas pleuré dans le commissariat. Tu ne pleures pas maintenant. Tu regardes par la fenêtre la ville qui ne s'arrête jamais, et tu penses à ce que ça fait d'apprendre que quelqu'un que tu aimais te connaissait si peu.`,choices:[{label:'Fin — La Vérité Coûte',next:'__end__',endTitle:'Libre',endText:'Libre. Mais certaines cages n\'ont pas de barreaux visibles.'}]},
    cap_end_deal:{bg:'mansion',speaker:null,text:`Il vérifie le nom. Il appelle quelqu'un. Il revient.

— Comment tu le sais ?

— Je travaillais avec mon père. J'ai vu des transferts qui ne correspondaient à rien.

Moitié vrai. Assez vrai pour tenir.

Il te libère à l'aube. Une voiture t'emmène à quinze kilomètres. On te rend ton téléphone — sans la carte SIM.

Tu n'appelles pas la police. Pas encore.

D'abord tu as besoin de savoir combien de temps ton père t'a utilisée sans que tu le saches.`,choices:[{label:'Fin — Le Prix',next:'__end__',endTitle:'Libre à Quel Prix',endText:'Tu as survécu. La personne qui t\'a kidnappée t\'a libérée. Et ton père, lui, t\'a livrée.'}]},
    cap_end_give:{bg:'cell',speaker:null,text:`Au matin tu leur donnes ce qu'ils veulent.

Pas parce que tu as cédé. Parce que tu as calculé.

Ils te libèrent comme promis. Trois semaines plus tard, les données que tu leur as fournies ont été rendues publiques dans une fuite anonyme — la tienne.

L'organisation s'effondre. Ton père plaide coupable.

Certaines sorties ressemblent à des défaites jusqu'à ce qu'on comprenne qui a réellement joué qui.`,choices:[{label:'Fin — Le Long Jeu',next:'__end__',endTitle:'Le Long Jeu',endText:'Tu leur as donné exactement ce qu\'il fallait pour les détruire. Eux ne l\'ont jamais compris.'}]}
  }
},

interrogation:{
  title:'SANG FROID',color:'#8B5A00',
  scenes:{
    start:{
      bg:'office',chapter:'I',chTitle:'48 Heures',speaker:null,
      text:`La salle de briefing a l'odeur des décisions irréversibles.

Reyes pose une photo sur la table. Un homme ordinaire. Quarante ans. Le genre de visage qu'on voit dans le métro et qu'on oublie avant d'arriver à destination.

— Sebastián Narvez. Logisticien du réseau Solano. On le tient depuis hier soir. Il sait où passe le prochain convoi — vendredi, avant l'aube. Après ça les traces disparaissent et le réseau change de structure.

Il te regarde.

— Quarante-huit heures, Damián. Tu as carte blanche.

Carte blanche. Un euphémisme pour ce que vous faites tous les deux semblant de ne pas nommer.

Tu ramasses la photo.

— Je veux le dossier complet. Et je veux être seul avec lui.`,
      choices:[
        {label:'Lire le dossier. Connaître l\'homme avant la pièce.',next:'inter_study'},
        {label:'Y aller maintenant. L\'élément de surprise.',next:'inter_cold'}
      ]
    },
    inter_study:{
      bg:'office',speaker:'DAMIÁN',
      text:`Sebastián Narvez.

Quarante-deux ans. Né dans le Barrio Kennedy à Bogotá. Cinq frères et sœurs. Aucun antécédent jusqu'à trente-quatre ans — et puis la plongée progressive, presque logique, vers le seul marché du travail accessible dans son quartier.

Une fille. Valentina. Neuf ans en novembre. Photo jointe : une gamine qui sourit trop grand pour la photo scolaire.

Sa femme est morte d'un cancer il y a quatre ans. Il élève la petite chez sa belle-mère depuis.

Tu poses le dossier.

Ce n'est pas de la pitié que tu ressens. Tu n'as plus les circuits pour ça. C'est de l'analyse : tu sais maintenant exactement quelle touche appuyer et dans quel ordre.

Ça devrait peut-être te déranger davantage.`,
      choices:[
        {label:'Entrer dans la salle.',next:'inter_enter'},
        {label:'Chercher une approche qui évite la violence.',next:'inter_clever'}
      ]
    },
    inter_cold:{
      bg:'interrogation',speaker:null,
      text:`Tu entres sans dossier, sans préparation, sans théorie.

Juste toi et lui et la lumière trop forte qui lui mange les yeux depuis hier soir.

Il lève la tête quand tu t'assieds. Il a essayé de dormir dans cette chaise — ça se voit à la façon dont son cou s'est raidi.

Vous vous regardez.

Tu ne dis rien.

Une minute. Deux. Le silence dans une pièce comme celle-ci n'est pas neutre. Il a du poids, du volume, une texture.

Il commence à fissurer les gens qui ont quelque chose à cacher.

— Je veux un avocat.

Sa voix est ferme. Il a décidé de tenir sur cette ligne. C'est son droit. C'est aussi la ligne que tu vas passer la prochaine journée à éroder.`,
      choices:[
        {label:'"Dans quarante-huit heures. Peut-être."',next:'inter_threat'},
        {label:'Ignorer. Continuer le silence.',next:'inter_silence'}
      ]
    },
    inter_enter:{
      bg:'interrogation',speaker:'DAMIÁN',
      text:`Il lève les yeux quand tu entres.

Il cherche quelque chose dans ton visage — une indication, un signe qui lui dira comment calibrer sa résistance. Tu ne lui en donnes pas.

Tu t'assieds en face de lui. Tu poses le dossier fermé sur la table. Tu ne l'ouvres pas.

— Je sais pour Valentina, tu dis enfin. Elle va avoir neuf ans en novembre. Elle aime les chats et les mangas. Elle est dans le top cinq de sa classe.

Son visage ne bouge pas. Mais son corps, lui — quelque chose dans ses épaules, infinitésimal.

— Je ne dis pas ça pour te menacer. Je dis ça pour que tu comprennes que je sais à quoi tu tiens. Et pour que tu saches que j'ai le pouvoir de m'assurer qu'elle ne manque de rien.

Tu poses les mains sur la table.

— Ou l'inverse.`,
      choices:[
        {label:'Laisser ce silence faire son travail.',next:'inter_silence'},
        {label:'Aller plus loin — sortir la photo de Valentina.',next:'inter_valentina'}
      ]
    },
    inter_clever:{
      bg:'office',speaker:'DAMIÁN',
      text:`La violence laisse des traces. Les traces créent des problèmes. Et les informations obtenues sous contrainte physique ont un taux d'erreur qui peut ruiner une opération entière.

Tu sais ça mieux que Reyes ne veut l'admettre.

Tu appelles le médecin de garde. Tu fais préparer une chambre propre — pas une cellule. Une chambre. De la nourriture décente. Une douche.

Quand tu entres dans la salle d'interrogatoire, tu es seul, et tu portes deux cafés.

Narvez te regarde comme si c'était un piège.

— C'en est un, tu dis en posant un café devant lui. Tout l'est. Mais le café est vrai.`,
      choices:[
        {label:'Jouer la carte de l\'humanité — voir jusqu\'où ça va.',next:'inter_deal'},
        {label:'Trop lent. Changer d\'approche.',next:'inter_threat'}
      ]
    },
    inter_silence:{
      bg:'interrogation',speaker:'DAMIÁN',
      text:`Vingt-deux minutes.

Tu comptes. Lui aussi — à la façon dont ses yeux dérivent vers le mur puis reviennent, cherchant quelque chose à quoi s'accrocher.

Le silence dans une salle d'interrogatoire est une forme de violence. La plus propre. Celle qui ne laisse pas de marque.

— Je sais pas de quoi vous parlez, dit-il enfin.

Sa voix s'est légèrement affaissée depuis le début. Subtil, mais là.

— Je me suis jamais dit ça à moi-même, tu réponds. "Je ne sais pas de quoi tu parles." Parce que mentir à quelqu'un qui tient ta vie entre ses mains, c'est le genre d'erreur qui se paye en nature.

Tu te lèves. Tu contournes la table.

— Quarante-six heures restantes. Repose-toi un peu.

Tu sors. Tu le laisses seul.

Le vide, parfois, est le meilleur interrogateur.`,
      choices:[
        {label:'Revenir dans deux heures — frappe suivante.',next:'inter_hour12'},
        {label:'Envoyer quelqu\'un d\'autre d\'abord — le déstabiliser.',next:'inter_reyes'}
      ]
    },
    inter_threat:{
      bg:'interrogation',speaker:'DAMIÁN',
      text:`— Dans quarante-huit heures. Peut-être.

Il entend le "peut-être". Il sait ce que ça veut dire dans une pièce comme celle-ci.

— C'est illégal.

— Oui.

Tu n'argumentes pas. L'honnêteté sur ce point est plus efficace que les euphémismes.

— Sebastián. Je vais te dire comment ça va se passer. Dans dix heures, tu auras envie de parler mais tu tiendras. Dans vingt heures, tu commenceras à calculer ce que tu peux me donner sans te condamner. Dans trente-cinq heures, le calcul changera parce que ton corps t'aura abandonné avant ta tête.

Tu t'assieds.

— Moi je préférerais qu'on saute les trente premières heures. Pas par pitié. Par efficacité.`,
      choices:[
        {label:'Lui donner le temps de réfléchir.',next:'inter_hour12'},
        {label:'Commencer maintenant — escalade immédiate.',next:'inter_physical'}
      ]
    },
    inter_valentina:{
      bg:'interrogation',speaker:'DAMIÁN',
      text:`Tu sors la photo de Valentina.

Tu la poses sur la table — pas avec brutalité, mais avec une précision qui est pire que la brutalité.

Il ne la regarde pas. Ou plutôt — il la regarde sans avoir l'air de la regarder, les yeux légèrement défocalisés.

Un mécanisme de protection. Il essaie de ne pas la rendre réelle dans cette pièce.

— Elle ressemble à sa mère, tu dis. D'après le dossier.

Sa mâchoire se contracte. Une fois.

— Tu es un monstre, dit-il. Voix plate. Constat.

— Oui, tu réponds. C'est pour ça que tu devrais me parler avant que mon supérieur décide de prendre le relais. Lui, c'est pire.`,
      choices:[
        {label:'Laisser ça poser. Sortir.',next:'inter_hour12'},
        {label:'Pousser encore — aller plus loin.',next:'inter_physical'}
      ]
    },
    inter_deal:{
      bg:'interrogation',speaker:'DAMIÁN',
      text:`Il boit le café sans te quitter des yeux.

— Qu'est-ce que vous voulez ?

— Une adresse. Une date. Peut-être un nom.

— Et en échange ?

Tu t'assieds. Tu poses les mains à plat.

— Ta fille ne figure dans aucun de nos fichiers. Le dossier sur toi disparaît. Et quand tu sortiras — parce que tu sortiras, cinq ans maximum avec témoignage de coopération — il n'y aura personne qui t'attendra pour régler des comptes.

— Vous pouvez garantir ça ?

— Non. Je peux te donner ma parole.

— La parole d'un homme qui m'a enlevé de mon lit à 3h du matin.

— Exactement cette parole-là.`,
      choices:[
        {label:'Attendre sa réponse.',next:'inter_breaking'},
        {label:'Ajouter de la pression — le temps presse.',next:'inter_hour12'}
      ]
    },
    inter_reyes:{
      bg:'blood',speaker:null,flash:true,shake:true,
      text:`Reyes entre dans la salle.

Tu l'observes de l'autre côté de la vitre sans tain.

Les méthodes de Reyes sont efficaces — dans le sens où elles obtiennent toujours quelque chose. Ce quelque chose n'est pas toujours vrai, n'est pas toujours utile, et n'est jamais propre.

Quand tu rentres dans la salle quarante minutes plus tard, l'atmosphère a changé. Narvez a changé.

Il te regarde. Et dans ce regard il y a une question : est-ce que tu es le pire ou le moins pire ?

— L'autre homme ne revient pas, tu dis. Mais ça dépend de ce que tu m'as à dire.`,
      choices:[
        {label:'Écouter ce qu\'il a à dire.',next:'inter_breaking'},
        {label:'Lui donner le temps de se ressaisir.',next:'inter_hour24'}
      ]
    },
    inter_hour12:{
      bg:'interrogation',speaker:null,
      text:`Heure douze.

Tu entres. Tu poses de l'eau. Tu t'assieds.

Il a les yeux rouges. Pas de larmes — de manque de sommeil, de lumière constante, d'attente.

— Vendredi, tu dis. Avant l'aube. C'est tout ce que je veux savoir. Une date, une heure, un endroit.

Il secoue la tête.

— Si je parle, je suis mort.

— Si tu ne parles pas, tu es mort aussi. La différence c'est le délai.

Tu te lèves.

— Et Valentina a besoin de son père vivant. Même derrière des barreaux, c'est mieux que rien.

Tu sors à nouveau.

Derrière toi, dans le silence de la pièce, tu entends quelque chose qui n'est pas tout à fait un sanglot.

Presque.`,
      choices:[
        {label:'Revenir dans douze heures.',next:'inter_hour24'},
        {label:'Escalader — tu n\'as plus de temps.',next:'inter_physical'}
      ]
    },
    inter_physical:{
      bg:'blood',speaker:'DAMIÁN',flash:true,shake:true,
      text:`Ce qui se passe dans les six heures suivantes, tu n'en parleras jamais.

Pas parce que c'est illégal — il y a des choses illégales qui valent la peine d'être racontées. Parce que les mots pour le décrire rendraient la chose trop réelle, trop concrète, trop là.

Narvez tient longtemps. Plus longtemps que tu ne t'y attendais. C'est presque du respect.

Quand il parle, sa voix est méconnaissable. Pas de douleur, à ce stade — les circuits pour ça sont coupés. C'est quelque chose de plus profond. De plus permanent.

Tu sors de la salle à l'aube.

Le couloir est normal. L'air est normal. Tu t'appuies contre le mur et tu restes là un moment.

Reyes arrive. Il lit les informations dans ta main.

— Bien joué, dit-il.`,
      choices:[
        {label:'"Bien joué."',next:'inter_end_hard'},
        {label:'Rien dire.',next:'inter_end_doubt'}
      ]
    },
    inter_hour24:{
      bg:'interrogation',speaker:null,
      text:`Heure vingt-quatre.

Il a arrêté d'essayer de dormir. Ses épaules tiennent encore mais elles penchent légèrement vers l'avant — comme si le poids de ce qu'il garde était devenu physique.

Tu entres avec une chaise. Tu t'assieds près de lui, pas en face. Côte à côte.

— Raconte-moi ta fille.

Il te regarde, méfiant.

— Je ne veux pas d'information. Raconte-moi juste.

Un long silence. Et puis — lentement, comme s'il débouchait quelque chose qui était fermé depuis trop longtemps — il parle.

De Valentina. De ses dessins sur le réfrigérateur. De la façon dont elle mange les céréales dans un ordre particulier.

Il parle pendant vingt minutes.

À la fin, il y a des larmes sur son visage. Il ne s'en rend pas compte.`,
      choices:[
        {label:'"Le convoi. Maintenant."',next:'inter_breaking'},
        {label:'Lui laisser une nuit de plus.',next:'inter_breaking'}
      ]
    },
    inter_breaking:{
      bg:'interrogation',speaker:'NARVEZ',
      text:`Il parle.

Zaragoza. Un entrepôt frigorifique dans la zone portuaire. Vendredi, entre 4h et 5h du matin. Deux camions. Dix-sept personnes. Le contact s'appelle El Pato — tu n'apprendras jamais son vrai nom.

Il parle pendant douze minutes sans s'arrêter.

Et quand il s'arrête, la pièce est silencieuse.

— C'est fini maintenant ? murmure-t-il.

Tu rassembles tes notes.

— Oui.

Premiere fois que tu lui dis la vérité sans calcul derrière.`,
      choices:[{label:'Transmettre l\'information.',next:'inter_end_clean'}]
    },
    inter_end_clean:{bg:'office',speaker:null,text:`Vendredi, 4h22. L'opération intercepte les deux camions à Zaragoza.

Dix-sept arrestations. Zéro victime.

Le rapport officiel est propre. Les méthodes utilisées sont décrites en termes neutres — procédure standard, coopération du détenu.

Narvez plaidera coupable. Cinq ans, avec les arrangements que tu avais promis.

Tu lis le rapport officiel une fois. Tu ne le relis pas.`,choices:[{label:'Fin — Résultat Net',next:'__end__',endTitle:'Résultat Net',endText:'L\'opération a réussi. Le rapport est propre. Toi, moins.'}]},
    inter_end_hard:{bg:'office',speaker:null,text:`Dans la semaine qui suit tu dors mal.

Pas à cause de cauchemars — tu n'as plus les circuits pour ça non plus. C'est plus simple et plus difficile que ça : à 3h du matin tu es réveillé et tu comptes. Les coûts. Les résultats. Le ratio.

Reyes appelle pour te féliciter.

Tu ne décroches pas.`,choices:[{label:'Fin — Le Ratio',next:'__end__',endTitle:'Le Ratio',endText:'Chaque résultat a un coût. Tu as arrêté de croire que le ratio était acceptable il y a longtemps. Tu continues quand même.'}]},
    inter_end_doubt:{bg:'office',speaker:null,text:`Tu ne réponds pas à Reyes.

Tu t'assieds dans ta voiture dans le parking souterrain et tu restes là jusqu'à ce que le soleil soit levé.

Le convoi sera intercepté. Des gens seront sauvés — statistiquement, c'est ce que ça signifie.

Dans ta tête, Narvez compte les céréales de Valentina dans un ordre particulier.

Ces deux choses coexistent. Elles continueront de coexister.

Tu mets le contact. Tu rentres chez toi.`,choices:[{label:'Fin — Coexistence',next:'__end__',endTitle:'Deux Vérités',endText:'Tu as fait ce qu\'il fallait. Et tu porteras ce que ça a coûté. Ces deux affirmations sont vraies en même temps.'}]}
  }
},

journalist:{
  title:'LA VÉRITÉ SAIGNE',color:'#1A5A8B',
  scenes:{
    start:{
      bg:'night',chapter:'I',chTitle:'Le Message',speaker:null,
      text:`Le téléphone crypté vibre à 23h38.

Pas de nom. Pas de numéro. Juste une adresse dans la zone portuaire et deux mots.

VENEZ SEULE.

Tu es Elena Moreau. Trois semaines que tu enquêtes sur les disparitions dans ce secteur. Deux articles publiés, deux convocations au commissariat, un rédacteur en chef qui t'a appelée hier pour te demander de "peser les risques".

Tu ne pèses pas les risques. Tu trouves les histoires.

Tu attrapes ton appareil photo. Tu mets ton gilet pare-balles sous ta veste — pas par peur, par habitude. Et tu sorts dans la nuit barcelonaise.`,
      choices:[
        {label:'Prévenir quelqu\'un avant de partir.',next:'journ_backup'},
        {label:'Y aller seule. Vraiment seule.',next:'journ_alone'}
      ]
    },
    journ_backup:{
      bg:'night',speaker:'ELENA',
      text:`Tu envoies un message à Marco — photographe, ami, discret.

Adresse. Heure. "Si pas de nouvelles dans 90 min, appelle le 17 et Sébastien dans cet ordre."

Il répond en six secondes : "Non."

Tu rappelles. Il décroche avant la première sonnerie.

— Elena. Non.

— Marco.

— Ils ont retrouvé Jiménez dans le port. Les poignets ligotés dans le dos.

— Je sais.

— Jiménez enquêtait sur la même chose que toi.

— Je sais.

Un silence.

— 90 minutes, tu répètes. Cet ordre.

Tu raccroches.

Dans le taxi, tu vérifies l'objectif de ton appareil. Tes mains ne tremblent pas encore.`,
      choices:[{label:'Arriver à l\'adresse.',next:'journ_arrive'}]
    },
    journ_alone:{
      bg:'night',speaker:'ELENA',
      text:`Tu n'appelles personne.

Ce n'est pas du courage. C'est du calcul — si tu préviens quelqu'un, l'information filtre, la source disparaît, l'histoire meurt avec elle.

Tu laisses quand même un carnet dans ta veste avec l'adresse, la date, l'heure. Si quelqu'un te retrouve, il aura ça.

Dans le taxi, le chauffeur essaie de faire la conversation. Tu regardes par la fenêtre la ville qui défile et tu penses à Jiménez, retrouvé dans le port la semaine dernière.

Jiménez enquêtait sur la même zone.

Tu descends du taxi deux rues avant l'adresse.

Tu fais le reste à pied, dans l'ombre.`,
      choices:[{label:'Arriver à l\'adresse.',next:'journ_arrive'}]
    },
    journ_arrive:{
      bg:'warehouse',speaker:null,
      text:`L'entrepôt frigorifique désaffecté ouvre sur un couloir sombre qui sent le froid industriel et quelque chose en dessous — chimique, épicé, difficile à identifier et impossible à oublier une fois qu'on le connaît.

Tu connais cette odeur depuis quatre ans. Depuis le Rwanda.

C'est l'odeur de ce que les hommes font aux autres hommes.

Une lampe torche posée sur une caisse, allumée, dirigée vers le fond.

Tu avances.

Ce que tu trouves au fond de cet entrepôt va changer quelque chose en toi. Irrémédiablement.

Tu sors ton appareil photo.

Tu fais ton travail.`,
      choices:[
        {label:'Photographier méthodiquement avant tout.',next:'journ_evidence'},
        {label:'Chercher d\'abord si quelqu\'un est encore là.',next:'journ_search'}
      ]
    },
    journ_search:{
      bg:'warehouse',speaker:'ELENA',flash:true,
      text:`Dans l'angle le plus sombre — une forme.

Un homme. Recroquevillé contre le mur, les bras autour des genoux. Il lève les yeux quand ta lampe l'atteint et tu vois dedans quelque chose qui te prend par la gorge.

Pas de la douleur. Pas encore. Juste le vide de quelqu'un qui a été vidé.

Il a peut-être quarante ans. Il en paraît vingt de plus.

— Ils sont partis, murmure-t-il.

Sa voix est cassée dans des endroits qui ne se réparent pas tout seuls.

— Je m'appelle Elena. Je suis journaliste. Tu es en sécurité.

Le mensonge le plus utile que tu aies jamais dit.`,
      choices:[
        {label:'L\'écouter. Enregistrer.',next:'journ_survivor'},
        {label:'Appeler les secours en premier.',next:'journ_call_first'}
      ]
    },
    journ_survivor:{
      bg:'warehouse',speaker:null,
      text:`Il s'appelle Rafa. Trente-huit ans.

Il parle pendant vingt-cinq minutes.

Tu enregistres. Tu hais que tu enregistres mais tu enregistres parce que c'est pour ça que tu es là et parce que son témoignage pourrait être le seul qui existe jamais.

Il parle des questions. Toujours les mêmes questions sur des noms, des dates, des transactions. Il parle de ce qui se passait quand les réponses ne venaient pas. Il parle des autres qui étaient là avant lui.

Sa voix est plate parce que c'est le seul registre qu'il lui reste.

— Tu as vu leurs visages ? tu demandes.

— Un seul. Sans cagoule.

La description qu'il te donne — tu la reconnais.

Tu as vu ce visage. En photo. Dans un dossier confidentiel qu'une source t'avait montré trois mois avant de disparaître.`,
      choices:[
        {label:'Lui demander de répéter. Te souvenir de chaque détail.',next:'journ_vega_link'},
        {label:'Appeler les secours. Il passe avant l\'histoire.',next:'journ_call_first'}
      ]
    },
    journ_evidence:{
      bg:'warehouse',speaker:'ELENA',
      text:`Tu travailles vite et en silence.

Les anneaux fixés aux murs — nouveaux, les vis encore brillantes. Les traces au sol qui ne sont pas des traces d'eau. Le matériel médical dans un sac plastique : seringues, glucomètre, anti-douleurs. Pour maintenir les gens en vie assez longtemps.

Dans le coin le plus sombre — un téléphone brûlé à moitié. Et sous lui, protégé par le métal fondu : un carnet.

Des noms. Des dates. Des montants. Des initiales.

Tu photographies chaque page avant même de les lire.

Et puis tu lis.

Et ta main tremble — pas de peur. De compréhension.

Ce n'est pas un réseau de disparitions. C'est une organisation. Structurée, financée, protégée.

En haut de la liste des financeurs, les initiales R.V.`,
      choices:[
        {label:'R.V. — chercher qui se cache derrière.',next:'journ_rv_search'},
        {label:'Publier maintenant — avant de disparaître.',die:true,deathMsg:'Tu as publié avant de comprendre qui te lisait. Ils ont vu l\'article cinq minutes après la mise en ligne. Tu n\'as pas eu le temps d\'en écrire un deuxième.'}
      ]
    },
    journ_call_first:{
      bg:'warehouse',speaker:'ELENA',
      text:`Tu appelles le 112.

Pendant qu'ils arrivent — sept minutes — tu travailles. L'appareil photo clique en continu.

Le carnet que tu trouves dans le coin, tu le prends. Tu ne devrais pas. Tu le prends quand même.

Quand les ambulanciers arrivent, tu leur remets Rafa. Tu donnes ton identité. Tu réponds aux questions de la police.

Dans ton sac, le carnet.

Dans le taxi du retour, tu le lis.

À la deuxième page, les initiales R.V. apparaissent sept fois.

Ton téléphone sonne. Numéro inconnu.`,
      choices:[
        {label:'Répondre.',next:'journ_call_response'},
        {label:'Ne pas répondre. Rentrer. Analyser d\'abord.',next:'journ_vega_name'}
      ]
    },
    journ_rv_search:{
      bg:'office',speaker:'ELENA',
      text:`R.V. Dans ta mémoire, dans tes archives, dans les conversations à mi-voix de couloirs de commissariats que tu n'aurais pas dû entendre.

Rafael Vega.

Un nom sans dossier public. Sans passé officiel. Sans photo accessible. Le genre d'absence qui coûte très cher à entretenir.

Il apparaît dans les marges de quatre autres enquêtes que tu as menées — jamais au centre, toujours à la périphérie. Toujours la même distance de sécurité.

Tu envoies les photos du carnet à trois serveurs différents.

Puis ton téléphone sonne.

Numéro inconnu.

— Señorita Moreau, dit la voix. Je sais que vous êtes chez vous. Je sais ce que vous venez de trouver. Et je pense qu'on devrait se parler avant que vous fassiez quelque chose d'irréparable.`,
      choices:[
        {label:'"Où et quand ?"',next:'journ_meet'},
        {label:'Raccrocher et partir de chez toi immédiatement.',die:true,deathMsg:'Tu as raccroché. Tu as pris ton sac. La porte de ton appartement s\'est ouverte de l\'extérieur avant que tu atteignes la serrure.'}
      ]
    },
    journ_vega_link:{
      bg:'warehouse',speaker:'ELENA',
      text:`Rafael Vega.

Tu l'as vu en photo une fois — une seule, dans un dossier que ta source Ana Reyes t'avait montré un vendredi soir dans son bureau. Le lundi suivant, Ana avait demandé sa mutation. Elle ne t'a plus jamais répondu.

Tu appelles les secours pour Rafa. Tu l'attendues avec lui jusqu'aux ambulanciers.

Dans le taxi, tu regardes les photos que tu as prises de son visage quand il décrivait l'homme sans cagoule.

Ton téléphone sonne.

Numéro inconnu.`,
      choices:[
        {label:'Répondre.',next:'journ_call_response'},
        {label:'Laisser sonner.',next:'journ_vega_name'}
      ]
    },
    journ_vega_name:{
      bg:'night',speaker:'ELENA',
      text:`Tu rentres. Tu verrouilles à double tour.

Tu travailles jusqu'à 4h du matin avec tout ce que tu as sur R.V.

Rafael Vega. Né à Valence, selon une seule source dont tu ne peux pas vérifier la fiabilité. Quarante ans environ. Aucun casier, aucun procès, aucune comparution. Une présence fantôme dans une dizaine d'enquêtes non résolues sur trois continents.

Ce n'est pas un criminel. C'est une architecture.

À 4h12, quelqu'un sonne à ton interphone.

Tu ne réponds pas.

À 4h13, une enveloppe passe sous ta porte.

Dedans : une photo de toi dans l'entrepôt ce soir. Prise depuis l'intérieur. Il y avait quelqu'un d'autre là-bas.

Et en dessous : un numéro de téléphone.`,
      choices:[
        {label:'Appeler le numéro.',next:'journ_call_response'},
        {label:'Appeler la police.',die:true,deathMsg:'La police est arrivée. Ils ont pris le carnet comme pièce à conviction. Deux jours plus tard, le dossier a été classé. L\'histoire est morte avec lui.'}
      ]
    },
    journ_call_response:{
      bg:'night',speaker:'VOIX',
      text:`— Rafael Vega.

Tu le dis comme une certitude.

Un silence de deux secondes.

— Vous êtes meilleure que je pensais, dit-il. Sa voix — grave, mesurée, sans affect. — Ce que vous avez trouvé ce soir dans cet entrepôt n'est pas mon œuvre.

— Mais votre financement y figure.

— Oui. Ce qui me place dans une position inconfortable vis-à-vis de votre enquête. Et de vous.

Tu n'es pas dans ton appartement — tu as changé de position deux fois pendant qu'il parlait, par instinct.

— Pourquoi m'appeler ?

— Parce que l'autre option était plus définitive. Et moins intéressante.

Un silence.

— Il y a un café. Rue del Carme. Dans vingt minutes. Je vous expliquerai pourquoi vous devriez me laisser vous aider.`,
      choices:[
        {label:'Y aller.',next:'journ_meet'},
        {label:'Refuser. Publier ce que tu as.',next:'journ_publish'}
      ]
    },
    journ_meet:{
      bg:'mansion',speaker:null,
      text:`Le café est fermé. La porte de derrière est ouverte.

Il est assis dans le fond, dos au mur. Cette position n'est jamais un hasard.

Grand. Un costume gris qui n'a pas besoin de marque pour coûter cher. Des yeux sombres qui t'évaluent avant même que tu t'assoies.

Tu t'assieds. Tu ne touches pas le café devant toi.

— L'entrepôt appartient aux frères Salazar, dit-il sans préambule. Ce sont des adversaires. L'argent que vous avez trouvé sous mes initiales a été placé là pour créer une association entre leur travail et mon nom.

— Vous me demandez de vous croire sur parole.

— Je vous demande de vérifier. Voilà les preuves que j'ai de mon côté.

Il pose un dossier sur la table.

— Pourquoi moi ?

— Parce que vous enquêtez depuis trois semaines sans vous arrêter. Et parce que les gens qui ne s'arrêtent pas finissent soit par trouver, soit par être trouvés.

Il te regarde.

— Je préfère que ce soit vous qui trouviez.`,
      choices:[
        {label:'Prendre le dossier. Vérifier avant de publier.',next:'journ_end_deal'},
        {label:'"Je ne travaille pas pour les sujets de mes enquêtes."',next:'journ_end_defiance'},
        {label:'Faire semblant d\'accepter — et publier quand même.',die:true,deathMsg:'Il a su. Il savait avant que tu partes. Les gens qui font ça depuis assez longtemps reconnaissent ce sourire.'}
      ]
    },
    journ_publish:{
      bg:'office',speaker:'ELENA',flash:true,
      text:`Tu publies à 3h17 du matin.

À 5h, dix mille partages. À 7h, les grandes agences reprennent. À 8h, trois journalistes te contactent pour des interviews.

À 9h, Marco t'appelle.

— Elena. Ton appartement.

Tu y es plus depuis cette nuit.

— Qu'est-ce qu'il s'est passé ?

— Quelqu'un est entré. Rien de volé. Mais ils ont laissé quelque chose.

Un silence.

— Une photo de toi. Prise hier soir dans l'entrepôt.

Tu regardes la rue devant toi. Les gens qui marchent. Le soleil de 9h.

L'histoire tourne. Et quelqu'un veut s'assurer que tu ne pourras pas écrire la suite.`,
      choices:[
        {label:'Continuer — tu ne t\'arrêtes pas.',next:'journ_end_defiance'},
        {label:'Appeler Vega. Comprendre ce que tu as déclenché.',next:'journ_call_response'}
      ]
    },
    journ_end_deal:{bg:'mansion',speaker:null,text:`Tu vérifies pendant soixante-douze heures.

Chaque élément du dossier tient. Les frères Salazar. Les comptes. La structure.

Ton article paraît une semaine plus tard. Il est différent — plus précis, plus profond, plus dévastateur pour les bonnes personnes.

Les Salazar sont arrêtés dans les quarante-huit heures.

Vega, lui — Vega reste où Vega a toujours été.

Il t'envoie un message une semaine après la publication.

"Bon travail, señorita Moreau."

Tu ne réponds pas. Mais tu gardes le message.`,choices:[{label:'Fin — La Source',next:'__end__',endTitle:'La Source',endText:'Tu as publié la vérité. Quelqu\'un t\'a aidée à la trouver. Ces deux choses coexistent inconfortablement — et continueront de le faire.'}]},
    journ_end_defiance:{bg:'night',speaker:null,text:`Tu te lèves.

— Je ne travaille pas avec mes sujets. Et je ne vous laisserai pas orienter mon enquête.

Tu marches vers la sortie.

— Señorita Moreau.

Tu t'arrêtes. Tu ne te retournes pas.

— Je respecte ça. Plus que vous ne le croiriez.

Tu sors.

Ton article paraît. Il déclenche la plus grande enquête judiciaire ouverte en trois ans sur les réseaux de disparitions portuaires.

Tu ne sais pas si Vega est coupable de ce soir-là ou pas. Tu as publié ce que tu as pu prouver.

C'est ce que le métier veut dire.`,choices:[{label:'Fin — La Ligne',next:'__end__',endTitle:'La Ligne',endText:'Tu n\'as pas plié. C\'est ce qui définit ce métier. Et ce qui le rend mortel.'}]}
  }
}

};

let audioCtx=null,musicOn=false,musicInterval=null;
let currentStory=null,paused=false,pausedText='',pausedIdx=0,pausedChoices=null,lastScene='start';
let typing=false,typeTimer=null;

function initAudio(){if(audioCtx)return;audioCtx=new(window.AudioContext||window.webkitAudioContext)()}
function playNote(f,t,d,v=.07){
  const o=audioCtx.createOscillator(),g=audioCtx.createGain(),fi=audioCtx.createBiquadFilter();
  fi.type='lowpass';fi.frequency.value=1100;o.type='triangle';o.frequency.value=f;
  g.gain.setValueAtTime(0,t);g.gain.linearRampToValueAtTime(v,t+.04);g.gain.exponentialRampToValueAtTime(.001,t+d);
  o.connect(fi);fi.connect(g);g.connect(audioCtx.destination);o.start(t);o.stop(t+d+.1)
}
const PROGS=[[110,138.59,164.81,220,261.63],[98,123.47,146.83,196,246.94],[116.54,146.83,174.61,233.08]];
function playChord(){
  if(!audioCtx||!musicOn||paused)return;
  const p=PROGS[Math.floor(Math.random()*PROGS.length)],now=audioCtx.currentTime;
  [...p].sort(()=>Math.random()-.5).forEach((f,i)=>{
    const off=i*.4+Math.random()*.2;
    playNote(f,now+off,5+Math.random()*3);
    if(Math.random()>.5)playNote(f*2,now+off+.8,3,.025);
    if(i===0)setTimeout(()=>{const tb=document.getElementById('game-textbox');tb.style.borderTopColor='rgba(255,255,255,.14)';setTimeout(()=>tb.style.borderTopColor='rgba(255,255,255,.06)',400)},off*1000)
  })
}
function toggleMusic(){
  initAudio();musicOn=!musicOn;
  document.getElementById('music-btn').textContent=musicOn?'♪ ON':'♪ OFF';
  if(musicOn){playChord();musicInterval=setInterval(playChord,7000)}
  else clearInterval(musicInterval)
}
function togglePause(){
  paused=!paused;
  document.getElementById('pause-btn').textContent=paused?'▶':'⏸';
  if(paused){clearTimeout(typeTimer);if(musicOn)clearInterval(musicInterval)}
  else{if(musicOn){playChord();musicInterval=setInterval(playChord,7000)}if(typing)resumeType()}
}
function showMenu(){
  document.getElementById('s-game').classList.add('hidden');
  document.getElementById('s-menu').classList.remove('hidden');
  document.getElementById('end-card').classList.remove('visible');
  document.getElementById('death-card').classList.remove('visible');
  document.getElementById('chapter-card').classList.remove('visible');
}
function startStory(id){
  currentStory=STORIES[id];
  document.getElementById('s-menu').classList.add('hidden');
  document.getElementById('s-game').classList.remove('hidden');
  document.getElementById('hud-title').textContent=currentStory.title;
  document.documentElement.style.setProperty('--story-color',currentStory.color);
  loadScene('start')
}
function loadScene(id){
  if(!currentStory)return;
  lastScene=id;
  const s=currentStory.scenes[id];if(!s)return;
  document.getElementById('game-bg').style.backgroundImage=`linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),url('${BG[s.bg]||BG.night}')`;
  document.getElementById('game-bg').style.backgroundSize='cover';
  document.getElementById('game-bg').style.backgroundPosition='center';
  if(s.shake){const bg=document.getElementById('game-bg');setTimeout(()=>bg.classList.add('shake'),100);setTimeout(()=>bg.classList.remove('shake'),700)}
  if(s.flash)flashRed();
  if(s.chapter)showChapter(s.chapter,s.chTitle||'');
  document.getElementById('speaker-name').textContent=s.speaker||'';
  document.getElementById('choices').innerHTML='';
  document.getElementById('choices').classList.remove('visible');
  document.getElementById('tap-hint').style.display='block';
  document.getElementById('game-textbox').classList.remove('fade-slide');
  void document.getElementById('game-textbox').offsetWidth;
  document.getElementById('game-textbox').classList.add('fade-slide');
  if(s.chapter)setTimeout(()=>typeText(s.text,s.choices),2800);
  else typeText(s.text,s.choices)
}
function showChapter(n,t){
  const c=document.getElementById('chapter-card');
  document.getElementById('ch-num').textContent='Chapitre '+n;
  document.getElementById('ch-title').textContent=t;
  c.classList.add('visible');setTimeout(()=>c.classList.remove('visible'),2500)
}
function flashRed(){const f=document.getElementById('flash-overlay');f.classList.add('flash-red');setTimeout(()=>f.classList.remove('flash-red'),400)}
function typeText(text,choices){
  const el=document.getElementById('story-text');
  el.textContent='';typing=true;pausedText=text;pausedIdx=0;pausedChoices=choices;
  function next(){
    if(paused)return;
    if(!typing){el.textContent=text;document.getElementById('tap-hint').style.display='none';showChoices(choices);return}
    if(pausedIdx<text.length){
      el.textContent+=text[pausedIdx++];
      const ch=text[pausedIdx-1];
      const d=ch==='.'||ch==='!'||ch==='?'?130:ch===','||ch===';'?70:ch==='\n'?50:22;
      typeTimer=setTimeout(next,d)
    }else{typing=false;document.getElementById('tap-hint').style.display='none';showChoices(choices)}
  }
  next()
}
function resumeType(){
  const el=document.getElementById('story-text');
  function next(){
    if(paused)return;
    if(!typing){showChoices(pausedChoices);return}
    if(pausedIdx<pausedText.length){
      el.textContent+=pausedText[pausedIdx++];
      const ch=pausedText[pausedIdx-1];
      const d=ch==='.'||ch==='!'||ch==='?'?130:ch===','||ch===';'?70:ch==='\n'?50:22;
      typeTimer=setTimeout(next,d)
    }else{typing=false;document.getElementById('tap-hint').style.display='none';showChoices(pausedChoices)}
  }
  next()
}
function skipTyping(){
  if(typing&&!paused){
    typing=false;clearTimeout(typeTimer);
    document.getElementById('story-text').textContent=pausedText;
    document.getElementById('tap-hint').style.display='none';
    showChoices(pausedChoices)
  }
}
function showChoices(choices){
  if(!choices||!choices.length)return;
  const el=document.getElementById('choices');el.innerHTML='';
  choices.forEach(c=>{
    const btn=document.createElement('button');
    btn.className='choice-btn';btn.textContent=c.label;
    btn.onclick=()=>choiceClick(c);el.appendChild(btn)
  });
  el.classList.add('visible')
}
function choiceClick(c){
  if(c.die)showDeath(c.deathMsg);
  else if(c.next==='__end__')showEnd(c.endTitle,c.endText);
  else loadScene(c.next)
}
function showDeath(msg){
  document.getElementById('death-msg').textContent=msg||'Tu n\'aurais pas dû.';
  document.getElementById('death-card').classList.add('visible')
}
function retryScene(){document.getElementById('death-card').classList.remove('visible');loadScene(lastScene)}
function showEnd(title,text){
  document.getElementById('end-title').textContent=title||'Fin';
  document.getElementById('end-text').textContent=text||'';
  document.getElementById('end-card').classList.add('visible')
}
document.getElementById('s-game').addEventListener('click',e=>{
  if(!['choice-btn','hud-btn','btn-main','btn-ghost'].some(c=>e.target.classList.contains(c)))skipTyping()
});
</script>
</body>
</html>