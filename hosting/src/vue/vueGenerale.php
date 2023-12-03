<?php

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;


if (ConnexionUtilisateur::estConnecte()) {
    $utilisateur = (new UtilisateurRepository)->recupererParUnique(ConnexionUtilisateur::getIdUtilisateurConnecte(), 0);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="./../ressources/css/navstyle.css" rel="stylesheet">
    <link href="./../ressources/css/bootstrapAlerts.css" rel="stylesheet">
    <link href="./../ressources/css/svg_assets.css" rel="stylesheet">
    <link href="./../ressources/css/animations.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title><?php /* @var $pagetitle */ echo $pagetitle; ?></title>

    <script src="./../ressources/js/menutrigger.js"></script>
</head>
<body>
<header>
    <nav>
        <ul>
            <li class="icon"><a href="controleurFrontal.php?action=afficherListe&controleur=article"><img src="../ressources/img/logo.png" title="Occasion/20" alt="Logo"></a></li>
            <li class="new"><a href="<?php if(ConnexionUtilisateur::estConnecte()){echo 'controleurFrontal.php?controleur=article&action=afficherFormulaireCreation';}else{echo 'controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireCreation';}?>"><div id="navbutton">Nouvelle annonce<div class="svg add-icon"></div></div></a></li>
            <li class="searchbar">
                <form method="POST" action="controleurFrontal.php">
                    <input type='hidden' name='action' value='afficherListeRecherche'>
                    <input type='hidden' name='controleur' value='article'>
                    <label id="search">
                        <input name="recherche" placeholder="Rechercher un article">
                        <input type="submit" class="svg mag-icon">
                    </label>
                </form>
            </li>
            <li class="menubutton"><a href="controleurFrontal.php?action=afficherListe&controleur=panier"><div class="svg cart-icon"></div>Panier</a></li>
            <li class="menubutton"><a href="controleurFrontal.php?action=afficherFavoris&controleur=wishlist"><div class="svg favorite-icon"></div>Favoris</a></li>
            <li class="menubutton" onclick="triggerAccountMenu()"><a href="#"><div class="svg account-icon"></div>
            <?php if(ConnexionUtilisateur::estConnecte()){echo $utilisateur->getLogin();}else{echo 'Compte';}?>
            </a></li>
        </ul>
    </nav>
    <div id="accountcontrol">
        <ul>
            <?php
            if (ConnexionUtilisateur::estConnecte()) {
                echo '<li class="profilepicture"><img alt="Profile picture" src="../ressources/img/unknown.png">'.$utilisateur->getNom().' '.$utilisateur->getPrenom().'</li>
                <li class="account-menuitem top"><a href="controleurFrontal.php?action=afficherDetail&controleur=utilisateur&id_utilisateur='.ConnexionUtilisateur::getIdUtilisateurConnecte().'"><div class="svg settings-icon"></div><div class="account-buttons">Préférences</div></a></li><li class="separator"></li>
                <li class="account-menuitem bottom"><a href="controleurFrontal.php?action=deconnecter&controleur=utilisateur"><div class="svg logout-icon"></div><div class="account-buttons">Déconnexion</div></a></li>';
            } else {
                echo '<li class="profilepicture"><img alt="Profile picture" src="../ressources/img/unknown.png">Non connecté</li>
                <li class="account-menuitem top bottom"><a href="controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur"><div class="svg key-icon"></div><div class="account-buttons">Connexion</div></a></li>';
            }
            ?>
<!--            <li class="instanceinfo">Version 1.1.0</li> À garder peut être pour plus tard quand on fera un lien entre les commits pour afficher la version-->
        </ul>
    </div>
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
                    <p>Copyleft &#127279; 2023 All Wrongs Reserved</p>
                </div>
                </footer>
</body>
</html>
