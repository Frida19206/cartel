<?php
session_start();

// ── Session & navigation ───────────────────────────────────────────────
if (isset($_POST['next_scene'])) {
    $next = preg_replace('/[^a-z0-9_]/', '', $_POST['next_scene']);
    if ($next === 'reset') {
        session_destroy();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    if (!isset($_SESSION['history'])) $_SESSION['history'] = [];
    $_SESSION['history'][] = $_SESSION['scene'] ?? 'prologue';
    $_SESSION['scene'] = $next;
}

if (!isset($_SESSION['scene'])) {
    $_SESSION['scene']  = 'prologue';
    $_SESSION['history'] = [];
}

// ── Story ──────────────────────────────────────────────────────────────
$scenes = [

/* ═══════════════════════════  PROLOGUE  ══════════════════════════════ */
'prologue' => [
    'chapter'  => null,
    'title'    => 'UNTOUCHABLE',
    'subtitle' => 'Une histoire originale',
    'text'     =>
"Il y a des hommes que le monde entier craint.

Des hommes dont le nom ne se prononce qu'en chuchotant, de peur que les murs aient des oreilles. Des hommes qui n'ont pas besoin de hausser la voix pour qu'un silence de mort s'installe. Des hommes qui vivent dans l'ombre — non pas parce qu'ils la fuient, mais parce que l'ombre leur appartient.

Rafael Vega est l'un d'eux.

Je ne savais pas, ce soir-là, que ma vie allait basculer. Que cette enquête allait m'entraîner dans les profondeurs d'un monde que je n'aurais jamais dû approcher. Un monde de sang, de silences calculés, et de beautés empoisonnées.

Je m'appelle Elena Moreau. J'avais vingt-trois ans, une ambition dévorante, et une naïveté que je confondais avec du courage.

C'est du moins ce qu'il m'a dit, la première nuit, sa voix basse comme une lame contre ma gorge.

— Tu n'aurais jamais dû creuser si profond, petite journaliste.

Il avait raison.

Mais c'était déjà trop tard.",
    'choices'  => [
        ['text' => '— Commencer l\'histoire —', 'next' => 'ch1_nuit'],
    ],
],

/* ══════════════════════════  CHAPITRE I  ═════════════════════════════ */
'ch1_nuit' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'La Nuit Où Tout A Basculé',
    'text'    =>
"Barcelone, 23h52.

La ville ne dort jamais vraiment. Elle somnole entre deux respirations, les yeux mi-clos sur ses propres secrets. C'est ce que j'aime en elle — cette façon qu'elle a de garder ses mystères même sous les lumières.

Mon téléphone vibre.

L'écran s'illumine sur ma table de nuit : SÉBASTIEN — RÉDAC CHEF. Je décroche à la deuxième sonnerie.

— Moreau. On a un nouveau cadavre.

Pas de bonjour. Sébastien Maret n'a jamais perdu de temps en préambules.

— Troisième cette semaine, reprend-il. Même mise en scène. Même signature. La Policía Nacional est dépassée — mes contacts au commissariat parlent de crime organisé. Du haut niveau.

Un silence. Je l'entends allumer une cigarette.

— J'ai besoin de toi sur cette affaire. Ce soir.

Je suis déjà debout, les pieds nus sur le carrelage froid. Par la fenêtre, les lumières de la ciudad dessinent des étoiles sur la baie.

Trois cadavres. Même signature. Crime organisé.

Quelque chose dans ma poitrine s'éveille — pas de la peur. De la faim.",
    'choices' => [
        ['text' => '"Envoie-moi l\'adresse. J\'arrive."', 'next' => 'ch1_scene'],
        ['text' => '"Qui sont les victimes ?"',           'next' => 'ch1_details'],
    ],
],

'ch1_details' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'La Nuit Où Tout A Basculé',
    'text'    =>
"— Les victimes ? je répète, ma voix plus froide que je ne le voulais.

— Trois hommes. Cadres supérieurs, apparemment sans lien entre eux. Mais mes sources disent que tous gravitaient autour des mêmes cercles... discrets.

— Des cercles discrets. C'est une façon polie de dire quoi, exactement ?

Sébastien souffle la fumée de sa cigarette contre le combiné.

— Des cercles où l'argent n'a pas d'odeur, Moreau. Et où les questions n'ont pas de réponse. C'est pour ça que j'ai besoin de toi — tu as l'instinct qu'il faut pour ce genre d'histoire.

Je regarde par la fenêtre. La ville scintille comme une promesse à moitié tenue.

Trois hommes. Des cercles discrets. Une signature.

Mon esprit commence à tisser des fils — noirs sur un fond encore plus sombre.

— Envoie-moi l'adresse.",
    'choices' => [
        ['text' => 'Partir immédiatement', 'next' => 'ch1_scene'],
    ],
],

'ch1_scene' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'La Scène de Crime',
    'text'    =>
