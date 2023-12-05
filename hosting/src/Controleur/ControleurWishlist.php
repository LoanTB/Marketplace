<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\contenir;
use App\Ecommerce\Modele\DataObject\Wishlist;
use App\Ecommerce\Modele\Repository\relations\contenirRepository;
use App\Ecommerce\Modele\Repository\relations\enregistrerRepository;
use App\Ecommerce\Modele\Repository\WishlistRepository;
use DateTime;

class ControleurWishlist extends ControleurGenerique {
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("Y");
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
            ControleurGenerique::accesNonAutorise("AC");
            return;
        }

        $wishlist = new Wishlist(null, $_REQUEST["nom"], $raw = false);

        $sqlreturn = (new WishlistRepository())->ajouterPourUtilisateur($wishlist,ConnexionUtilisateur::getIdUtilisateurConnecte());

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "La liste de souhaits a bien été créée.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "Échec lors de la création de la liste de souhaits (".$sqlreturn."), veuillez réessayer plus tard.");
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
            ControleurGenerique::accesNonAutorise("AA");
            return;
        }

        if (!self::estEnregistrerDeWishlist($_REQUEST["id_wishlist"])){
            ControleurGenerique::accesNonAutorise("AB");
            return;
        }

        $contenir = new contenir($_REQUEST["id_article"],$_REQUEST["id_wishlist"],(new DateTime())->format('Y-m-d H:i:s'));

        $sqlreturn = (new contenirRepository())->ajouter($contenir);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "L'article à bien été ajouter à la liste des souhaits");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être ajouter à la lite des souhaits (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
        }
    }

    public static function supprimerArticleDeWishlist(): void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["id_wishlist"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("AF");
            return;
        }

        if (!self::estEnregistrerDeWishlist($_REQUEST["id_wishlist"])){
            ControleurGenerique::accesNonAutorise("AG");
            return;
        }

        $sqlreturn = (new contenirRepository())->supprimerParDeuxColonne($_REQUEST["id_article"],0,$_REQUEST["id_wishlist"],1);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Article ajouté à la liste des souhaits");
        } else {
            MessageFlash::ajouter("warning", "Impossible d'ajouter l'article à la liste de souhaits (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function supprimerArticleDesFavoris(): void {
        if (!isset($_REQUEST["id_article"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("AE");
            return;
        }

        $favoris = self::recupererFavoris();
        if ($favoris == null){
            MessageFlash::ajouter("warning", "Échec lors de la suppression du favori (WFAVERROR), veuillez réessayer plus tard.");
            return;
        }

        $sqlreturn = (new contenirRepository())->supprimerParDeuxColonne($_REQUEST["id_article"],0,$favoris->getIdWishlist(),1);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Article supprimé des favoris");
        } else {
            MessageFlash::ajouter("warning", "Échec lors de la suppression du favori (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        $favoris = self::recupererFavoris();
        ControleurGenerique::rediriger([
            "wishlist" => $favoris,
            "articles" => (new contenirRepository())->recupererArticlesWishlist($favoris->getIdWishlist())
        ]);
    }

    public static function recupererFavoris(): ?Wishlist {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("AD");
            return null;
        }
        $wishlists = (new enregistrerRepository())->recupererWishlistsUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());
        foreach ($wishlists as $wishlist){
            if ($wishlist->getNom() == "favoris"){
                return $wishlist;
            }
        }
        if ((new WishlistRepository())->ajouterPourUtilisateur(new Wishlist(null, "favoris", $raw = false),ConnexionUtilisateur::getIdUtilisateurConnecte()) != ""){
            MessageFlash::ajouter("warning", "Échec lors de la récupération de vos favoris (WFAVERROR), veuillez réessayer plus tard.");
            return null;
        }
        return self::recupererFavoris();
    }

    public static function estDansFavoris($id_utilisateur,$id_article): bool{
        $wishlists = (new enregistrerRepository())->recupererWishlistsUtilisateur($id_utilisateur);
        foreach ($wishlists as $wishlist){
            if ($wishlist->getNom() == "favoris"){
                $articles = (new contenirRepository())->recupererArticlesWishlist($wishlist->getIdWishlist());
                foreach ($articles as $article) {
                    if ($article->getIdArticle() == $id_article){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function afficherFavoris(): void{
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurUtilisateur::formulaireConnexion();
            return;
        }

        self::afficherVueAvecPointControle("vueGenerale.php", [
            "pagetitle" => "Favoris",
            "cheminVueBody" => "wishlist/listeArticles.php"
        ]);
    }

    public static function ajouterArticleAuxFavoris(): void {
        if (!isset($_REQUEST["id_article"]) or !ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("Z");
            return;
        }

        $favoris = self::recupererFavoris()->getIdWishlist();

        $contenir = new contenir($_REQUEST["id_article"],$favoris,(new DateTime())->format('Y-m-d H:i:s'));

        $sqlreturn = (new contenirRepository())->ajouter($contenir);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Article ajouté aux favoris");
        } else {
            MessageFlash::ajouter("warning", "Échec lors de l'ajout de l'article aux favoris (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }
}