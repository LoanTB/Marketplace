<?php

use App\Ecommerce\Controleur\ControleurWishlist;
use App\Ecommerce\Modele\Repository\relations\contenirRepository;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

$articles = (new contenirRepository())->recupererArticlesWishlist(ControleurWishlist::recupererFavoris()->getIdWishlist());

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
    $userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
    echo '<div class="card animationList">
            <div class="listItem articleView">
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '" class="thumbnail" style="background-image: url('.(new illustrerRepository())->recupererImagesArticle($article->getIdArticle())[0].')"></a>
                <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '" class="articleDesc">
                    <h2>'.htmlspecialchars($article->getNom()).'</h2>
                    <div class="authorRow">
                        <h4>'.htmlspecialchars($userEntity->getPrenom()).' '.htmlspecialchars($userEntity->getNom()).'</h4>
                    </div>
                </a>
                <div class="rowActions">
                    <p class="price">'.htmlspecialchars($article->getPrix()).' €</p>
                    <a href="controleurFrontal.php?controleur=wishlist&action=supprimerArticleDesFavoris&id_article=' . rawurlencode($article->getIdArticle()) . '" class="svg close-icon last-icon"></a>
                </div>
            </div>
        </div>';
}
echo '</div>';