"Le quartier de Gràcia est cerclé de rubalise bleue et blanche quand j'arrive, mon appareil photo en bandoulière, mon carnet sous le bras.

Les flics ne laissent passer personne. Mais j'ai appris, au fil des années, qu'il suffit de repérer l'agent le plus distrait pour se glisser là où on n'est pas invitée.

J'y suis en trois minutes.

La victime — un homme d'une cinquantaine d'années, costume impeccable — est allongée dans une ruelle étroite avec une précision presque chirurgicale. Trop précise. Trop... organisée. Ce n'est pas le geste d'une rage. C'est le geste d'un message.

Les inspecteurs se concentrent sur le corps. Mais quelque chose attire mon regard plus loin — une marque sur le mur. Pas un tag. Pas un graffiti.

Une lettre. Un V, tracé à même la pierre avec ce qui ressemble à de la cendre.

La même marque que sur les deux autres scènes, selon les notes de Sébastien.

À ce moment précis, je sens une présence dans mon dos. Quelqu'un me regarde.",
    'choices' => [
        ['text' => 'Photographier la marque discrètement', 'next' => 'ch1_photo'],
        ['text' => 'Me retourner pour faire face',          'next' => 'ch1_retourne'],
    ],
],

'ch1_photo' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'Le V',
    'text'    =>
"Je garde les yeux sur le mur.

Je lève mon appareil avec des gestes lents, comme si je photographiais la scène dans son ensemble.

Click.

La marque est dans le cadre. Un V aux bords parfaitement nets, tracé avec une précision qui ne ressemble à aucune improvisation. Pas le geste d'un fou. Une signature froide. Délibérée.

Je prends trois clichés supplémentaires avant de sentir une main se poser sur mon épaule — lourde, ferme, sans brutalité mais sans question non plus.

— Votre accréditation, Señorita.

Ce n'est pas un policier. La voix est trop calme. Le costume trop parfait. Et les yeux de l'homme qui se tient devant moi, quand je me retourne, sont d'un noir absolu — sans la moindre trace de chaleur.

— Je suis journaliste, je dis en cherchant ma carte de presse.

— Je sais ce que vous êtes.

Dans mon dos, deux autres hommes en noir ont encerclé l'espace. Imperceptiblement. Comme des ombres qui auraient décidé de prendre forme.

— Il y a quelqu'un qui souhaite vous parler.",
    'choices' => [
        ['text' => '"Qui ?"',         'next' => 'ch1_capture'],
        ['text' => 'Tenter de fuir', 'next' => 'ch1_fuite'],
    ],
],

'ch1_retourne' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'Les Yeux Dans L\'Ombre',
    'text'    =>
"Je me retourne d'un coup, le cœur en accélération.

Un homme. Grand. Un costume sombre qui coûte plus que mon loyer mensuel. Il me regarde avec une expression neutre — pas hostile, pas bienveillante. Évaluatrice. Comme si j'étais un problème dont il calculait déjà la solution.

— Vous ne devriez pas être ici. Sa voix est basse, légèrement teintée d'un accent espagnol.

— Je suis journaliste.

— Je sais ce que vous êtes.

Quelque chose dans sa façon de le dire me fait comprendre qu'il ne parle pas seulement de ma profession. Il sait qui je suis. Et ça, c'est différent.

Dans mon dos, j'entends des pas. Deux autres hommes. Sans bruit, sans précipitation.

— Il y a quelqu'un qui souhaite faire votre connaissance, reprend-il en glissant ses mains dans ses poches, comme si la situation était parfaitement ordinaire.

Je cherche mes options.

Il n'y en a pas beaucoup.",
    'choices' => [
        ['text' => '"Qui est ce quelqu\'un ?"', 'next' => 'ch1_capture'],
        ['text' => 'Tenter de fuir',            'next' => 'ch1_fuite'],
    ],
],

'ch1_fuite' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'La Course',
    'text'    =>
"Je pars en courant.

Mes jambes prennent la décision avant que mon cerveau ait fini de peser le pour et le contre — c'est l'instinct, pur et brut, qui me propulse dans la ruelle.

Je suis rapide. Mais eux sont plus rapides.

Et surtout — ils connaissent le quartier.

Je prends à gauche, puis à droite, mes semelles claquant sur les pavés mouillés. J'entends des pas derrière moi — pas de précipitation, pas de panique. Une poursuite méthodique. Comme si fuir ne changeait rien à l'issue.

Quand je débouche sur la Plaza del Sol, une voiture noire m'attend, garée exactement dans ma trajectoire.

Je m'arrête.

L'homme au costume est là. Légèrement essoufflé — mais il ne le montre pas. Derrière lui, la portière arrière de la voiture est ouverte.

— Vous avez essayé, dit-il. C'est bien. Il apprécie ça.

— Qui apprécie quoi ?

Il s'efface légèrement pour m'indiquer la voiture.

— Monte.",
    'choices' => [
        ['text' => 'Monter dans la voiture', 'next' => 'ch1_voiture'],
    ],
],

