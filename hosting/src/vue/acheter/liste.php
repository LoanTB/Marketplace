<?php

use \App\Ecommerce\Modele\Repository\relations\acheterRepository;
use \App\Ecommerce\Lib\ConnexionUtilisateur;

$historique = (new acheterRepository())->recupererHistoriqueAchats(ConnexionUtilisateur::getIdUtilisateurConnecte());

foreach ($historique as $achat){
    echo "<p>Vous avez acheté le ".$achat[0]->getJour()." ".$achat[0]->getQuantite()." articles pour un total de ".$achat[0]->getPrix()."€ (";
    if ($achat[1] == null){
        echo "L'article n'es plus disponible";
    } else {
        echo "Article : ".$achat[1]->getNom();
    }
    echo ")</p>";
}