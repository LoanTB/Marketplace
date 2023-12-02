<?php
use \App\Ecommerce\Lib\ConnexionUtilisateur;
/* @var $articles */

echo '<link rel="stylesheet" href="../ressources/css/SimpleListe.css">
    <div id="enteteListe">
        <h1>Vos favoris</h1>';

if (count($articles) === 0) {
    echo "<h3>Vous n'avez pas de favoris pour le moment !</h3>";
} else {
    echo '<h3>Articles en favoris</h3>';
}

echo '</div><div id="articleList">';

foreach ($articles as $article) {
    echo '<div class="card animationList" href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=7">
            <div class="listItem articleView">
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '" class="thumbnail" style="background-image: url(\'https://picsum.photos/300/200\')"></a>
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '" class="articleDesc">
                    <h2>'.htmlspecialchars($article->getNom()).'</h2>
                    <div class="authorRow">
                        <h4>Auteur</h4>
                    </div>
                </a>
                <div class="rowActions">
                    <p class="price">'.htmlspecialchars($article->getPrix()).' €</p>
                    <a href="controleurFrontal.php?controleur=wishlist&action=supprimerArticleDesFavoris&id_article=' . rawurlencode($article->getIdArticle()) . '" class="svg close-icon"></a>
                </div>
            </div>
        </div>';
}
echo '</div>';