'ch1_capture' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'La Voiture Noire',
    'text'    =>
"Il ne répond pas à ma question.

À la place, il s'efface légèrement et tend la main vers la ruelle où une voiture noire aux vitres teintées vient de se garer sans un bruit.

— Votre appareil photo et votre téléphone, s'il vous plaît.

C'est dit avec une politesse absolue. Mais ce n'est pas une demande.

Je regarde les deux hommes derrière moi. Je regarde la voiture. Je regarde le V sur le mur.

Mes options sont limitées. Clairement.

Je lui tends mon matériel.

— Bien, dit-il simplement.

La portière s'ouvre. L'intérieur de la voiture sent le cuir neuf et quelque chose d'autre — quelque chose d'indéfinissable, légèrement boisé, légèrement froid.

Un bandeau noir repose sur le siège.

— Une précaution, précise-t-il en voyant mon expression. Rien de plus.",
    'choices' => [
        ['text' => 'Monter dans la voiture', 'next' => 'ch1_voiture'],
    ],
],

'ch1_voiture' => [
    'chapter' => 'CHAPITRE I',
    'title'   => 'L\'Obscurité',
    'text'    =>
"Le trajet dure vingt-deux minutes.

Je les compte, par réflexe journalistique — une tentative désespérée de garder le contrôle de quelque chose.

Sous le bandeau, je perds le sens des directions après la troisième courbe. Mais je sens qu'on quitte la ville. L'asphalte devient plus doux. Le bruit de Barcelone s'éloigne comme une respiration qui s'endort.

Personne ne parle dans la voiture.

Je réfléchis à tout ce que je sais. Trois cadavres. Un V sur un mur. Des hommes en costumes parfaits qui surgissent de l'ombre comme des fantômes avec un agenda.

La voiture s'arrête.

On m'aide à descendre — fermement, sans violence. On retire le bandeau.

Je cligne des yeux.

Devant moi se dresse une demeure qui n'a rien d'ordinaire. Haute, sombre, noyée dans les pins et la nuit, elle ressemble à quelque chose qu'on ne trouve pas sur les cartes. Quelque chose qu'on n'est pas censé trouver du tout.

L'homme en costume me fait signe d'avancer.

Les portes s'ouvrent.",
    'choices' => [
        ['text' => 'Entrer', 'next' => 'ch2_manoir'],
    ],
],

/* ══════════════════════════  CHAPITRE II  ════════════════════════════ */
'ch2_manoir' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'El Rey',
    'text'    =>
"L'intérieur est à la hauteur de l'extérieur — et de ce que mes pires suppositions me soufflaient depuis vingt-deux minutes.

Haut de plafond. Marbre noir. Des œuvres d'art dont je reconnais la valeur sans pouvoir les nommer. Une bibliothèque qui court sur toute la hauteur d'un mur et disparaît dans l'étage.

Et dans le grand salon, dos à moi, face à une baie vitrée qui donne sur les pins et la nuit, un homme.

Il ne se retourne pas quand j'entre.

Il n'a pas besoin de le faire — je sens son attention dans mon dos comme une pression physique. Comme si l'air de la pièce était différent autour de lui. Plus dense. Plus chargé.

Il tient un verre de whisky dans la main droite. Sa silhouette est celle de quelqu'un qui n'a jamais douté d'une seule décision de sa vie.

— Elena Moreau, dit-il enfin. Sa voix est grave, mesurée. Chaque mot pesé avant d'être lâché. — Vingt-trois ans. Journaliste au Gazette Internationale depuis dix-huit mois. Spécialité : enquêtes criminelles. Citée une fois dans le New York Times pour votre travail sur la corruption madrilène.

Il se retourne.

Et le monde s'arrête.",
    'choices' => [
        ['text' => 'Voir son visage', 'next' => 'ch2_rafael'],
    ],
],

'ch2_rafael' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'Le Visage Du Diable',
    'text'    =>
"Rafael Vega n'est pas ce à quoi je m'attendais.

J'avais imaginé quelque chose de brutal — l'archétype du criminel, tout en violence mal camouflée et en cicatrices exhibées. J'avais eu tort.

Il est... autre chose.

Grand. Des épaules sous un costume gris nuit taillé pour lui et lui seul. Des yeux sombres qui n'ont pas besoin de cligner — qui observent, analysent, dissèquent avec une précision chirurgicale. Des traits qui pourraient appartenir à un dieu si les dieux avaient la froideur des glaciers et la beauté des tempêtes.

Il me regarde de la façon qu'un collectionneur regarde une pièce rare. Pas avec du désir. Avec de l'intérêt.

Ce qui est, d'une certaine façon, infiniment plus dangereux.

— Vous avez photographié la marque, dit-il. Ce n'est pas une question.

Je garde le menton levé. Mon cœur bat à une vitesse que je refuse de lui montrer.

— J'ai fait mon travail.

