<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Controleur\ControleurUtilisateur;
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;

class ControleurPanier extends ControleurGenerique {
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurUtilisateur::formulaireConnexion();
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php", [
            "pagetitle" => "Panier",
            "cheminVueBody" => "panier/liste.php",
            "articles" => (new dansPanierRepository())->recupererPanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte())
        ]);
    }

    public static function ajouterAuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("I");
            return;
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("J");
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

    public static function supprimerDuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("K");
            return;
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("L");
            return;
        }

        if ((new dansPanierRepository())->recupererParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1) == null) {
            ControleurGenerique::accesNonAutorise("M");
            return;
        }

        $sqlreturn = (new dansPanierRepository())->supprimerParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "L'article a bien été supprimé du panier.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être supprimer du panier (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }

    public static function vider(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("N");
            return;
        }

        $sqlreturn = (new dansPanierRepository())->supprimerParColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Le panier a bien été vidé.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "Les articles n'ont pas pu être supprimer du panier (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }
}
