<?php

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

/* @var $article \App\Ecommerce\Modele\DataObject\Article */

$userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
echo '
<div id="mainClass">
    <link rel="stylesheet" href="../ressources/css/ArticleDetail.css">

    <h1 id="articleTitle">'.htmlspecialchars($article->getNom()).'</h1>

    <div id="picturesZone">
            <img src="https://picsum.photos/200">
            <img src="https://picsum.photos/200/300">
            <img src="https://picsum.photos/300">
    </div>

    <div id="articleDescription">
        <p>'.htmlspecialchars($article->getDescription()).'</p>
    </div>

    <div id="sidebarPrice" class="sidebarItem">
        <h2 id="price">
            '.htmlspecialchars($article->getPrix()).' €
        </h2>
    </div>
    <div id="sidebarAuthor" class="sidebarItem">
        <div id="authorProps">
            <img src="https://picsum.photos/30">
            <div id="authorDesc">
                <p>Posté par :</p>
                <h3>' . htmlspecialchars($userEntity->getPrenom()) . ' ' . htmlspecialchars($userEntity->getNom()) . '</h3>
            </div>
        </div>
        <div class="CTAbuttons">
            <a id="addToCart" href="';

if (ConnexionUtilisateur::estConnecte()) {
	echo 'controleurFrontal.php?controleur=panier&action=ajouterAuPanier&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle()));
} else {
	echo 'controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur';
}

echo '">Ajouter au panier</a>
            <a class="animated-button">
                <a id="addToFav" href="';

if (ConnexionUtilisateur::estConnecte()) {
    echo 'controleurFrontal.php?controleur=wishlist&action=ajouterArticleAuxFavoris&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle()));
} else {
    echo 'controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur';
}

echo '">Ajouter aux favoris</a>
                <span></span>
            </a>
        </div>
        <!--<div class="annonceProps">
            <p><strong>Mise en ligne :</strong> 24 octobre à 12h00</p>
            <p><strong>Mise à jour :</strong> 17 novembre à 17h24</p>
        </div>-->
    </div>
</div>
';
echo "<a href='controleurFrontal.php?controleur=commenter&action=ajouterCommentaire&id_article=".htmlspecialchars(rawurlencode($article->getIdArticle()))."&titre=LE DEV A PAS ENCORE FAIT SON BOULOT&note=0.25&texte=Il faut travailler PLUS ENCORE PLUS'> Ajouter le commentaire LE DEV A PAS ENCORE FAIT SON BOULOT 25% content avec comme texte 'Il faut travailler PLUS ENCORE PLUS' !</a>";
foreach (\App\Ecommerce\Controleur\ControleurCommenter::recupererListeCommentaires($article->getIdArticle()) as $commentaire){
    echo "<p>".htmlspecialchars($commentaire->getTitre())." [".htmlspecialchars($commentaire->getNote()*5)."/5] : ".htmlspecialchars($commentaire->getTexte())." </p>";
}