Un silence. Puis — et c'est la première fois — quelque chose bouge dans ses yeux. Pas tout à fait un sourire. Mais quelque chose d'approchant.

— Oui, dit-il lentement. C'est exactement ce que vous avez fait.",
    'choices' => [
        ['text' => '"Qui êtes-vous ?"',             'next' => 'ch2_qui'],
        ['text' => '"Pourquoi m\'avoir amenée ici ?"', 'next' => 'ch2_pourquoi'],
    ],
],

'ch2_qui' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'Le Nom',
    'text'    =>
"— Qui êtes-vous ?

C'est la seule question qui compte. Il le sait. Je le sais.

Il porte le verre à ses lèvres, boit une gorgée, me regarde par-dessus le cristal avec quelque chose qui ressemble à de l'amusement — mais froid. Clinique.

— Vous le savez déjà, dit-il enfin. Sinon vous n'auriez pas cherché aussi loin.

— Rafael Vega.

Le nom tombe entre nous comme une pierre dans l'eau profonde. Je le vois dans ses yeux — l'infinitésimale réaction. La satisfaction que son nom soit prononcé sans trembler.

— Bien, dit-il. On peut gagner du temps.

Il pose son verre sur une table en verre et marbre et me fait face complètement. La distance entre nous est d'environ quatre mètres.

Ce n'est pas assez.

— Vous avez vu quelque chose ce soir que personne d'autre ne devait voir. La marque sur le mur. Ce que vous en ferez déterminera... la nature de nos prochaines interactions.

Nos prochaines interactions. Comme si ma vie venait d'être réduite à une variable dans son calcul.",
    'choices' => [
        ['text' => 'Écouter ce qu\'il a à proposer', 'next' => 'ch2_offre'],
        ['text' => '"Je ne travaille pas pour les criminels"', 'next' => 'ch2_refuse_pre'],
    ],
],

'ch2_pourquoi' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'La Raison',
    'text'    =>
"— Pourquoi m'avoir amenée ici ?

Il n'hésite pas. Les gens comme lui n'hésitent jamais.

— Parce que vous avez vu quelque chose. Et parce que les gens qui voient des choses qu'ils ne devraient pas voir ont, dans mon monde, deux options.

Un silence. Il me laisse le temps de comprendre — et de douter. Délibérément.

— Je suis encore en vie, je remarque.

— Vous l'êtes.

— Ce qui signifie que vous avez choisi la deuxième option.

Quelque chose traverse ses yeux — rapide, presque invisible. Pas tout à fait de la surprise. L'ombre d'une considération nouvelle.

— Vous êtes directe.

— Je suis journaliste.

— Vous l'étiez, corrige-t-il doucement. Ce que vous êtes maintenant est encore à définir.

Il s'approche — pas vite, pas menaçant. Juste... inévitable, comme une marée. Il s'arrête à deux mètres de moi.

De près, ses yeux ne sont pas tout à fait noirs. Ils sont d'un brun si sombre qu'ils absorbent la lumière au lieu de la refléter.",
    'choices' => [
        ['text' => 'Écouter ce qu\'il a à proposer',         'next' => 'ch2_offre'],
        ['text' => '"Je ne suis la propriété de personne"', 'next' => 'ch2_refuse_pre'],
    ],
],

'ch2_refuse_pre' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'La Ligne',
    'text'    =>
"Les mots sortent avant que j'aie pu les peser.

Rafael Vega ne réagit pas comme je l'attendais. Il n'élève pas la voix. Il ne menace pas. Il penche légèrement la tête de côté — une inclinaison de quelques degrés à peine — et me regarde comme s'il recalibrait quelque chose dans ses calculs.

— Non, dit-il enfin. Vous n'êtes la propriété de personne. C'est... rafraîchissant.

Il reprend son verre.

— Mais laissez-moi vous proposer un autre cadre. Pas un emploi. Pas une sujétion. Un accord entre deux personnes qui ont des intérêts convergents.

Ses yeux ne me lâchent pas.

— J'ai besoin d'une journaliste. Vous avez besoin d'une histoire.

La pièce devient très silencieuse.

Dehors, le vent dans les pins. À l'intérieur, rien que sa respiration — parfaitement régulière — et la mienne, que je maîtrise par pur entêtement.

— Écoutez ce que j'ai à vous dire, reprend-il. Ensuite, vous déciderez.

Et dans sa voix — pour la première fois — quelque chose qui ressemble à une demande.",
    'choices' => [
        ['text' => 'Écouter', 'next' => 'ch2_offre'],
    ],
],

