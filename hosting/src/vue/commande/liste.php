<?php

use \App\Ecommerce\Modele\Repository\relations\acheterRepository;
use \App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

$historique = (new acheterRepository())->recupererHistoriqueAchats(ConnexionUtilisateur::getIdUtilisateurConnecte());

if (ConnexionUtilisateur::estAdministrateur()) {
    foreach ((new UtilisateurRepository())->recuperer() as $user) {
        $historique = $historique + (new acheterRepository())->recupererHistoriqueAchats($user->getIdUtilisateur());
    }
}

echo '<link rel="stylesheet" href="../ressources/css/SimpleListe.css">
    <div id="enteteListe">
        <h1>Historique d';
if (ConnexionUtilisateur::estAdministrateur()) echo 'e tous les achats effectués';
else echo 'es achats</h1>';

if (empty($historique)) {
    echo "<h3>Aucune commande passée";
} else {
    echo '<h3>'.count($historique).' articles ont été achetés';
}



echo '</div><div id="articleList">';

foreach ($historique as $article) {
    $userEntity = (new UtilisateurRepository)->recupererParUnique($article[0]->getIdUtilisateur(), 0);
    $articleItem = $article[1];
    echo '<div class="card animationList">
            <div class="listItem articleView">';
            if (is_null($articleItem)) {
                echo '<a class="thumbnail svg no-image-icon notFoundIllustration"></a>
                <a class="articleDesc">
                <h2>Article n°' . htmlspecialchars($article[0]->getIdArticle()) . '</h2>';
            } else {
                echo '<a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article[0]->getIdArticle())) . '" class="thumbnail" style="background-image: url(' . (new illustrerRepository())->recupererImagesArticle($article[0]->getIdArticle())[0] . ')"></a>
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article[0]->getIdArticle())) . '" class="articleDesc">
                <h2>'.htmlspecialchars($article[1]->getNom()).'</h2>';
            }
                    echo '<div class="authorRow">
                        <h4>Acheté ';
    if (ConnexionUtilisateur::estAdministrateur()) {
        echo 'par '.$userEntity->getPrenom().' '.$userEntity->getNom().' ';
    }
    echo 'le '.$userEntity->getJour();

    echo '</h4></div></a>
        <div class="rowActions editOptions">
            <p class="price last-icon">'.htmlspecialchars($article[0]->getPrix()).' €</p>';

    echo '</div>
            </div>
        </div>';
}
echo '</div>';
