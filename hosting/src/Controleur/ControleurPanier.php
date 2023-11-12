<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;

class ControleurPanier extends ControleurGenerique {
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::alerterAccesNonAutorise();
            ControleurArticle::afficherListe();
            return;
        }

        self::afficherVue("vueGenerale.php", [
            "pagetitle" => "Panier",
            "cheminVueBody" => "panier/liste.php",
            "articles" => (new dansPanierRepository())->recupererArticlesDePanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte())
        ]);
    }

    public static function ajouterAuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        $dansPanier = new dansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $_REQUEST["id_article"], $raw = false);
        $sqlreturn = (new dansPanierRepository())->ajouter($dansPanier);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "L'article a bien été ajouté au panier.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouté au panier (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }
}