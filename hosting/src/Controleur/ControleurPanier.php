<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\PanierTemporaire;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use DateTime;

class ControleurPanier extends ControleurGenerique {
    public static function afficherListe(): void {
        self::afficherVueAvecPointControle("vueGenerale.php", [
            "pagetitle" => "Panier",
            "cheminVueBody" => "panier/liste.php"
        ]);
    }

    public static function ajouterAuPanier(): void {
        if (!isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("I");
            return;
        }

        PanierTemporaire::ajouter($_REQUEST["id_article"]);
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("success", "Article ajouté au panier");
        } else {
            $article = (new ArticleRepository())->recupererParUniqueDansRequest();
            if ($article == null){
                MessageFlash::ajouter("warning", "L'article n'existe pas");
                ControleurGenerique::rediriger();
                return;
            }
            if ($article->getIdUtilisateur() == ConnexionUtilisateur::getIdUtilisateurConnecte() and !ConnexionUtilisateur::estAdministrateur()){
                MessageFlash::ajouter("warning", "Vous ne pouvez pas ajouter votre propre article au panier !");
                ControleurGenerique::rediriger();
                return;
            }
            $sqlreturn = (new dansPanierRepository())->ajouter(new dansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $_REQUEST["id_article"],1, (new DateTime())->format('Y-m-d H:i:s'), $raw = false));

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "Article ajouté au panier");
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

        PanierTemporaire::supprimer($_REQUEST["id_article"]);
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("success", "Article supprimé du panier");
            ControleurGenerique::rediriger();
        } else {
            if ((new dansPanierRepository())->recupererParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1) == null) {
                ControleurGenerique::accesNonAutorise("M");
                return;
            }

            $sqlreturn = (new dansPanierRepository())->supprimerParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1);

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "Article supprimé du panier");
            } else {
                MessageFlash::ajouter("warning", "L'article n'a pas pu être supprimé du panier (".$sqlreturn."), veuillez réessayer plus tard.");
            }
            ControleurGenerique::rediriger();
        }

    }

    public static function vider(): void {
        PanierTemporaire::vider();
        if (!ConnexionUtilisateur::estConnecte()) {
            MessageFlash::ajouter("success", "Le panier a bien été vidé");
            ControleurGenerique::rediriger();
        } else {
            $sqlreturn = (new dansPanierRepository())->supprimerParColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0);

            if ($sqlreturn == "") {
                MessageFlash::ajouter("success", "Le panier a bien été vidé");
            } else {
                MessageFlash::ajouter("warning", "Les articles n'ont pas pu être supprimés du panier (".$sqlreturn."), veuillez réessayer plus tard.");
            }
            ControleurGenerique::rediriger();
        }
    }

    public static function estDansPanier($id_article): bool {
        if (ConnexionUtilisateur::estConnecte()) {
            return (new dansPanierRepository)->estDansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $id_article);
        } else {
            return PanierTemporaire::estDansPanier($id_article);
        }
    }

    public static function convertir($id_utilisateur): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("N");
            return;
        }
        $fail = false;
        foreach (PanierTemporaire::lire() as $id_article) {
            $sqlreturn = (new dansPanierRepository())->ajouter(new dansPanier(ConnexionUtilisateur::getIdUtilisateurConnecte(), $id_article,1, (new DateTime())->format('Y-m-d H:i:s'), $raw = false));
            if ($sqlreturn != "" and $sqlreturn != "23000"){
                MessageFlash::ajouter("warning", "Des articles n'ont pas pu être enregistrés dans le panier (".$sqlreturn."), veuillez réessayer plus tard.");
                $fail = true;
            }
        }
        if (!empty(PanierTemporaire::lire()) and !$fail){
            MessageFlash::ajouter("success", "Le panier a été associé au compte");
        }
        PanierTemporaire::vider();
        $dansPaniers = (new dansPanierRepository())->recupererParColonne($id_utilisateur,0);
        foreach ($dansPaniers as $dansPanier){
            PanierTemporaire::ajouter($dansPanier->getIdArticle());
        }
    }
}
