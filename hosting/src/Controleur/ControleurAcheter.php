<?php

namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\acheter;
use App\Ecommerce\Modele\Repository\relations\acheterRepository;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use DateTime;

class ControleurAcheter extends ControleurGenerique{
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurArticle::afficherListe();
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php", [
            "pagetitle" => "Achats",
            "cheminVueBody" => "acheter/liste.php"
        ]);
    }

    public static function ajouterPanierAuxAchats(): void {
        if (!isset($_REQUEST["id_utilisateur"])) {
            ControleurGenerique::accesNonAutorise("AJ");
            return;
        }

        if (ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"])) {
            ControleurGenerique::accesNonAutorise("AK");
            return;
        }

        $articles = (new dansPanierRepository())->recupererPanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());

        if ($articles == null) {
            MessageFlash::ajouter("warning", "Le panier n'as pas pu être chargé ou est vide, veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        foreach ($articles as $article){
            $sqlreturn = (new acheterRepository())->ajouter(new acheter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$article->getIdArticle(),1,1*$article->getPrix(),(new DateTime())->format('Y-m-d H:i:s')));
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "Le panier n'as pas pu être enregistré comme acheté (".$sqlreturn."), veuillez réessayer plus tard.");
                ControleurGenerique::rediriger();
                return;
            }
        }

        MessageFlash::ajouter("success", "Votre panier a bien été enregistré comme acheté");

        ControleurGenerique::rediriger();
    }
}