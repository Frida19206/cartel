<?php
require_once 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
$conn->set_charset('utf8mb4');
if ($conn->connect_error) die('Connexion échouée : ' . $conn->connect_error);

$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

$conn->query("
    CREATE TABLE IF NOT EXISTS stories (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        story_key   VARCHAR(50) UNIQUE NOT NULL,
        title       VARCHAR(100) NOT NULL,
        color       VARCHAR(20) NOT NULL,
        description TEXT,
        tag         VARCHAR(200)
    ) ENGINE=InnoDB
");

$conn->query("
    CREATE TABLE IF NOT EXISTS scenes (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        story_id      INT NOT NULL,
        scene_key     VARCHAR(100) NOT NULL,
        chapter       VARCHAR(10),
        chapter_title VARCHAR(200),
        bg            VARCHAR(50) DEFAULT 'night',
        speaker       VARCHAR(100),
        text_content  TEXT NOT NULL,
        shake         TINYINT(1) DEFAULT 0,
        flash         TINYINT(1) DEFAULT 0,
        FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE,
        UNIQUE KEY uq_scene (story_id, scene_key)
    ) ENGINE=InnoDB
");

$conn->query("
    CREATE TABLE IF NOT EXISTS choices (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        scene_id    INT NOT NULL,
        label       TEXT NOT NULL,
        next_scene  VARCHAR(100),
        is_death    TINYINT(1) DEFAULT 0,
        death_msg   TEXT,
        end_title   VARCHAR(200),
        end_text    TEXT,
        sort_order  INT DEFAULT 0,
        FOREIGN KEY (scene_id) REFERENCES scenes(id) ON DELETE CASCADE
    ) ENGINE=InnoDB
");

$conn->query("
    CREATE TABLE IF NOT EXISTS game_sessions (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        session_id    VARCHAR(100) UNIQUE NOT NULL,
        story_key     VARCHAR(50),
        current_scene VARCHAR(100),
        death_count   INT DEFAULT 0,
        created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

$conn->query("
    CREATE TABLE IF NOT EXISTS player_history (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        session_id   VARCHAR(100),
        scene_key    VARCHAR(100),
        choice_label TEXT,
        chosen_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

function insertStory($conn, $data) {
    $stmt = $conn->prepare("
        INSERT IGNORE INTO stories (story_key, title, color, description, tag)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('sssss', $data['key'], $data['title'], $data['color'], $data['description'], $data['tag']);
    $stmt->execute();
    $storyId = $conn->insert_id;
    if (!$storyId) {
        $r = $conn->query("SELECT id FROM stories WHERE story_key = '{$data['key']}'");
        $storyId = $r->fetch_assoc()['id'];
    }
    return $storyId;
}

function insertScene($conn, $storyId, $data) {
    $shake = $data['shake'] ? 1 : 0;
    $flash = $data['flash'] ? 1 : 0;
    $speaker = $data['speaker'] ?? null;
    $chapter = $data['chapter'] ?? null;
    $chTitle = $data['chapter_title'] ?? null;
    $stmt = $conn->prepare("
        INSERT IGNORE INTO scenes (story_id, scene_key, chapter, chapter_title, bg, speaker, text_content, shake, flash)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('issssssii', $storyId, $data['key'], $chapter, $chTitle, $data['bg'], $speaker, $data['text'], $shake, $flash);
    $stmt->execute();
    $sceneId = $conn->insert_id;
    if (!$sceneId) {
        $r = $conn->query("SELECT id FROM scenes WHERE story_id = $storyId AND scene_key = '{$data['key']}'");
        $sceneId = $r->fetch_assoc()['id'];
    }
    return $sceneId;
}

function insertChoice($conn, $sceneId, $choice, $order) {
    $isDeath   = $choice['is_death'] ? 1 : 0;
    $deathMsg  = $choice['death_msg'] ?? null;
    $nextScene = $choice['next'] ?? null;
    $endTitle  = $choice['end_title'] ?? null;
    $endText   = $choice['end_text'] ?? null;
    $stmt = $conn->prepare("
        INSERT INTO choices (scene_id, label, next_scene, is_death, death_msg, end_title, end_text, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param('issiissi', $sceneId, $choice['label'], $nextScene, $isDeath, $deathMsg, $endTitle, $endText, $order);
    $stmt->execute();
}

$stories = [
[
    'key'=>'captive','title'=>'CAPTIVE','color'=>'#C41E3A',
    'description'=>'Elle s\'est réveillée ligotée dans le noir. Si elle ne trouve pas une sortie avant l\'aube, il n\'y en aura plus.',
    'tag'=>'★ Survie · Kidnapping · Mort possible',
    'scenes'=>[
        ['key'=>'start','chapter'=>'I','chapter_title'=>'Les Ténèbres','bg'=>'cell','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Du sang dans la bouche.\n\nC'est la première sensation. Avant la douleur. Avant la panique. Le goût métallique, chaud, qui te dit que quelque chose de grave s'est déjà passé.\n\nTu ouvres les yeux.\n\nLe noir. Complet. Un sac sur la tête. Tes mains sont liées derrière ton dos. Tes chevilles aussi. Quelque chose de froid sous toi — du béton.\n\nTu es vivante.\n\nPour l'instant.",
         'choices'=>[
             ['label'=>'Rester immobile. Écouter avant d\'agir.','next'=>'cap_listen','is_death'=>false],
             ['label'=>'Te débattre. Appeler au secours.','next'=>null,'is_death'=>true,'death_msg'=>'Tu as crié. Quelqu\'un est entré. La crosse du pistolet contre ta tempe a été la dernière chose que tu as sentie.']
         ]],
        ['key'=>'cap_listen','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"Tu te forces à ne pas bouger.\n\nTu écoutes.\n\nDes voix. Loin, atténuées par une porte. Espagnol. Au moins deux hommes. Une caméra dans l'angle supérieur droit — le voyant rouge clignote.\n\nIls te regardent.\n\nAlors tu fais ce qu'il faut : tu te rassieds, tu recroise les mains dans le dos, et tu attends.",
         'choices'=>[
             ['label'=>'Chercher un angle mort à la caméra.','next'=>'cap_camera','is_death'=>false],
             ['label'=>'Regarder la caméra pour montrer que tu sais.','next'=>'cap_stare','is_death'=>false]
         ]],
        ['key'=>'cap_camera','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"Sous le lit de camp — dans l'angle mort — une brique légèrement déplacée.\n\nTu l'extrais.\n\nDerrière : une lame de couteau brisée. Rouillée, coupante sur un côté. Et gravé dans la brique, en lettres minuscules :\n\nLA FENÊTRE DU COULOIR S'OUVRE DE L'EXTÉRIEUR.\n\nQuelqu'un d'autre a été ici. Et a survécu assez longtemps pour laisser un message.\n\nLa porte s'ouvre.",
         'choices'=>[
             ['label'=>'Cacher la lame dans ta manche avant qu\'il entre.','next'=>'cap_hide_blade','is_death'=>false],
             ['label'=>'Remettre la brique en place — garder le secret.','next'=>'cap_matteo_armed','is_death'=>false]
         ]],
        ['key'=>'cap_stare','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Tu regardes la caméra. Droit dans l'objectif. Sans ciller.\n\nTrente secondes plus tard, la porte s'ouvre.\n\nL'homme qui entre a un visage conçu pour ne pas être décrit dans un témoignage. Il s'arrête sur le seuil.\n\n— Tu es plus calme que je pensais.\n\nIl entre. Il laisse la porte ouverte derrière lui — un piège, peut-être, pour voir si tu cours.\n\nTu ne cours pas.",
         'choices'=>[
             ['label'=>'"Qu\'est-ce que vous voulez ?"','next'=>'cap_matteo_talk','is_death'=>false],
             ['label'=>'Garder le silence total.','next'=>'cap_matteo_silence','is_death'=>false]
         ]],
        ['key'=>'cap_hide_blade','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"Tu glisses la lame dans ta manche une demi-seconde avant que la porte s'ouvre.\n\nL'homme qui entre est plus jeune que tu ne l'imaginais. Trop bien habillé pour cet endroit.\n\n— Sara Navarro. Tu n'aurais pas dû être dans cette voiture ce soir-là. C'est une erreur. Notre erreur.\n\nUn silence. Puis :\n\n— Mais maintenant tu l'as vu. Et on ne peut pas défaire ça.",
         'choices'=>[
             ['label'=>'"Qu\'est-ce que j\'ai vu ?"','next'=>'cap_wrong_place','is_death'=>false],
             ['label'=>'Attaquer maintenant — tu as la lame.','next'=>null,'is_death'=>true,'death_msg'=>'Tu n\'étais pas assez proche. Il a réagi en une fraction de seconde. La lame est tombée. Lui, non.']
         ]],
        ['key'=>'cap_matteo_armed','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"La brique est remise en place. La lame dans ta paume quand il entre.\n\nIl s'assoie. Il pose ton téléphone sur ses genoux.\n\n— Tu as appelé quelqu'un avant qu'on te prenne. Le numéro a été identifié et coupé.\n\nIl te regarde.\n\n— Qui étais-tu en train d'appeler ?",
         'choices'=>[
             ['label'=>'Mentir : "Ma sœur. Un appel de routine."','next'=>'cap_lie_sister','is_death'=>false],
             ['label'=>'"La police."','next'=>null,'is_death'=>true,'death_msg'=>'"La police." Il a hoché la tête, presque triste. Puis il a fait un signe. Tu n\'as pas eu le temps de comprendre.']
         ]],
        ['key'=>'cap_matteo_talk','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>'MATTEO','shake'=>false,'flash'=>false,
         'text'=>"— Ton père travaillait pour nous. Pendant six ans. La semaine dernière, il a décidé de s'arrêter. D'une façon qui nous pose un problème.\n\nIl s'accroupit à ta hauteur. De près, ses yeux sont sombres et vides.\n\n— Toi, tu es la façon dont on s'assure qu'il change d'avis.\n\nIl n'a pas besoin de finir la phrase.",
         'choices'=>[
             ['label'=>'"Mon père ne cédera pas pour moi."','next'=>'cap_bluff','is_death'=>false],
             ['label'=>'Demander à parler à ton père.','next'=>'cap_father_call','is_death'=>false]
         ]],
        ['key'=>'cap_matteo_silence','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>'MATTEO','shake'=>false,'flash'=>false,
         'text'=>"Il observe ton silence avec quelque chose qui ressemble à du respect.\n\n— Le silence, dit-il, c'est soit de la sagesse, soit de la stupidité. Les deux ont le même visage.\n\nIl prend ton menton dans sa main — pas violemment, mais sans te laisser le choix.\n\n— Tu vas parler. Tout le monde parle. La question c'est quand et dans quel état.\n\n— Je reviendrai dans une heure. Profite du silence pendant qu'il est encore confortable.",
         'choices'=>[
             ['label'=>'Utiliser cette heure pour explorer la pièce.','next'=>'cap_camera','is_death'=>false],
             ['label'=>'Préparer ce que tu vas lui dire.','next'=>'cap_bluff','is_death'=>false]
         ]],
        ['key'=>'cap_wrong_place','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"— Tu étais dans la mauvaise voiture au mauvais moment. C'est tout.\n\n— Qu'est-ce que j'ai vu dans cette voiture ?\n\n— Des visages. Des noms. Des choses qui ne devraient exister sur aucune photo.\n\nIl se retourne.\n\n— Mon patron veut t'éliminer. Je pense qu'il y a une autre option. Mais tu dois me faire confiance.",
         'choices'=>[
             ['label'=>'"Pourquoi vous aideriez-vous à me sauver ?"','next'=>'cap_why_help','is_death'=>false],
             ['label'=>'Accepter son aide.','next'=>'cap_escape_together','is_death'=>false],
             ['label'=>'Refuser. Tu te sors de là seule.','next'=>null,'is_death'=>true,'death_msg'=>'Tu t\'es levée trop vite. La porte était verrouillée. Ils t\'ont entendue. Il n\'y a pas eu de deuxième chance.']
         ]],
        ['key'=>'cap_lie_sister','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"— Ma sœur. Un appel de routine.\n\n— Tu n'as pas de sœur.\n\nIl pose le téléphone sur le sol et le pousse vers toi avec le pied.\n\n— Essaie encore.\n\nLa lame dans ta paume est froide. L'homme devant toi ne l'est pas.",
         'choices'=>[
             ['label'=>'"Un ami. Je lui avais dit de prévenir la police."','next'=>'cap_bluff','is_death'=>false],
             ['label'=>'Attaquer maintenant.','next'=>null,'is_death'=>true,'death_msg'=>'Il était plus rapide. Il avait prévu ça. Peut-être depuis le début.']
         ]],
        ['key'=>'cap_bluff','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"— Mon père ne cédera pas pour moi. Vous misez sur quelqu'un qui n'existe plus.\n\nC'est un bluff total. Mais le bluff n'est pas là pour être cru. Il est là pour acheter du temps.\n\n— Intéressant.\n\nIl se lève. Il marche vers la porte.\n\n— Dans ce cas, tu n'as aucune valeur pour nous.\n\nLa porte reste ouverte.\n\n— À moins que tu aies quelque chose d'autre à nous offrir.",
         'choices'=>[
             ['label'=>'"Je connais l\'identité de la taupe dans votre organisation."','next'=>'cap_gamble','is_death'=>false],
             ['label'=>'Garder le silence.','next'=>'cap_wait_night','is_death'=>false]
         ]],
        ['key'=>'cap_father_call','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"La ligne sonne quatre secondes.\n\nTon père décroche à la première.\n\n— Sara.\n\nUn mot. Mais dans sa voix il y a dix ans de secrets qu'il ne t'a jamais dits.\n\n— Papa... tu savais.\n\nIl ne nie pas.\n\n— Fais ce qu'ils disent, murmure-t-il. S'il te plaît.\n\nL'homme reprend le téléphone. Dans le silence qui suit, quelque chose se reconstruit en toi à la place de ce qui vient de mourir.",
         'choices'=>[
             ['label'=>'Utiliser cette rage pour réfléchir — pas pour agir.','next'=>'cap_gamble','is_death'=>false],
             ['label'=>'Craquer. Laisser voir la douleur.','next'=>null,'is_death'=>true,'death_msg'=>'Il a vu la fissure. Il a appuyé dessus jusqu\'à ce que tu lui donnes tout. Après ça, tu n\'avais plus aucune valeur.']
         ]],
        ['key'=>'cap_gamble','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"— Je connais l'identité de la taupe dans votre organisation.\n\nCe n'est pas vrai.\n\nMais dans les deux secondes qui suivent tu vois quelque chose traverser son visage.\n\nPas de la peur. Quelque chose de pire : de l'incertitude.\n\n— Continue, dit-il.\n\nTu tiens quelque chose maintenant — même si ce quelque chose n'existe pas.\n\n— Je continue quand je suis dehors.",
         'choices'=>[
             ['label'=>'Tenir cette position. Ne rien lâcher.','next'=>'cap_end_escape','is_death'=>false],
             ['label'=>'Donner un faux nom pour maintenir l\'illusion.','next'=>'cap_end_deal','is_death'=>false]
         ]],
        ['key'=>'cap_wait_night','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>'SARA','shake'=>false,'flash'=>false,
         'text'=>"La nuit dans cette pièce est interminable.\n\nLes rondes du garde deviennent moins régulières vers 3h.\n\nTu travailles. Sans bruit. Sans lumière.\n\nÀ 4h17, tu as deux choses : un câble électrique arraché du montant du lit et une idée qui va soit te sortir de là, soit te tuer.\n\nUne troisième option n'existe pas.",
         'choices'=>[
             ['label'=>'Passer à l\'action.','next'=>'cap_night_escape','is_death'=>false],
             ['label'=>'Attendre encore — trop risqué.','next'=>'cap_end_give','is_death'=>false]
         ]],
        ['key'=>'cap_why_help','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'MATTEO','shake'=>false,'flash'=>false,
         'text'=>"— Parce que j'en ai assez.\n\nC'est dit si simplement que ça sonne faux. Et en même temps — personne n'invente quelque chose d'aussi banal.\n\n— Mon patron élimine tout le monde qui sait trop. Dans six mois, ce sera mon tour.\n\nToi tu as vu des visages. Moi j'ai les noms, les comptes, les preuves. Ensemble on peut sortir et enterrer ces gens.\n\n— Ou tu m'utilises pour sortir et tu disparais, tu dis.\n\n— Oui. Ou ça.",
         'choices'=>[
             ['label'=>'Accepter le deal.','next'=>'cap_escape_together','is_death'=>false],
             ['label'=>'Négocier : lui d\'abord, toi ensuite.','next'=>'cap_escape_together','is_death'=>false]
         ]],
        ['key'=>'cap_escape_together','chapter'=>null,'chapter_title'=>null,'bg'=>'escape','speaker'=>null,'shake'=>true,'flash'=>false,
         'text'=>"Il sort par la porte de service à 4h du matin.\n\nToi trois minutes après.\n\nTu cours dans les pins. L'air est froid et tu respires trop vite mais tu t'en fous — tu respires, c'est déjà tout.\n\nDerrière toi, une alarme. Puis des voix. Puis rien.\n\nPuis les phares d'une voiture sur la nationale.\n\nTu t'arrêtes au bord de la route, les pieds dans la boue, le cœur à cent soixante.\n\nTu es vivante.",
         'choices'=>[
             ['label'=>'Fin — L\'Évasion','next'=>'__end__','is_death'=>false,'end_title'=>'Vivante','end_text'=>'Tu es sortie. Le monde dans lequel tu as mis les pieds ne te relâchera pas aussi facilement.']
         ]],
        ['key'=>'cap_night_escape','chapter'=>null,'chapter_title'=>null,'bg'=>'escape','speaker'=>'SARA','shake'=>true,'flash'=>true,
         'text'=>"Le câble électrique claque contre la poignée extérieure au moment où le garde ouvre.\n\nIl trébuche. Tu passes.\n\nCouloir. Gauche. Escalier de service. La fenêtre du couloir s'ouvre avec une pression sur le montant supérieur gauche — exactement comme indiqué sur la brique.\n\nL'air de la nuit.\n\nTu tombes à deux mètres cinquante sur de l'herbe mouillée.\n\nTu cours.",
         'choices'=>[
             ['label'=>'Fin — La Fugitive','next'=>'__end__','is_death'=>false,'end_title'=>'En Fuite','end_text'=>'Tu t\'es échappée. Ce qu\'ils te voulaient, tu ne le sais toujours pas. Et cette ignorance, la nuit, te brûle.']
         ]],
        ['key'=>'cap_end_escape','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Une heure plus tard, tu es dans une voiture de police.\n\nQuarante-huit heures plus tard, les hommes qui t'ont enlevée sont en garde à vue.\n\nTon père est parmi eux.\n\nTu n'as pas pleuré dans le commissariat. Tu regardes par la fenêtre la ville qui ne s'arrête jamais.",
         'choices'=>[
             ['label'=>'Fin — La Vérité Coûte','next'=>'__end__','is_death'=>false,'end_title'=>'Libre','end_text'=>'Libre. Mais certaines cages n\'ont pas de barreaux visibles.']
         ]],
        ['key'=>'cap_end_deal','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Il vérifie le nom. Il revient.\n\n— Comment tu le sais ?\n\n— Je travaillais avec mon père. J'ai vu des transferts qui ne correspondaient à rien.\n\nMoitié vrai. Assez vrai pour tenir.\n\nIl te libère à l'aube.",
         'choices'=>[
             ['label'=>'Fin — Le Prix','next'=>'__end__','is_death'=>false,'end_title'=>'Libre à Quel Prix','end_text'=>'Tu as survécu. Ton père t\'a livrée. Ces deux vérités vivront ensemble.']
         ]],
        ['key'=>'cap_end_give','chapter'=>null,'chapter_title'=>null,'bg'=>'cell','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Au matin tu leur donnes ce qu'ils veulent.\n\nPas parce que tu as cédé. Parce que tu as calculé.\n\nTrois semaines plus tard, les données que tu leur as fournies ont été rendues publiques dans une fuite anonyme.\n\nLa tienne.",
         'choices'=>[
             ['label'=>'Fin — Le Long Jeu','next'=>'__end__','is_death'=>false,'end_title'=>'Le Long Jeu','end_text'=>'Tu leur as donné exactement ce qu\'il fallait pour les détruire. Eux ne l\'ont jamais compris.']
         ]]
    ]
],
[
    'key'=>'interrogation','title'=>'SANG FROID','color'=>'#8B5A00',
    'description'=>'48 heures. Un prisonnier. Une information. Il n\'y a pas de limites dans cette pièce.',
    'tag'=>'★ Interrogatoire · Pression · Sans pitié',
    'scenes'=>[
        ['key'=>'start','chapter'=>'I','chapter_title'=>'48 Heures','bg'=>'office','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"La salle de briefing a l'odeur des décisions irréversibles.\n\nReyes pose une photo sur la table. Un homme ordinaire. Quarante ans.\n\n— Sebastián Narvez. Logisticien du réseau Solano. Il sait où passe le prochain convoi — vendredi, avant l'aube.\n\nIl te regarde.\n\n— Quarante-huit heures, Damián. Tu as carte blanche.",
         'choices'=>[
             ['label'=>'Lire le dossier. Connaître l\'homme avant la pièce.','next'=>'inter_study','is_death'=>false],
             ['label'=>'Y aller maintenant. L\'élément de surprise.','next'=>'inter_cold','is_death'=>false]
         ]],
        ['key'=>'inter_study','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"Sebastián Narvez. Quarante-deux ans. Une fille — Valentina, neuf ans en novembre.\n\nSa femme est morte d'un cancer il y a quatre ans. Il élève la petite seul.\n\nTu poses le dossier.\n\nCe n'est pas de la pitié que tu ressens. Tu n'as plus les circuits pour ça. C'est de l'analyse : tu sais maintenant exactement quelle touche appuyer et dans quel ordre.\n\nÇa devrait peut-être te déranger davantage.",
         'choices'=>[
             ['label'=>'Entrer dans la salle.','next'=>'inter_enter','is_death'=>false],
             ['label'=>'Chercher une approche sans violence.','next'=>'inter_clever','is_death'=>false]
         ]],
        ['key'=>'inter_cold','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Tu entres sans dossier, sans préparation.\n\nJuste toi et lui et la lumière trop forte qui lui mange les yeux depuis hier soir.\n\nVous vous regardez.\n\nTu ne dis rien.\n\nUne minute. Deux. Le silence dans une pièce comme celle-ci a du poids, du volume, une texture.\n\n— Je veux un avocat.\n\nC'est la ligne qu'il a décidé de tenir. Et la ligne que tu vas passer la journée à éroder.",
         'choices'=>[
             ['label'=>'"Dans quarante-huit heures. Peut-être."','next'=>'inter_threat','is_death'=>false],
             ['label'=>'Ignorer. Continuer le silence.','next'=>'inter_silence','is_death'=>false]
         ]],
        ['key'=>'inter_enter','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"Tu t'assieds. Tu poses le dossier fermé. Tu ne l'ouvres pas.\n\n— Je sais pour Valentina. Elle va avoir neuf ans en novembre.\n\nSon corps, lui — quelque chose dans ses épaules, infinitésimal.\n\n— Je ne dis pas ça pour te menacer. Je dis ça pour que tu comprennes ce que tu as à perdre.\n\nTu poses les mains sur la table.\n\n— Ou l'inverse.",
         'choices'=>[
             ['label'=>'Laisser ce silence faire son travail.','next'=>'inter_silence','is_death'=>false],
             ['label'=>'Aller plus loin — sortir la photo de Valentina.','next'=>'inter_valentina','is_death'=>false]
         ]],
        ['key'=>'inter_clever','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"La violence laisse des traces. Les informations obtenues sous contrainte ont un taux d'erreur qui peut ruiner une opération.\n\nTu fais préparer une chambre propre. De la nourriture décente. Une douche.\n\nQuand tu entres, tu portes deux cafés.\n\nNarvez te regarde comme si c'était un piège.\n\n— C'en est un, tu dis en posant un café devant lui. Tout l'est. Mais le café est vrai.",
         'choices'=>[
             ['label'=>'Jouer la carte de l\'humanité.','next'=>'inter_deal','is_death'=>false],
             ['label'=>'Trop lent. Changer d\'approche.','next'=>'inter_threat','is_death'=>false]
         ]],
        ['key'=>'inter_silence','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"Vingt-deux minutes.\n\n— Je sais pas de quoi vous parlez.\n\nSa voix s'est légèrement affaissée depuis le début.\n\n— Le silence dans une salle d'interrogatoire est une forme de violence. La plus propre. Celle qui ne laisse pas de marque.\n\nTu te lèves. Tu contournes la table.\n\n— Quarante-six heures restantes. Repose-toi un peu.\n\nTu sors. Tu le laisses seul.\n\nLe vide, parfois, est le meilleur interrogateur.",
         'choices'=>[
             ['label'=>'Revenir dans deux heures — frappe suivante.','next'=>'inter_hour12','is_death'=>false],
             ['label'=>'Envoyer quelqu\'un d\'autre d\'abord — le déstabiliser.','next'=>'inter_reyes','is_death'=>false]
         ]],
        ['key'=>'inter_threat','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"— Dans quarante-huit heures. Peut-être.\n\nIl entend le «peut-être».\n\n— Sebastián. Dans dix heures, tu auras envie de parler mais tu tiendras. Dans vingt heures, tu commenceras à calculer. Dans trente-cinq heures, ton corps t'aura abandonné avant ta tête.\n\n— Moi je préférerais qu'on saute les trente premières heures. Pas par pitié. Par efficacité.",
         'choices'=>[
             ['label'=>'Lui donner le temps de réfléchir.','next'=>'inter_hour12','is_death'=>false],
             ['label'=>'Commencer maintenant — escalade immédiate.','next'=>'inter_physical','is_death'=>false]
         ]],
        ['key'=>'inter_valentina','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"Tu sors la photo de Valentina.\n\nTu la poses sur la table avec une précision qui est pire que la brutalité.\n\nIl ne la regarde pas — ou plutôt il la regarde sans avoir l'air de la regarder.\n\n— Tu es un monstre, dit-il. Voix plate. Constat.\n\n— Oui. C'est pour ça que tu devrais me parler avant que mon supérieur prenne le relais. Lui, c'est pire.",
         'choices'=>[
             ['label'=>'Laisser ça poser. Sortir.','next'=>'inter_hour12','is_death'=>false],
             ['label'=>'Pousser encore.','next'=>'inter_physical','is_death'=>false]
         ]],
        ['key'=>'inter_deal','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'DAMIÁN','shake'=>false,'flash'=>false,
         'text'=>"— Ce que je veux : une adresse, une date, peut-être un nom.\n\n— Et en échange ?\n\n— Ta fille ne figure dans aucun de nos fichiers. Et quand tu sortiras — cinq ans maximum avec témoignage — il n'y aura personne qui t'attendra.\n\n— Vous pouvez garantir ça ?\n\n— Non. Je peux te donner ma parole.\n\n— La parole d'un homme qui m'a enlevé à 3h du matin.\n\n— Exactement cette parole-là.",
         'choices'=>[
             ['label'=>'Attendre sa réponse.','next'=>'inter_breaking','is_death'=>false],
             ['label'=>'Ajouter de la pression — le temps presse.','next'=>'inter_hour12','is_death'=>false]
         ]],
        ['key'=>'inter_reyes','chapter'=>null,'chapter_title'=>null,'bg'=>'blood','speaker'=>null,'shake'=>true,'flash'=>true,
         'text'=>"Reyes entre dans la salle.\n\nTu l'observes de l'autre côté de la vitre sans tain.\n\nSes méthodes sont efficaces — dans le sens où elles obtiennent toujours quelque chose. Ce quelque chose n'est pas toujours vrai, pas toujours utile, et jamais propre.\n\nQuand tu rentres quarante minutes plus tard, l'atmosphère a changé. Narvez a changé.\n\nIl te regarde : est-ce que tu es le pire ou le moins pire ?",
         'choices'=>[
             ['label'=>'Écouter ce qu\'il a à dire.','next'=>'inter_breaking','is_death'=>false],
             ['label'=>'Lui donner le temps de se ressaisir.','next'=>'inter_hour24','is_death'=>false]
         ]],
        ['key'=>'inter_hour12','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Heure douze.\n\nIl a les yeux rouges. Pas de larmes — de manque de sommeil.\n\n— Vendredi. Avant l'aube. C'est tout ce que je veux. Une date, une heure, un endroit.\n\nIl secoue la tête.\n\n— Si je parle, je suis mort.\n\n— Si tu ne parles pas, tu es mort aussi. La différence c'est le délai.\n\n— Et Valentina a besoin de son père vivant. Même derrière des barreaux.",
         'choices'=>[
             ['label'=>'Revenir dans douze heures.','next'=>'inter_hour24','is_death'=>false],
             ['label'=>'Escalader — tu n\'as plus de temps.','next'=>'inter_physical','is_death'=>false]
         ]],
        ['key'=>'inter_physical','chapter'=>null,'chapter_title'=>null,'bg'=>'blood','speaker'=>'DAMIÁN','shake'=>true,'flash'=>true,
         'text'=>"Ce qui se passe dans les six heures suivantes, tu n'en parleras jamais.\n\nPas parce que c'est illégal. Parce que les mots rendraient la chose trop réelle.\n\nNarvez tient longtemps. Plus longtemps que tu ne t'y attendais.\n\nQuand il parle, sa voix est méconnaissable.\n\nTu sors de la salle à l'aube. Le couloir est normal. L'air est normal. Tu t'appuies contre le mur.",
         'choices'=>[
             ['label'=>'Transmettre les informations.','next'=>'inter_end_hard','is_death'=>false]
         ]],
        ['key'=>'inter_hour24','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Heure vingt-quatre.\n\nTu entres avec une chaise. Tu t'assieds près de lui, pas en face.\n\n— Raconte-moi ta fille.\n\nIl te regarde, méfiant.\n\n— Je ne veux pas d'information. Raconte-moi juste.\n\nEt puis — lentement — il parle. De Valentina. De ses dessins sur le réfrigérateur. De la façon dont elle mange les céréales dans un ordre particulier.\n\nÀ la fin, il y a des larmes sur son visage. Il ne s'en rend pas compte.",
         'choices'=>[
             ['label'=>'"Le convoi. Maintenant."','next'=>'inter_breaking','is_death'=>false]
         ]],
        ['key'=>'inter_breaking','chapter'=>null,'chapter_title'=>null,'bg'=>'interrogation','speaker'=>'NARVEZ','shake'=>false,'flash'=>false,
         'text'=>"Il parle.\n\nZaragoza. Un entrepôt frigorifique dans la zone portuaire. Vendredi, entre 4h et 5h. Deux camions. Dix-sept personnes.\n\nIl parle pendant douze minutes sans s'arrêter.\n\nEt quand il s'arrête, la pièce est silencieuse.\n\n— C'est fini maintenant ? murmure-t-il.\n\nTu rassembles tes notes.\n\n— Oui.\n\nPremière fois que tu lui dis la vérité sans calcul derrière.",
         'choices'=>[
             ['label'=>'Transmettre l\'information.','next'=>'inter_end_clean','is_death'=>false]
         ]],
        ['key'=>'inter_end_clean','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Vendredi, 4h22. L'opération intercepte les deux camions à Zaragoza.\n\nDix-sept arrestations. Zéro victime.\n\nLe rapport officiel est propre. Les méthodes décrites en termes neutres.\n\nNarvez plaidera coupable. Cinq ans.",
         'choices'=>[
             ['label'=>'Fin — Résultat Net','next'=>'__end__','is_death'=>false,'end_title'=>'Résultat Net','end_text'=>'L\'opération a réussi. Le rapport est propre. Toi, moins.']
         ]],
        ['key'=>'inter_end_hard','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Le convoi est intercepté. L'opération réussit.\n\nDans la semaine qui suit, tu dors mal.\n\nPas à cause de cauchemars. À 3h du matin tu es réveillé et tu comptes. Les coûts. Les résultats. Le ratio.\n\nReyes appelle pour te féliciter.\n\nTu ne décroches pas.",
         'choices'=>[
             ['label'=>'Fin — Le Ratio','next'=>'__end__','is_death'=>false,'end_title'=>'Le Ratio','end_text'=>'Chaque résultat a un coût. Tu as arrêté de croire que le ratio était acceptable il y a longtemps. Tu continues quand même.']
         ]]
    ]
],
[
    'key'=>'journalist','title'=>'LA VÉRITÉ SAIGNE','color'=>'#1A5A8B',
    'description'=>'Elena Moreau a trouvé quelque chose qu\'elle n\'aurait jamais dû trouver. Et quelqu\'un veut s\'en assurer.',
    'tag'=>'★ Enquête · Danger · Pas de retour',
    'scenes'=>[
        ['key'=>'start','chapter'=>'I','chapter_title'=>'Le Message','bg'=>'night','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Le téléphone crypté vibre à 23h38.\n\nPas de nom. Pas de numéro. Juste une adresse et deux mots.\n\nVENEZ SEULE.\n\nTu es Elena Moreau. Trois semaines que tu enquêtes sur les disparitions dans le quartier portuaire. Jiménez enquêtait sur la même zone — on l'a retrouvé dans le port, les poignets ligotés.\n\nTu attrapes ton appareil photo et tu sors.",
         'choices'=>[
             ['label'=>'Prévenir quelqu\'un avant de partir.','next'=>'journ_backup','is_death'=>false],
             ['label'=>'Y aller seule. Vraiment seule.','next'=>'journ_alone','is_death'=>false]
         ]],
        ['key'=>'journ_backup','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Tu envoies un message à Marco.\n\nAdresse. Heure. \"Si pas de nouvelles dans 90 min, appelle le 17 et Sébastien dans cet ordre.\"\n\nIl répond en six secondes : \"Non.\"\n\nTu rappelles.\n\n— Ils ont retrouvé Jiménez dans le port.\n\n— Je sais.\n\n— Il enquêtait sur la même chose que toi.\n\n— Je sais.\n\n— 90 minutes, tu répètes. Cet ordre.",
         'choices'=>[
             ['label'=>'Arriver à l\'adresse.','next'=>'journ_arrive','is_death'=>false]
         ]],
        ['key'=>'journ_alone','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Tu n'appelles personne.\n\nTu laisses quand même un carnet dans ta veste avec l'adresse, la date, l'heure. Si quelqu'un te retrouve, il aura ça.\n\nDans le taxi, tu penses à Jiménez, retrouvé dans le port la semaine dernière.\n\nTu descends deux rues avant l'adresse. Tu fais le reste à pied, dans l'ombre.",
         'choices'=>[
             ['label'=>'Arriver à l\'adresse.','next'=>'journ_arrive','is_death'=>false]
         ]],
        ['key'=>'journ_arrive','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"L'entrepôt frigorifique désaffecté. L'odeur tu la connais depuis le Rwanda — c'est l'odeur de ce que les hommes font aux autres hommes.\n\nUne lampe torche posée sur une caisse, dirigée vers le fond.\n\nTu avances.\n\nCe que tu trouves au fond va changer quelque chose en toi. Irrémédiablement.",
         'choices'=>[
             ['label'=>'Photographier méthodiquement avant tout.','next'=>'journ_evidence','is_death'=>false],
             ['label'=>'Chercher si quelqu\'un est encore là.','next'=>'journ_search','is_death'=>false]
         ]],
        ['key'=>'journ_search','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'ELENA','shake'=>false,'flash'=>true,
         'text'=>"Dans l'angle le plus sombre — une forme.\n\nUn homme. Recroquevillé. Il lève les yeux quand ta lampe l'atteint.\n\nPas de la douleur dans ce regard. Le vide de quelqu'un qui a été vidé.\n\nIl a peut-être quarante ans. Il en paraît vingt de plus.\n\n— Ils sont partis, murmure-t-il.\n\n— Je m'appelle Elena. Je suis journaliste. Tu es en sécurité.\n\nLe mensonge le plus utile que tu aies jamais dit.",
         'choices'=>[
             ['label'=>'L\'écouter. Enregistrer.','next'=>'journ_survivor','is_death'=>false],
             ['label'=>'Appeler les secours en premier.','next'=>'journ_call_first','is_death'=>false]
         ]],
        ['key'=>'journ_survivor','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Il s'appelle Rafa. Trente-huit ans.\n\nIl parle vingt-cinq minutes. Sa voix est plate — les circuits pour l'émotion sont coupés pour l'instant.\n\nIl parle des questions. Toujours les mêmes. De ce qui se passait quand les réponses ne venaient pas.\n\n— Tu as vu leurs visages ?\n\n— Un seul. Sans cagoule.\n\nLa description qu'il te donne — tu la reconnais. Tu l'as vue en photo, dans un dossier confidentiel, trois mois avant que ta source disparaisse.",
         'choices'=>[
             ['label'=>'Lui demander de répéter. Mémoriser chaque détail.','next'=>'journ_vega_link','is_death'=>false],
             ['label'=>'Appeler les secours. Il passe avant l\'histoire.','next'=>'journ_call_first','is_death'=>false]
         ]],
        ['key'=>'journ_evidence','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Tu travailles vite et en silence.\n\nLes anneaux fixés aux murs — nouveaux, vis encore brillantes. Matériel médical pour maintenir les gens en vie assez longtemps.\n\nDans le coin : un carnet à moitié brûlé. Des noms. Des dates. Des montants.\n\nTu photographies chaque page.\n\nEt tu lis.\n\nEn haut de la liste des financeurs : les initiales R.V.",
         'choices'=>[
             ['label'=>'Chercher qui se cache derrière R.V.','next'=>'journ_rv_search','is_death'=>false],
             ['label'=>'Publier maintenant — avant de disparaître.','next'=>null,'is_death'=>true,'death_msg'=>'Tu as publié avant de comprendre qui te lisait. Ils ont vu l\'article cinq minutes après la mise en ligne. Tu n\'as pas eu le temps d\'en écrire un deuxième.']
         ]],
        ['key'=>'journ_call_first','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Tu appelles le 112.\n\nPendant sept minutes tu travailles. Le carnet que tu trouves dans le coin, tu le prends.\n\nQuand les ambulanciers arrivent tu leur remets Rafa.\n\nDans le taxi, tu le lis.\n\nÀ la deuxième page, les initiales R.V. apparaissent sept fois.\n\nTon téléphone sonne. Numéro inconnu.",
         'choices'=>[
             ['label'=>'Répondre.','next'=>'journ_call_response','is_death'=>false],
             ['label'=>'Ne pas répondre. Analyser d\'abord.','next'=>'journ_vega_name','is_death'=>false]
         ]],
        ['key'=>'journ_rv_search','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Rafael Vega.\n\nUn nom sans dossier public. Sans photo accessible. Une présence fantôme dans quatre enquêtes non résolues sur trois continents.\n\nCe n'est pas un criminel. C'est une architecture.\n\nTu envoies les photos du carnet à trois serveurs différents.\n\nPuis ton téléphone sonne.\n\n— Señorita Moreau. Je sais que vous êtes chez vous. Je sais ce que vous venez de trouver. On devrait se parler.",
         'choices'=>[
             ['label'=>'"Où et quand ?"','next'=>'journ_meet','is_death'=>false],
             ['label'=>'Raccrocher et quitter ton appartement immédiatement.','next'=>null,'is_death'=>true,'death_msg'=>'Tu as raccroché. Tu as pris ton sac. La porte s\'est ouverte de l\'extérieur avant que tu atteignes la serrure.']
         ]],
        ['key'=>'journ_vega_link','chapter'=>null,'chapter_title'=>null,'bg'=>'warehouse','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Rafael Vega.\n\nTu appelles les secours pour Rafa. Tu l'attends avec lui.\n\nDans le taxi du retour, tu regardes les photos que tu as prises.\n\nTon téléphone sonne. Numéro inconnu.",
         'choices'=>[
             ['label'=>'Répondre.','next'=>'journ_call_response','is_death'=>false],
             ['label'=>'Laisser sonner.','next'=>'journ_vega_name','is_death'=>false]
         ]],
        ['key'=>'journ_vega_name','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>'ELENA','shake'=>false,'flash'=>false,
         'text'=>"Tu rentres. Tu verrouilles à double tour.\n\nÀ 4h12, quelqu'un sonne à ton interphone.\n\nTu ne réponds pas.\n\nÀ 4h13, une enveloppe passe sous ta porte.\n\nDedans : une photo de toi dans l'entrepôt ce soir. Il y avait quelqu'un d'autre là-bas.\n\nEt en dessous : un numéro de téléphone.",
         'choices'=>[
             ['label'=>'Appeler le numéro.','next'=>'journ_call_response','is_death'=>false],
             ['label'=>'Appeler la police.','next'=>null,'is_death'=>true,'death_msg'=>'La police est arrivée. Ils ont pris le carnet comme pièce à conviction. Deux jours plus tard, le dossier a été classé. L\'histoire est morte avec lui.']
         ]],
        ['key'=>'journ_call_response','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>'VOIX','shake'=>false,'flash'=>false,
         'text'=>"— Rafael Vega.\n\nUn silence de deux secondes.\n\n— Vous êtes meilleure que je pensais. Ce que vous avez trouvé dans cet entrepôt n'est pas mon œuvre.\n\n— Mais votre financement y figure.\n\n— Oui. Ce qui me place dans une position inconfortable.\n\n— Pourquoi m'appeler ?\n\n— Parce que l'autre option était plus définitive. Et moins intéressante.\n\n— Il y a un café. Rue del Carme. Dans vingt minutes.",
         'choices'=>[
             ['label'=>'Y aller.','next'=>'journ_meet','is_death'=>false],
             ['label'=>'Refuser. Publier ce que tu as.','next'=>'journ_publish','is_death'=>false]
         ]],
        ['key'=>'journ_meet','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Il est assis dans le fond, dos au mur. Cette position n'est jamais un hasard.\n\nGrand. Des yeux sombres qui t'évaluent avant même que tu t'assoies.\n\n— L'entrepôt appartient aux frères Salazar. Mes adversaires. L'argent a été placé là pour créer une association avec mon nom.\n\nIl pose un dossier sur la table.\n\n— Pourquoi moi ?\n\n— Parce que vous enquêtez depuis trois semaines sans vous arrêter. Les gens qui ne s'arrêtent pas finissent soit par trouver, soit par être trouvés.\n\nIl te regarde.\n\n— Je préfère que ce soit vous qui trouviez.",
         'choices'=>[
             ['label'=>'Prendre le dossier. Vérifier avant de publier.','next'=>'journ_end_deal','is_death'=>false],
             ['label'=>'"Je ne travaille pas avec mes sujets."','next'=>'journ_end_defiance','is_death'=>false],
             ['label'=>'Faire semblant d\'accepter — publier quand même.','next'=>null,'is_death'=>true,'death_msg'=>'Il a su. Il savait avant que tu partes. Les gens qui font ça depuis assez longtemps reconnaissent ce sourire.']
         ]],
        ['key'=>'journ_publish','chapter'=>null,'chapter_title'=>null,'bg'=>'office','speaker'=>'ELENA','shake'=>false,'flash'=>true,
         'text'=>"Tu publies à 3h17 du matin.\n\nÀ 9h, Marco appelle.\n\n— Elena. Ton appartement.\n\n— Qu'est-ce qu'il s'est passé ?\n\n— Quelqu'un est entré. Rien de volé. Mais ils ont laissé quelque chose.\n\nUne photo de toi dans l'entrepôt cette nuit.\n\nL'histoire tourne. Et quelqu'un veut s'assurer que tu ne pourras pas écrire la suite.",
         'choices'=>[
             ['label'=>'Continuer — tu ne t\'arrêtes pas.','next'=>'journ_end_defiance','is_death'=>false],
             ['label'=>'Appeler Vega.','next'=>'journ_call_response','is_death'=>false]
         ]],
        ['key'=>'journ_end_deal','chapter'=>null,'chapter_title'=>null,'bg'=>'mansion','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"Tu vérifies pendant soixante-douze heures.\n\nChaque élément du dossier tient. Les Salazar sont arrêtés quarante-huit heures après la publication.\n\nVega reste où il a toujours été.\n\nIl t'envoie un message une semaine après.\n\n\"Bon travail, señorita Moreau.\"\n\nTu ne réponds pas. Mais tu gardes le message.",
         'choices'=>[
             ['label'=>'Fin — La Source','next'=>'__end__','is_death'=>false,'end_title'=>'La Source','end_text'=>'Tu as publié la vérité. Quelqu\'un t\'a aidée à la trouver. Ces deux choses coexisteront inconfortablement.']
         ]],
        ['key'=>'journ_end_defiance','chapter'=>null,'chapter_title'=>null,'bg'=>'night','speaker'=>null,'shake'=>false,'flash'=>false,
         'text'=>"— Je ne travaille pas avec mes sujets.\n\nTu marches vers la sortie.\n\n— Señorita Moreau.\n\nTu t'arrêtes. Tu ne te retournes pas.\n\n— Je respecte ça. Plus que vous ne le croiriez.\n\nTu sors.\n\nTon article déclenche la plus grande enquête judiciaire ouverte en trois ans.\n\nTu ne sais pas si Vega est coupable. Tu as publié ce que tu pouvais prouver.\n\nC'est ce que le métier veut dire.",
         'choices'=>[
             ['label'=>'Fin — La Ligne','next'=>'__end__','is_death'=>false,'end_title'=>'La Ligne','end_text'=>'Tu n\'as pas plié. C\'est ce qui définit ce métier. Et ce qui le rend mortel.']
         ]]
    ]
]
];

$conn->query("SET FOREIGN_KEY_CHECKS=0");
$conn->query("TRUNCATE TABLE choices");
$conn->query("TRUNCATE TABLE scenes");
$conn->query("TRUNCATE TABLE stories");
$conn->query("SET FOREIGN_KEY_CHECKS=1");

foreach ($stories as $storyData) {
    $storyId = insertStory($conn, $storyData);
    foreach ($storyData['scenes'] as $sceneData) {
        $sceneId = insertScene($conn, $storyId, $sceneData);
        foreach ($sceneData['choices'] as $order => $choice) {
            insertChoice($conn, $sceneId, $choice, $order);
        }
    }
}

echo "<h2>✅ Installation terminée !</h2>";
echo "<p>Base de données <strong>" . DB_NAME . "</strong> créée avec :</p><ul>";
$r = $conn->query("SELECT COUNT(*) as n FROM stories"); echo "<li>".$r->fetch_assoc()['n']." histoires</li>";
$r = $conn->query("SELECT COUNT(*) as n FROM scenes");  echo "<li>".$r->fetch_assoc()['n']." scènes</li>";
$r = $conn->query("SELECT COUNT(*) as n FROM choices"); echo "<li>".$r->fetch_assoc()['n']." choix</li>";
echo "</ul><p><a href='index.php'>▶ Lancer le jeu</a></p>";
$conn->close();
