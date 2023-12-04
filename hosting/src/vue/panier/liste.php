<?php
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\PanierTemporaire;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;

if (!ConnexionUtilisateur::estConnecte()) {
    $articles = PanierTemporaire::lireArticles();
} else {
    $articles = (new dansPanierRepository())->recupererPanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());
}

echo '<link rel="stylesheet" href="../ressources/css/SimpleListe.css">
    <div id="enteteListe">
        <h1>Votre panier</h1>';

if (empty($articles)) {
    echo "<h3>Votre panier est vide !</h3>";
} else {
    echo '<h3>Articles que vous êtes sur le point d\'acheter</h3>
          <div class="CTAbuttons">
            <a id="addToCart">Finaliser la commande</a>
            <a href="controleurFrontal.php?controleur=panier&action=vider" class="animated-button critical">
                <span>Vider le panier</span>
                <span></span>
            </a>
        </div>';
}

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
                <div class="rowActions">
                    <p class="price">'.htmlspecialchars($article->getPrix()).' €</p>
                    <a href="controleurFrontal.php?controleur=panier&action=supprimerDuPanier&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '" class="svg close-icon last-icon"></a>
                </div>
            </div>
        </div>';
}
echo '</div>';
