<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\PanierTemporaire;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;

class ControleurPanier extends ControleurGenerique {
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            self::afficherNouvelleVue("vueGenerale.php", [
                "pagetitle" => "Panier",
                "cheminVueBody" => "panier/liste.php",
                "articles" => PanierTemporaire::lire()
            ]);
        } else {
            self::afficherNouvelleVue("vueGenerale.php", [
                "pagetitle" => "Panier",
                "cheminVueBody" => "panier/liste.php",
                "articles" => (new dansPanierRepository())->recupererPanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte())
            ]);
        }
    }

    public static function ajouterAuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("I");
            return;
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            PanierTemporaire::supprimer($_REQUEST["id_article"]);
            MessageFlash::ajouter("success", "L'article a bien été ajouté au panier.");
        } else {
            $sqlreturn = (new dansPanierRepository())->ajouter(new dansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $_REQUEST["id_article"], $raw = false));

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "L'article a bien été ajouté au panier.");
            } else {
                MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouté au panier (".$sqlreturn."), veuillez réessayer plus tard.");
            }
        }
        ControleurGenerique::rediriger();
    }

    public static function supprimerDuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("K");
            return;
        }

        if (!ConnexionUtilisateur::estConnecte()) {
            PanierTemporaire::supprimer($_REQUEST["id_article"]);
            MessageFlash::ajouter("success", "L'article a bien été supprimé du panier.");
        } else {
            if ((new dansPanierRepository())->recupererParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1) == null) {
                ControleurGenerique::accesNonAutorise("M");
                return;
            }

            $sqlreturn = (new dansPanierRepository())->supprimerParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1);

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "L'article a bien été supprimé du panier.");
            } else {
                MessageFlash::ajouter("warning", "L'article n'as pas pu être supprimer du panier (".$sqlreturn."), veuillez réessayer plus tard.");
            }
        }
        ControleurGenerique::rediriger();
    }

    public static function vider(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            PanierTemporaire::vider();
            MessageFlash::ajouter("success", "Le panier a bien été vidé.");
        } else {
            $sqlreturn = (new dansPanierRepository())->supprimerParColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0);

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "Le panier a bien été vidé.");
                self::afficherListe();
            } else {
                MessageFlash::ajouter("warning", "Les articles n'ont pas pu être supprimer du panier (".$sqlreturn."), veuillez réessayer plus tard.");
                self::afficherListe();
            }
        }
        ControleurGenerique::rediriger();
    }

    public static function convertir(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("N");
            return;
        }
        foreach (PanierTemporaire::lire() as $id_article) {
            $sqlreturn = (new dansPanierRepository())->ajouter(new dansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $_REQUEST["id_article"], $raw = false));
            if ($sqlreturn != "" and $sqlreturn != "23000"){
                MessageFlash::ajouter("warning", "Des articles n'ont pas pu être enregistrer au panier du compte (".$sqlreturn."), veuillez réessayer plus tard.");
            }
        }
        if (MessageFlash::estVide()){
            MessageFlash::ajouter("success", "Le panier a bien été enregistré.");
        }
        PanierTemporaire::vider();
    }
}