'ch2_offre' => [
    'chapter' => 'CHAPITRE II',
    'title'   => 'L\'Accord',
    'text'    =>
"Il parle pendant vingt minutes.

Et en vingt minutes, mon monde bascule.

Les trois cadavres ne sont pas les premiers. Ils sont les premiers à être trouvés. Depuis dix-huit mois, des hommes disparaissent — des hommes avec des positions, des connexions, des secrets qu'ils auraient mieux fait de garder. Quelqu'un, dans les cercles où Vega opère, est en train de purger.

Et Vega lui-même est visé.

— Pourquoi me raconter ça ?

— Parce que j'ai besoin d'un angle que je n'ai pas. Quelqu'un qui peut poser des questions sans que les portes se ferment. Quelqu'un de l'extérieur.

— Un journaliste. Vous avez besoin d'un journaliste pour enquêter sur vos propres ennemis.

— Pour enquêter sur quelqu'un qui tue des gens et qui compte continuer.

Je le regarde. Il me regarde.

Le marché est clair : j'enquête pour lui. En échange — l'histoire du siècle. L'accès exclusif. La vérité sur le monde qu'il représente.

Et ma liberté, selon ce qu'il reste à définir.

— Si je refuse ?

Rafael Vega pose son verre vide. Quand il relève les yeux, il n'y a plus l'ombre d'une nuance dans son regard.

— Vous oubliez ce que vous avez vu. Vous rentrez chez vous. Et vous ne fouillez plus jamais dans cette direction.",
    'choices' => [
        ['text' => '"J\'accepte."', 'next' => 'ch3_accord'],
        ['text' => '"Je refuse."', 'next' => 'ch3_refus'],
    ],
],

/* ══════════════════════════  CHAPITRE III  ═══════════════════════════ */
'ch3_accord' => [
    'chapter' => 'CHAPITRE III',
    'title'   => 'Dans Le Ventre Du Monstre',
    'text'    =>
"— J'accepte.

Deux mots. Et quelque chose change dans l'air de la pièce — imperceptiblement, mais je le sens. Comme si le pacte existait déjà avant même que je l'aie prononcé.

Rafael me regarde une longue seconde. Puis il hoche la tête — une seule fois, brève, définitive.

— Marco va vous installer. Vous resterez ici le temps nécessaire.

— Je ne vis pas ici.

— Pour l'instant, si.

Je veux protester. Mais je me souviens de l'expression dans ses yeux quand il a dit « vous oubliez ». Ce n'était pas une menace. C'était une constatation. La différence est subtile et terrifiante.

Marco m'emmène au premier étage.

La chambre est plus grande que mon appartement barcelonais. Un lit immense. Une salle de bain en marbre. Une fenêtre qui donne sur les jardins et, au-delà, la ligne sombre des pins sous la lune.

Je m'assieds sur le bord du lit et reste immobile un long moment.

J'ai accepté de travailler pour Rafael Vega.

Je ne sais pas encore si c'était la décision la plus courageuse ou la plus stupide de ma vie.

Peut-être les deux.",
    'choices' => [
        ['text' => 'La première nuit — Continuer', 'next' => 'ch3_nuit'],
    ],
],

'ch3_refus' => [
    'chapter' => 'CHAPITRE III',
    'title'   => 'Le Prix De La Liberté',
    'text'    =>
"— Je refuse.

Rafael Vega ne dit rien pendant trois secondes entières.

Trois secondes où je ne sais pas ce qui se passe derrière ces yeux sombres qui ne clignotent pas.

Puis il sourit.

C'est le sourire le plus inquiétant que j'aie jamais vu. Pas parce qu'il est cruel — parce qu'il est sincère.

— Très bien.

Marco me raccompagne à la voiture. Le bandeau. Le trajet de vingt-deux minutes en sens inverse. Je retrouve Barcelone, la Plaza del Sol, mon appareil photo et mon téléphone glissés dans mes mains avant que la voiture reparte sans un bruit.

Je rentre chez moi.

J'essaie de dormir.

À 4h du matin, un message anonyme arrive sur mon téléphone. Pas de numéro. Pas d'expéditeur.

Juste une photo.

Ma fenêtre de chambre. Prise de l'extérieur. Cette nuit.

Et en dessous, quatre mots :

« Vous avez bien réfléchi ? »

Je regarde le plafond dans le noir.

Quelque part, Rafael Vega attend.",
    'choices' => [
        ['text' => 'Rappeler. Accepter.',       'next' => 'ch3_accord'],
        ['text' => 'Ignorer. Et creuser seule.', 'next' => 'fin_solo'],
    ],
],

