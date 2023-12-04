<?php
/* @var $articles */
/** @var $requete String */

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;

echo '<link rel="stylesheet" href="../ressources/css/SimpleListe.css">
    <div id="enteteListe">
        <h1>Résultats de la recherche</h1>';

if (empty($articles)) {
    echo "<h3>Aucun résultat trouvé pour ";
} else {
    echo '<h3>'.count($articles).' résultats trouvés pour ';
}

echo htmlspecialchars($requete)."</h3>";


echo '</div><div id="articleList">';

foreach ($articles as $article) {
    echo '<div class="card animationList">
            <div class="listItem articleView">
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '" class="thumbnail" style="background-image: url('.(new illustrerRepository())->recupererImagesArticle($article->getIdArticle())[0].')"></a>
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '" class="articleDesc">
                    <h2>'.htmlspecialchars($article->getNom()).'</h2>
                    <div class="authorRow">
                        <h4>Auteur</h4>
                    </div>
                </a>
                <div class="rowActions editOptions">
                    <p class="price';
    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
        echo '">'.htmlspecialchars($article->getPrix()).' €</p><a href="controleurFrontal.php?controleur=article&action=afficherFormulaireMiseAJour&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '"><div class="svg edit-icon-fill"></div></a>
        <a href="controleurFrontal.php?controleur=article&action=supprimer&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '"><div class="svg delete-icon-fill last-icon"></div></a>';
    } else {
        echo ' last-icon">'.htmlspecialchars($article->getPrix()).' €</p>';
    }

    echo '</div>
            </div>
        </div>';
}
echo '</div>';
