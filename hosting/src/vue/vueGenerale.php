<?php
use \App\Ecommerce\Lib\MessageFlash;
use \App\Ecommerce\Lib\ConnexionUtilisateur;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="./../ressources/css/navstyle.css" rel="stylesheet">
    <link href="./../ressources/css/bootstrapAlerts.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title><?php /* @var $pagetitle */ echo $pagetitle; ?></title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="controleurFrontal.php?action=afficherListe&controleur=article">Articles</a></li>
            <li><a href="controleurFrontal.php?action=formulairePreference"><img src="./../ressources/img/heart.png" alt="cœur"></a></li>
            <?php
            if (ConnexionUtilisateur::estConnecte()){
                echo '<li><a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login='.ConnexionUtilisateur::getIdUtilisateurConnecte().'"><img src="./../ressources/img/user.png" alt="infos utilisateur"></a></li>';
                echo '<li><a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur"><img src="./../ressources/img/sortir.png" alt="déconnexion"></a></li>';
            } else {
                echo '<li><a href="controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur"><img src="./../ressources/img/enter.png" alt="connexion"></a></li>';
            }
            ?>
        </ul>
    </nav>
</header>
<main>
    <?php
    $values = MessageFlash::lireTousMessages();
    foreach ($values as $value){
        echo $value;
    }
    /* @var $cheminVueBody */
    require __DIR__ . "/{$cheminVueBody}";
    ?>
</main>
<footer>
    <p>
        Site de Ecommerce de ...
    </p>
</footer>
</body>
</html>

