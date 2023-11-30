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

        self::afficherVue("vueGenerale.php", [
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
}