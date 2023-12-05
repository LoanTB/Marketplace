<?php

use App\Ecommerce\Controleur\ControleurArticle;
use App\Ecommerce\Controleur\ControleurCommenter;
use App\Ecommerce\Controleur\ControleurWishlist;
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;

/* @var $id_article;*/

$article = (new ArticleRepository())->recupererParUnique($id_article,0);
if ($article == null){
    MessageFlash::ajouter("warning", "L'article demandé est introuvable !");
    ControleurArticle::afficherListe();
}

$imagesArticle = (new illustrerRepository())->recupererImagesArticle($article->getIdArticle());
$userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
echo '
<div id="mainClass">
    <link rel="stylesheet" href="../ressources/css/ArticleDetail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <h1 id="articleTitle">'.htmlspecialchars($article->getNom()).'</h1>

    <div id="picturesZone">';

for ($i=0;$i<3;$i++) {
    if (isset($imagesArticle[$i])){
        echo '<img src="'.$imagesArticle[$i].'">';
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
        <img src="';

if ($userEntity->getUrlImage()==null){
    echo '../ressources/img/unknown.png';
} else {
    echo $userEntity->getUrlImage();
}

echo '   ">      
         <div id="authorDesc">
                <p>Posté par :</p>
                <h3>' . htmlspecialchars($userEntity->getPrenom()) . ' ' . htmlspecialchars($userEntity->getNom()) . '</h3>
            </div>
        </div>
        <div class="CTAbuttons">
            <a id="addToCart" href="';

if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
    echo 'controleurFrontal.php?controleur=article&action=afficherFormulaireMiseAJour&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '">Modifier l\'article</a>';
}
else if (\App\Ecommerce\Controleur\ControleurPanier::estDansPanier($article->getIdArticle())) {
    echo 'controleurFrontal.php?action=afficherListe&controleur=panier">Voir dans le panier</a>';
} else {
    echo 'controleurFrontal.php?controleur=panier&action=ajouterAuPanier&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '">Ajouter au panier</a>';
}

echo '<a class="animated-button';

if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
    echo ' critical" href="controleurFrontal.php?controleur=article&action=supprimer&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '"><span id="addToFav">Supprimer l\'article</span>';
} else if (ConnexionUtilisateur::estConnecte()) {
    echo '" href="controleurFrontal.php?controleur=wishlist&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) . '&';
    if (ControleurWishlist::estDansFavoris(ConnexionUtilisateur::getIdUtilisateurConnecte(), $article->getIdArticle())) {
        echo 'action=supprimerArticleDesFavoris"><span id="addToFav">Supprimer des favoris</span>';
    } else {
        echo 'action=ajouterArticleAuxFavoris"><span id="addToFav">Ajouter aux favoris</span>';
    }
} else {
    echo '" href="controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur"><span id="addToFav">Ajouter aux favoris</span>';
}

$tousCommentaires = ControleurCommenter::recupererListeCommentaires($article->getIdArticle());

echo '<span></span>
            </a>
        </div>
    </div>
</div>

<div id="commentaires">
    <div id="commentHeader">
        <h1>';

if (count($tousCommentaires) > 0){
    $moyenne = 0;
    foreach ($tousCommentaires as $commentaire){
        $moyenne += $commentaire->getNote();
    }
    $moyenne /= count($tousCommentaires);

    echo count($tousCommentaires).' Commentaires</h1><h1>';
    for ($i=0;$i<=5;$i++){
        if (($moyenne*5)-$i > 1) {
            echo'<span class="fa fa-star starIsChecked"></span>';
        } else if (($moyenne*5)-$i < 1 and ($moyenne*5)-$i > 0) {
            echo'<span class="fa fa-star-half starIsChecked"></span>';
        } else if (($moyenne*5)-$i < 0 and ($moyenne*5)-$i > -1) {
            echo'<span class="fa fa-star-half" style="transform: rotateY(180deg) translateX(10%);"></span>';
        } else if (($moyenne*5)-$i < -1) {
            echo'<span class="fa fa-star"></span>';
        } else if (($moyenne*5)-$i == 0) {
            echo'<span class="fa fa-star starIsChecked"></span>';
        }
    }
} else {
    echo 'Aucun commentaire';
}
echo '</h1></div>';

$commentaireUtilisateur = ConnexionUtilisateur::estConnecte() ? ControleurCommenter::recupererCommentaireUtilisateur($article->getIdArticle(), ConnexionUtilisateur::getIdUtilisateurConnecte()) : null;