'ch3_nuit' => [
    'chapter' => 'CHAPITRE III',
    'title'   => 'Minuit Dans La Maison Du Roi',
    'text'    =>
"Je ne dors pas.

À 2h du matin, je descends. Les couloirs sont silencieux mais pas vides — des silhouettes discrètes aux points stratégiques. Une maison qui ne dort jamais vraiment.

Je trouve la bibliothèque par hasard, en cherchant la cuisine.

Elle est grande, éclairée par quelques lampes basses, et sent le papier vieux et le bois ciré. Des milliers de livres en espagnol, en anglais, en français, en langues que je ne reconnais pas.

Je tends la main vers un titre au hasard quand j'entends une voix dans l'ombre.

— Vous ne dormez pas.

Rafael Vega est assis dans un fauteuil que je n'avais pas vu en entrant. Il porte les mêmes vêtements, sans la veste. Les manches de sa chemise sont retroussées jusqu'aux coudes.

C'est, étrangement, la chose la plus humaine que j'aie vue de lui.

— Vous non plus, je réponds.

Un silence.

— Je ne dors jamais beaucoup.

Il regarde le feu — une cheminée que je n'avais pas remarquée non plus — avec une expression que je n'arrive pas à lire. Quelque chose de vieux. De fatigué, presque.

Pour la première fois depuis cette nuit, il n'est pas El Rey.

Il est juste un homme, dans une bibliothèque, à 2h du matin.",
    'choices' => [
        ['text' => 'S\'asseoir. Lui parler.', 'next' => 'ch3_conversation'],
        ['text' => 'Remonter dans ma chambre', 'next' => 'ch3_fuite_douce'],
    ],
],

'ch3_conversation' => [
    'chapter' => 'CHAPITRE III',
    'title'   => 'Ce Que Le Feu Révèle',
    'text'    =>
"Je m'assieds dans le fauteuil en face du sien.

Il ne dit rien. Je ne dis rien. Le feu crépite entre nous.

— Qui cherche à vous tuer ? je demande enfin.

Sa tête tourne légèrement vers moi. Un regard oblique.

— Vous travaillez déjà.

— J'essaie de comprendre dans quoi je me suis embarquée.

Un silence. Puis, à ma surprise, il répond.

— Quelqu'un qui pensait que ma mort laisserait un vide qu'il pourrait combler. Quelqu'un qui ne comprend pas que les vides, dans mon monde, ne durent pas.

— Vous avez peur ?

Il me regarde vraiment, cette fois. Pas le regard du collectionneur. Autre chose.

— Les hommes comme moi n'ont pas le droit d'avoir peur.

C'est la réponse la plus honnête qu'il m'aura faite. Peut-être parce que la nuit est assez profonde. Peut-être parce que le feu rend les silhouettes plus douces.

Je le regarde — ce profil taillé dans quelque chose de dur et de beau, ces yeux qui portent trop de choses pour quelqu'un qui a à peine trente ans.

Et je comprends quelque chose que je n'aurais pas voulu comprendre.

Je suis en danger.

Pas à cause de lui. À cause de moi.",
    'choices' => [
        ['text' => 'Fin du Chapitre III — À suivre...', 'next' => 'fin_ch3'],
    ],
],

'ch3_fuite_douce' => [
    'chapter' => 'CHAPITRE III',
    'title'   => 'La Prudence',
    'text'    =>
"Je remonte dans ma chambre.

La sagesse l'emporte — ou peut-être la prudence. Peut-être la conscience aiguë que Rafael Vega est exactement le genre d'homme dont il faut se méfier justement quand il semble le moins dangereux.

Je suis journaliste. Je suis ici pour une histoire. Rien de plus.

Je m'allonge sur le lit immense et regarde le plafond.

À 3h du matin, j'entends ses pas dans le couloir. Ils s'arrêtent devant ma porte — une seconde, une seule — puis continuent.

Je ferme les yeux.

Je mens très mal à moi-même.",
    'choices' => [
        ['text' => 'Fin du Chapitre III — À suivre...', 'next' => 'fin_ch3'],
    ],
],

/* ═══════════════════════════  FINS  ══════════════════════════════════ */
'fin_ch3' => [
    'chapter' => 'FIN DU CHAPITRE III',
    'title'   => '— À Suivre —',
    'text'    =>
"La nuit dans la maison de Rafael Vega dure plus que toutes les autres.

Demain, l'enquête commence vraiment. Les questions se multiplieront. Les frontières deviendront floues — entre le chasseur et la proie, entre le mensonge et la vérité, entre ce qu'Elena cherche et ce qu'elle commence à ressentir.

Demain, elle plongera plus profond.

Elle ne sait pas encore qu'il n'y a pas de remontée.

✦

Merci d'avoir joué les trois premiers chapitres d'UNTOUCHABLE.
La suite arrive bientôt.",
    'fin'     => true,
    'choices' => [
        ['text' => '↺ Recommencer depuis le début', 'next' => 'reset'],
    ],
],

