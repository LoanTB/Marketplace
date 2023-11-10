<?php
use \App\Ecommerce\Lib\MessageFlash;
use \App\Ecommerce\Lib\ConnexionUtilisateur;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="./../ressources/css/navstyle.css" rel="stylesheet">
    <link href="./../ressources/css/bootstrapAlerts.css" rel="stylesheet">
    <link href="./../ressources/css/svg_assets.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title><?php /* @var $pagetitle */ echo $pagetitle; ?></title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li class="icon"><img src="../ressources/img/logo.png" title="Occasion/20" alt="Logo"></li>
            <li class="new"><div id="navbutton">Nouvelle annonce<div class="svg add-icon"></div></div></li>
            <li class="searchbar"><label id="search"><input placeholder="Rechercher un article"><div class="svg mag-icon"></div></label></li>
            <li class="menubutton"><a href="controleurFrontal.php?action=afficherListe&controleur=article"><div class="svg cart-icon"></div>Articles</a></li>
            <li class="menubutton"><a href="controleurFrontal.php?action=formulairePreference"><div class="svg favorite-icon"></div>Favoris</a></li>
            <li class="menubutton"><a href="
            <?php
            if (ConnexionUtilisateur::estConnecte()){
                echo 'controleurFrontal.php?action=afficherDetail&controleur=utilisateur&login='.ConnexionUtilisateur::getLoginUtilisateurConnecte().'"><div class="svg account-icon"></div>'.ConnexionUtilisateur::getLoginUtilisateurConnecte();
            } else {
                echo 'controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur"><div class="svg account-icon"></div>Compte';
            }
            ?>
            </a></li>
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
    <div class="footer">
                <div class="aboutrow">
                    <h6>À propos</h6>
                    <p>Ce site est un site d'e-commerce développé dans le but de vous offrir la meilleure expérience utilisateur possible.
                    </p>
                    <p>N'hésitez pas à nous suivre sur les réseaux sociaux via les liens disponibles ci-dessous</p>
                </div>
                <div class="quicklinks">
                    <div id="categories">
                        <h6>Plan du site</h6>
                        <ul class="liensfooter">
                            <li><a>Accueil</a></li>
                            <li><a>Articles</a></li>
                            <li><a>Options</a></li>
                            <li><a>404</a></li>
                            <li><a>Administration</a></li>
                        </ul>
                    </div>
                    <div id="contact">
                        <h6>Créé par</h6>
                        <ul class="liensfooter">
                            <li><a>Dorian B.</a></li>
                            <li><a>Loan TB.</a></li>
                            <li><a>Dall E.</a></li>
                            <li><a>Lucas C.</a></li>
                            <li><a>Rick A.</a></li>
                        </ul>
                    </div>
                </div>

                </div>
                <div id="copyright">
                    <p>Copyleft &copy; 2023 All Wrongs Reserved</p>
                </div>
                </footer>
</body>
</html>