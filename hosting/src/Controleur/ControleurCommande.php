<?php

namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\Repository\CommandeRepository;
use App\Ecommerce\Modele\Repository\relations\dansPanierRepository;
use DateTime;

class ControleurCommande extends ControleurGenerique{
    public static function afficherListe(): void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurArticle::afficherListe();
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php", [
            "pagetitle" => "Historique des commandes",
            "cheminVueBody" => "commande/liste.php"
        ]);
    }

    public static function ajouterPanierAuxAchats(): void {
        if (!isset($_REQUEST["id_utilisateur"])) {
            ControleurGenerique::accesNonAutorise("AJ");
            return;
        }

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"])) {
            ControleurGenerique::accesNonAutorise("AK");
            return;
        }

        $articles = (new dansPanierRepository())->recupererPanierUtilisateur(ConnexionUtilisateur::getIdUtilisateurConnecte());

        if ($articles == null) {
            MessageFlash::ajouter("warning", "Le panier n'a pas pu être chargé ou est vide, veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        foreach ($articles as $article){
            $sqlreturn = (new CommandeRepository())->acheterArticle(ConnexionUtilisateur::getIdUtilisateurConnecte(),$article,1);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "Le panier n'a pas pu être totalement enregistré comme acheté (".$sqlreturn."), consultez votre historique d'achats pour voir quels achats ont bien été confirmés, veuillez réessayer plus tard.");
                ControleurGenerique::rediriger();
                return;
            }
        }

        MessageFlash::ajouter("success", "La commande a bien été enregistrée ! Merci de votre achat !");

        ControleurPanier::vider();
    }
}