'fin_solo' => [
    'chapter' => 'FIN ALTERNATIVE',
    'title'   => 'La Chasseuse',
    'text'    =>
"Je pose mon téléphone.

Je vais dans ma cuisine, je me fais un café, et j'ouvre mon ordinateur.

Rafael Vega veut que je l'aide à trouver quelqu'un. Ou il veut que j'oublie. Dans un cas comme dans l'autre, il a sous-estimé une chose : je suis journaliste. Et quand on me dit de ne pas fouiller, c'est toujours là que l'histoire se trouve.

Je commence à creuser. Seule.

Le V sur le mur. Les trois cadavres. Les connexions.

À 6h du matin, j'ai un fil. Mince, fragile, mais réel.

À 6h12, mon téléphone sonne. Numéro masqué.

Je décroche.

— Bonjour, Elena. La voix de Rafael Vega. Toujours aussi calme. Toujours aussi froide. — Je vois que vous avez fait votre choix.

Un silence.

— Faites attention à vous.

Il raccroche.

Dehors, le soleil se lève sur Barcelone.

L'histoire vient de commencer. Et pour la première fois, je ne suis pas certaine d'en être la seule à tenir les ficelles.",
    'fin'     => true,
    'choices' => [
        ['text' => '↺ Rejouer et faire un autre choix', 'next' => 'reset'],
    ],
],

]; // end $scenes

// ── Resolve current scene ──────────────────────────────────────────────
$key   = $_SESSION['scene'];
$scene = $scenes[$key] ?? $scenes['prologue'];

// ── Progress ───────────────────────────────────────────────────────────
$all_keys   = array_keys($scenes);
$scene_idx  = array_search($key, $all_keys);
$total_keys = count($all_keys);

// Paragraph split helper
function paragraphs(string $text): string {
    $parts = explode("\n\n", $text);
    $html  = '';
    foreach ($parts as $p) {
        $p = nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8'));
        $html .= "<p>$p</p>\n";
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UNTOUCHABLE — Histoire Interactive</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* ── Reset & Base ───────────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --bg:          #07070E;
    --surface:     #0C0C1A;
    --border:      #1A1A30;
    --border-glow: #2A1A25;
    --text:        #E8E4D8;
    --text-dim:    #6A6560;
    --text-mid:    #A09890;
    --red:         #9B1C2E;
    --red-bright:  #C9243A;
    --red-glow:    rgba(155,28,46,0.15);
    --gold:        #A07840;
    --gold-dim:    rgba(160,120,64,0.3);
    --white:       #F0EDE6;
}

html { scroll-behavior: smooth; }

body {
    background-color: var(--bg);
    color: var(--text);
    font-family: 'Raleway', sans-serif;
    font-weight: 300;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

/* ── Atmospheric background ─────────────────────────────────────────── */
body::before {
    content: '';
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse 60% 50% at 20% 20%, rgba(120,20,40,0.08) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 80% 80%, rgba(20,10,50,0.12) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
    animation: breathe 12s ease-in-out infinite alternate;
}

@keyframes breathe {
    0%   { opacity: .6; }
    100% { opacity: 1;  }
}

/* Grain overlay */
body::after {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 0;
    opacity: 0.5;
}

/* ── Layout ─────────────────────────────────────────────────────────── */
.wrapper {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0 1.5rem 4rem;
}

.header-bar {
    width: 100%;
    max-width: 680px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.8rem 0 1.2rem;
    border-bottom: 1px solid var(--border);
}

.logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.9rem;
    font-weight: 400;
    letter-spacing: 0.28em;
    color: var(--text-dim);
    text-transform: uppercase;
}

.progress-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress-dots {
    display: flex;
    gap: 5px;
}

.dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--border);
    transition: background 0.4s;
}

.dot.done  { background: var(--red); }
.dot.active{ background: var(--red-bright); box-shadow: 0 0 6px var(--red); }

.reset-btn {
    background: none;
    border: none;
    color: var(--text-dim);
    font-family: 'Raleway', sans-serif;
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    cursor: pointer;
    text-transform: uppercase;
    transition: color 0.3s;
}
.reset-btn:hover { color: var(--red-bright); }

/* ── Scene card ─────────────────────────────────────────────────────── */
.scene {
    width: 100%;
    max-width: 680px;
    margin-top: 4rem;
    animation: fadeIn 0.8s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.chapter-label {
    font-family: 'Raleway', sans-serif;
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.35em;
    color: var(--gold);
    text-transform: uppercase;
    margin-bottom: 1.2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chapter-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--gold-dim), transparent);
}

.scene-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 300;
    font-style: italic;
    color: var(--white);
    line-height: 1.15;
    margin-bottom: 0.4rem;
}

.scene-subtitle {
    font-family: 'Raleway', sans-serif;
    font-size: 0.72rem;
    letter-spacing: 0.2em;
    color: var(--text-dim);
    text-transform: uppercase;
    margin-bottom: 2.8rem;
}

/* Signature element: title underline that grows */
.title-line {
    height: 1px;
    background: linear-gradient(90deg, var(--red), transparent);
    width: 0;
    margin-top: 0.6rem;
    margin-bottom: 2.8rem;
    animation: expandLine 1s ease 0.3s forwards;
}
@keyframes expandLine {
    to { width: 60%; }
}

.story-text {
    line-height: 1.95;
    font-size: clamp(0.95rem, 2vw, 1.05rem);
    color: var(--text);
}

.story-text p {
    margin-bottom: 1.4em;
}

/* Dialogue styling */
.story-text p:has(em),
.story-text em {
    font-style: italic;
    color: #D0CCBf;
}

