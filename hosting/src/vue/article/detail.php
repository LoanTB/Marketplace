<?php
use \App\Ecommerce\Lib\ConnexionUtilisateur;
/* @var $article \App\Ecommerce\Modele\DataObject\Article */
echo '<p> Article '.htmlspecialchars($article->getNom()).' : '.htmlspecialchars($article->getDescription()).' coute '.htmlspecialchars($article->getPrix()).' avec comme identifiant ' . htmlspecialchars($article->getIdArticle());
if (ConnexionUtilisateur::estConnecte()){
    echo ' <A  href="controleurFrontal.php?controleur=panier&action=ajouterAuPanier&id_article=' . htmlspecialchars(rawurlencode($article->getIdArticle())) .'" > Ajouter au panier </A>';
}
echo '</p>';
