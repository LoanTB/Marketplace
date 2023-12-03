<?php

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

/* @var $articles */
echo '<link rel="stylesheet" href="../ressources/css/ArticleListe.css">';

echo '<div id="enteteListe"><h1>Articles mis en vente</h1>
    <h3>Parcourez la liste des articles mis en ligne sur la plateforme</h3></div>
    <div id="articleList">';

foreach ($articles as $article) {
    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
        echo '<div class="card animationList">';
    } else {
        echo '<a class="card animationList" href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '">';
    }
    echo '<div class="articleView">';

    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
        echo '<div class="editOptions">
        <a href="controleurFrontal.php?controleur=article&action=afficherFormulaireMiseAJour&id_article=' . rawurlencode($article->getIdArticle()) . '"><div class="svg edit-icon-fill"></div></a>
        <a href="controleurFrontal.php?controleur=article&action=supprimer&id_article=' . rawurlencode($article->getIdArticle()) . '"><div class="svg delete-icon-fill"></div></a>
        </div>
        <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '">';
    }
    /* Remplacer par un appel à l'image illustrative du produit */
    echo '<div class="thumbnail" style="background-image: url(' . '\'https://picsum.photos/300/200\'' . ')"></div>';

    echo '<div class="articleDesc">
    <h2 class="overflowable">' . htmlspecialchars($article->getNom()) . '
    </h2><div><h4>';
    $userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
    echo $userEntity->getPrenom() . ' ' . $userEntity->getNom();
    echo '</h4>
    <p class="price">' . htmlspecialchars($article->getPrix()).' €
    </p></div></div>';

    if (ConnexionUtilisateur::estAdministrateur() || ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())) {
        echo '</a></div></div>';
    } else {
        echo '</div></a>';
    }
}
echo '</div><script src="../ressources/js/overflowtrigger.js"></script>';

/* Inutilisé : Description du produit -> htmlspecialchars($article->getDescription()) */