/* ── Choices ─────────────────────────────────────────────────────────── */
.choices {
    margin-top: 3.5rem;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.choice-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 0;
    color: var(--text-dim);
    font-size: 0.65rem;
    letter-spacing: 0.2em;
}

.choice-divider::before,
.choice-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

.choice-form { display: contents; }

.choice-btn {
    display: block;
    width: 100%;
    padding: 1.3rem 1.6rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-left: 3px solid var(--red);
    color: var(--text);
    font-family: 'Raleway', sans-serif;
    font-size: 0.85rem;
    font-weight: 400;
    letter-spacing: 0.06em;
    text-align: left;
    cursor: pointer;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}

.choice-btn::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 0;
    background: var(--red-glow);
    transition: width 0.3s ease;
}

.choice-btn:hover {
    background: #0F0F20;
    border-color: var(--red-bright);
    border-left-color: var(--red-bright);
    color: var(--white);
    transform: translateX(4px);
    box-shadow: -3px 0 20px rgba(155,28,46,0.2);
}

.choice-btn:hover::before { width: 100%; }

.choice-btn:active { transform: translateX(2px); }

.choice-btn span {
    position: relative;
    z-index: 1;
}

/* Only choice (no separator needed) */
.choices.single .choice-btn {
    border-left: 1px solid var(--red);
    text-align: center;
    letter-spacing: 0.15em;
    font-size: 0.8rem;
    text-transform: uppercase;
    padding: 1.5rem;
    background: transparent;
    margin-top: 1rem;
}

.choices.single .choice-btn:hover {
    background: var(--red-glow);
    border-color: var(--red-bright);
    transform: none;
}

/* ── Fin badge ───────────────────────────────────────────────────────── */
.fin-badge {
    margin: 3rem 0 1rem;
    display: flex;
    align-items: center;
    gap: 1.2rem;
}
.fin-badge::before,
.fin-badge::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold-dim), transparent);
}
.fin-badge-inner {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.4rem;
    font-style: italic;
    color: var(--gold);
    letter-spacing: 0.05em;
}

/* ── Scrollbar ───────────────────────────────────────────────────────── */
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: var(--bg); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

/* ── Mobile ──────────────────────────────────────────────────────────── */
@media (max-width: 600px) {
    .scene { margin-top: 2.5rem; }
    .choice-btn { padding: 1.1rem 1.2rem; }
    .header-bar { padding: 1.2rem 0 1rem; }
}
</style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <header class="header-bar">
        <span class="logo">Untouchable</span>
        <div class="progress-bar">
            <div class="progress-dots">
                <?php
                $visible = min($total_keys, 14);
                for ($i = 0; $i < $visible; $i++) {
                    if ($i < count($_SESSION['history'])) $cls = 'done';
                    elseif ($i === count($_SESSION['history'])) $cls = 'active';
                    else $cls = '';
                    echo "<span class='dot $cls'></span>";
                }
                ?>
            </div>
        </div>
        <form method="POST">
            <input type="hidden" name="next_scene" value="reset">
            <button type="submit" class="reset-btn">↺ Recommencer</button>
        </form>
    </header>

    <!-- Scene -->
    <main class="scene">

        <?php if (!empty($scene['chapter'])): ?>
        <div class="chapter-label"><?= htmlspecialchars($scene['chapter']) ?></div>
        <?php endif; ?>

        <h1 class="scene-title"><?= htmlspecialchars($scene['title']) ?></h1>

        <?php if (!empty($scene['subtitle'])): ?>
        <div class="scene-subtitle"><?= htmlspecialchars($scene['subtitle']) ?></div>
        <?php endif; ?>

        <div class="title-line"></div>

        <div class="story-text">
            <?= paragraphs($scene['text']) ?>
        </div>

        <?php if (!empty($scene['fin'])): ?>
        <div class="fin-badge"><span class="fin-badge-inner">✦</span></div>
        <?php endif; ?>

        <!-- Choices -->
        <?php if (!empty($scene['choices'])): ?>
        <nav class="choices <?= count($scene['choices']) === 1 ? 'single' : '' ?>">
            <?php foreach ($scene['choices'] as $i => $choice): ?>

                <?php if ($i > 0): ?>
                <div class="choice-divider">ou</div>
                <?php endif; ?>

                <form method="POST" class="choice-form">
                    <input type="hidden" name="next_scene"  value="<?= htmlspecialchars($choice['next']) ?>">
                    <input type="hidden" name="choice_text" value="<?= htmlspecialchars($choice['text']) ?>">
                    <button type="submit" class="choice-btn">
                        <span><?= htmlspecialchars($choice['text']) ?></span>
                    </button>
                </form>

            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

    </main>
</div>

<script>
// Smooth scroll to top on scene change
window.addEventListener('pageshow', () => window.scrollTo({top:0,behavior:'smooth'}));
</script>
</body>
</html>
