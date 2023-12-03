<?php

use App\Ecommerce\Controleur\ControleurWishlist;
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
use \App\Ecommerce\Modele\Repository\relations\illustrerRepository;

/* @var $article \App\Ecommerce\Modele\DataObject\Article */

$imagesArticle = (new illustrerRepository())->recupererImagesArticle($article->getIdArticle());
$userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
echo '
<div id="mainClass">
    <link rel="stylesheet" href="../ressources/css/ArticleDetail.css">

    <h1 id="articleTitle">'.htmlspecialchars($article->getNom()).'</h1>

    <div id="picturesZone">';

for ($i=0;$i<3;$i++) {
    if (isset($imagesArticle[$i])){
        echo '<img src="'.$imagesArticle[$i].'">';
    } else {
        echo '<img src="https://picsum.photos/300">';
    }
}

echo '    </div>

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

if (\App\Ecommerce\Controleur\ControleurPanier::estDansPanier($article->getIdArticle())) {
    echo 'controleurFrontal.php?action=afficherListe&controleur=panier">Voir dans le panier</a>';
} else {
    echo 'controleurFrontal.php?controleur=panier&action=ajouterAuPanier&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '">Ajouter au panier</a>';
}

echo '<a class="animated-button" href="';

if (ConnexionUtilisateur::estConnecte()) {
    echo 'controleurFrontal.php?controleur=wishlist&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '&';
    if (ControleurWishlist::estDansFavoris(ConnexionUtilisateur::getIdUtilisateurConnecte(), $article->getIdArticle())) {
        echo 'action=supprimerArticleDesFavoris"><span id="addToFav">Supprimer des favoris</span>';
    } else {
        echo 'action=ajouterArticleAuxFavoris"><span id="addToFav">Ajouter aux favoris</span>';
    }
} else {
    echo 'controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur"><span id="addToFav">Ajouter aux favoris</span>';
}

echo '<span></span>
            </a>
        </div>
    </div>
</div>
';
echo "<a href='controleurFrontal.php?controleur=commenter&action=ajouterCommentaire&id_article=".htmlspecialchars(rawurlencode($article->getIdArticle()))."&titre=LE DEV A PAS ENCORE FAIT SON BOULOT&note=0.25&texte=Il faut travailler PLUS ENCORE PLUS'> Ajouter le commentaire LE DEV A PAS ENCORE FAIT SON BOULOT 25% content avec comme texte 'Il faut travailler PLUS ENCORE PLUS' !</a>";
foreach (\App\Ecommerce\Controleur\ControleurCommenter::recupererListeCommentaires($article->getIdArticle()) as $commentaire){
    echo "<p>".htmlspecialchars($commentaire->getTitre())." [".htmlspecialchars($commentaire->getNote()*5)."/5] : ".htmlspecialchars($commentaire->getTexte())." </p>";
}
