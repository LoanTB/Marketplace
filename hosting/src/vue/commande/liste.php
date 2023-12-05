<?php

use App\Ecommerce\Controleur\ControleurGenerique;
use \App\Ecommerce\Modele\Repository\CommandeRepository;
use \App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
use \App\Ecommerce\Modele\Repository\ArticleRepository;

if (!ConnexionUtilisateur::estConnecte()){
    ControleurGenerique::rediriger();
}

if (ConnexionUtilisateur::estAdministrateur()){
    $commandes = (new CommandeRepository())->recuperer();
} else {
    $commandes = (new CommandeRepository())->recupererParColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),6);
}

$historique = [];
foreach ($commandes as $commande) {
    $historique[] = [$commande,(new ArticleRepository())->recupererParUnique($commande->getIdArticle(),0)];
}

echo '<link rel="stylesheet" href="../ressources/css/SimpleListe.css">
    <div id="enteteListe">
        <h1>Historique d';
if (ConnexionUtilisateur::estAdministrateur()){
    echo 'e tous les achats effectués';
} else {
    echo 'es achats</h1>';
}

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
                <h2>' . htmlspecialchars($article[0]->getNom()) . '</h2>';
            } else {
                echo '<a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article[0]->getIdArticle())) . '" class="thumbnail" style="background-image: url(' . (new illustrerRepository())->recupererImagesArticle($article[0]->getIdArticle())[0] . ')"></a>
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article[0]->getIdArticle())) . '" class="articleDesc">
                <h2>'.htmlspecialchars($article[1]->getNom()).'</h2>';
            }
                    echo '<div class="authorRow">
                        <h4>'.$article[0]->getQuantite().' Acheté ';
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
