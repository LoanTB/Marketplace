<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\contenir;
use App\Ecommerce\Modele\DataObject\Wishlist;
use App\Ecommerce\Modele\Repository\relations\contenirRepository;
use App\Ecommerce\Modele\Repository\relations\enregistrerRepository;
use App\Ecommerce\Modele\Repository\WishlistRepository;

class ControleurWishlist extends ControleurGenerique {
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php", [
            "pagetitle" => "Wishlists",
            "cheminVueBody" => "wishlist/liste.php",
            "wishlists" => (new enregistrerRepository())->recupererWishlistsUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte())
        ]);
    }

    public static function ajouterWishlist(): void {
        if (!isset($_REQUEST["nom"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $wishlist = new Wishlist(null, $_REQUEST["nom"], $raw = false);

        $sqlreturn = (new WishlistRepository())->ajouterPourUtilisateur($wishlist,ConnexionUtilisateur::getIdUtilisateurConnecte());

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "La liste de souhait a bien été ajouté créer.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "La liste de souhait n'as pas pu être créer (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }

    private static function estEnregistrerDeWishlist(int $id_wishlist): bool {
        $wishlists = (new enregistrerRepository())->recupererWishlistsUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());
        foreach ($wishlists as $wishlist){
            if ($wishlist->getIdWishlist() == $id_wishlist){
                return True;
            }
        }
        return False;
    }

    public static function ajouterArticleDansWishlist(): void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["id_wishlist"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        if (!self::estEnregistrerDeWishlist($_REQUEST["id_wishlist"])){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $contenir = new contenir($_REQUEST["id_article"],$_REQUEST["id_wishlist"]);

        $sqlreturn = (new contenirRepository())->ajouter($contenir);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "L'article à bien été ajouter à la liste des souhaits");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouter à la lite des souhaits (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }

    public static function recupererFavoris(): ?Wishlist {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return null;
        }
        $wishlists = (new enregistrerRepository())->recupererWishlistsUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());
        foreach ($wishlists as $wishlist){
            if ($wishlist->getNom() == "favoris"){
                return $wishlist;
            }
        }
        if ((new WishlistRepository())->ajouterPourUtilisateur(new Wishlist(null, "favoris", $raw = false),ConnexionUtilisateur::getIdUtilisateurConnecte()) != ""){
            return null;
        }
        return self::recupererFavoris();
    }

    public static function afficherFavoris(): void{
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $favoris = self::recupererFavoris();
        if ($favoris == null){
            MessageFlash::ajouter("warning", "Recupération des favoris échouée (WFAVERROR), veuillez réessayer plus tard.");
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php", [
            "pagetitle" => "Favoris",
            "cheminVueBody" => "wishlist/listeArticles.php",
            "wishlist" => $favoris,
            "articles" => (new contenirRepository())->recupererArticlesWishlist($favoris->getIdWishlist())
        ]);
    }

    public static function ajouterArticleAuxFavoris(): void {
        if (!isset($_REQUEST["id_article"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $favoris = self::recupererFavoris()->getIdWishlist();

        if ($favoris == null){
            MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouter aux favoris (WFAVERROR), veuillez réessayer plus tard.");
            return;
        }

        $contenir = new contenir($_REQUEST["id_article"],$favoris);

        $sqlreturn = (new contenirRepository())->ajouter($contenir);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "L'article à bien été ajouter aux favoris");
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouter aux favoris (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }
}