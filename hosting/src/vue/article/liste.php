<?php
use \App\Ecommerce\Lib\ConnexionUtilisateur;
/* @var $articles */
foreach ($articles as $article) {
    echo '<p> Article ' . htmlspecialchars($article->getNom()) . ' : ' . htmlspecialchars($article->getDescription()) . ' de login <a href="controleurFrontal.php?controleur=article&action=afficherDetail&id_article=' . rawurlencode($article->getIdArticle()) . '">' . htmlspecialchars($article->getIdArticle()) . '<a>.';
    if (ConnexionUtilisateur::estAdministrateur()) {
        echo ' (<a href="controleurFrontal.php?controleur=article&action=afficherFormulaireMiseAJour&login=' . rawurlencode($article->getIdArticle()) . '">modifier<a>/<a href="controleurFrontal.php?controleur=article&action=supprimer&login=' . rawurlencode($article->getIdArticle()) . '">supprimer<a>)</p>';
    } else {
        echo '</p>';
    }
}
echo '<p><a href="controleurFrontal.php?controleur=article&action=afficherFormulaireCreation">Créer un article<a></p>';