if (ConnexionUtilisateur::estConnecte() && $article->getIdUtilisateur() != ConnexionUtilisateur::getIdUtilisateurConnecte()) {

        echo '<form id="commenter" method="GET" action=\'controleurFrontal.php\'>
            <input type="text" class="commentInput" name="titre" placeholder="Titre" value="';
            if (!is_null($commentaireUtilisateur)) echo $commentaireUtilisateur->getTitre();
            echo '" required>
            <textarea name="texte" placeholder="Description" class="commentInput" required>';
            if (!is_null($commentaireUtilisateur)) echo $commentaireUtilisateur->getTexte();
            echo '</textarea>
            <div id="commentActions">';
            if (!is_null($commentaireUtilisateur)) echo '<a id="deleteBtn" href="controleurFrontal.php?action=supprimerCommentaire&controleur=commenter&id_article='.$article->getIdArticle().'">Supprimer</a>';
            echo '<div id="half-stars-example">
                    <div class="rating-group">
                        <input class="rating__input rating__input--none" ';
                        if (is_null($commentaireUtilisateur) || $commentaireUtilisateur->getNote() == 0) echo "checked";
                        echo ' name="note" id="rating2-0" value="0" type="radio">
                        <label aria-label="0 stars" class="rating__label" for="rating2-0">&nbsp;</label>
                        <label aria-label="0.5 stars" class="rating__label rating__label--half" for="rating2-05"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.09) echo "checked";
                        echo ' name="note" id="rating2-05" value="0.1" type="radio">
                        <label aria-label="1 star" class="rating__label" for="rating2-10"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.19) echo "checked";
                        echo ' name="note" id="rating2-10" value="0.2" type="radio">
                        <label aria-label="1.5 stars" class="rating__label rating__label--half" for="rating2-15"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.29) echo "checked";
                        echo ' name="note" id="rating2-15" value="0.3" type="radio">
                        <label aria-label="2 stars" class="rating__label" for="rating2-20"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.39) echo "checked";
                        echo ' name="note" id="rating2-20" value="0.4" type="radio">
                        <label aria-label="2.5 stars" class="rating__label rating__label--half" for="rating2-25"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.49) echo "checked";
                        echo ' name="note" id="rating2-25" value="0.5" type="radio">
                        <label aria-label="3 stars" class="rating__label" for="rating2-30"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.59) echo "checked";
                        echo ' name="note" id="rating2-30" value="0.6" type="radio">
                        <label aria-label="3.5 stars" class="rating__label rating__label--half" for="rating2-35"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.69) echo "checked";
                        echo ' name="note" id="rating2-35" value="0.7" type="radio">
                        <label aria-label="4 stars" class="rating__label" for="rating2-40"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.79) echo "checked";
                        echo ' name="note" id="rating2-40" value="0.8" type="radio">
                        <label aria-label="4.5 stars" class="rating__label rating__label--half" for="rating2-45"><i class="rating__icon rating__icon--star fa fa-star-half"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() > 0.89) echo "checked";
                        echo ' name="note" id="rating2-45" value="0.9" type="radio">
                        <label aria-label="5 stars" class="rating__label" for="rating2-50"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                        <input class="rating__input" ';
                        if (!is_null($commentaireUtilisateur) && $commentaireUtilisateur->getNote() == 1) echo "checked";
                        echo ' name="note" id="rating2-50" value="1" type="radio">
                    </div>
                </div>

                <input type="hidden" name="controleur" value="commenter">
                <input type="hidden" name="action" value="';
                if (is_null($commentaireUtilisateur)) echo 'ajouterCommentaire'; else echo 'modifierCommentaire';
                echo '">
                <input type="hidden" name="id_article" value="'.htmlspecialchars(rawurlencode($article->getIdArticle())).'">

                <input type="submit" value="';
                if (is_null($commentaireUtilisateur)) echo 'Commenter'; else echo 'Modifier';
                echo '">
            </div>
        </form>';
}

echo '<div id="commentaireScrollable">';

foreach ((!is_null($commentaireUtilisateur) ? ControleurCommenter::recupererListeSaufCommentaireUtilisateur($article->getIdArticle(), ConnexionUtilisateur::getIdUtilisateurConnecte()) : $tousCommentaires) as $commentaire){
        $userObj = (new UtilisateurRepository)->recupererParUnique($commentaire->getIdUtilisateur(), 0);
        echo'<div class="commentaire">
               <img src="';
        if ($userObj->getUrlImage()==null){
            echo '../ressources/img/unknown.png';
        } else {
            echo $userObj->getUrlImage();
        }
        echo'">
            <h3>'.htmlspecialchars($userObj->getPrenom()).' '.htmlspecialchars($userObj->getNom()).'</h3>
            <div>
                <div class="commentaireEntete">
                    <h3>'.htmlspecialchars($commentaire->getTitre()).'</h3>
                    <div class="note">';
                        for ($i=0;$i<=5;$i++){
                            if (($commentaire->getNote()*5)-$i > 1) {
                                echo'<span class="fa fa-star starIsChecked"></span>';
                            } else if (($commentaire->getNote()*5)-$i < 1 and ($commentaire->getNote()*5)-$i > 0) {
                                echo'<span class="fa fa-star-half starIsChecked"></span>';
                            } else if (($commentaire->getNote()*5)-$i < 0 and ($commentaire->getNote()*5)-$i > -1) {
                                echo'<span class="fa fa-star-half" style="transform: rotateY(180deg) translateX(10%);"></span>';
                            } else if (($commentaire->getNote()*5)-$i < -1) {
                                echo'<span class="fa fa-star"></span>';
                            } else if (($commentaire->getNote()*5)-$i == 0) {
                                echo'<span class="fa fa-star starIsChecked"></span>';
                            }
                        }
//                        for ($x = 0.2; $x <= ($commentaire->getNote()+0.11); $x+=0.2) {
//                            if ($x > $commentaire->getNote()) {
//                                echo'<span class="fa fa-star-half starIsChecked"></span>';
//                            } else {
//                                echo'<span class="fa fa-star starIsChecked"></span>';
//                            }
//                        }
                    echo '</div>
                </div>
                <p>'.htmlspecialchars($commentaire->getTexte()).'</p>
            </div>
        </div>';
}

echo'</div>
</div>';
