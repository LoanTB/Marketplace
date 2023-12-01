<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\Commenter;
use App\Ecommerce\Modele\Repository\CommenterRepository;

class ControleurCommenter extends ControleurGenerique {
    public static function recupererListeCommentaires(string $id_article): array {
        return (new CommenterRepository())->recupererParColonne($id_article,1);
    }

    public static function ajouterCommentaire(): void {
        if (!ConnexionUtilisateur::estConnecte() or !isset($_REQUEST["id_article"]) or !isset($_REQUEST["titre"]) or !isset($_REQUEST["texte"]) or !isset($_REQUEST["note"])) {
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $commenter = new Commenter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$_REQUEST["id_article"],$_REQUEST["titre"],$_REQUEST["texte"],$_REQUEST["note"],$raw = false);
        $sqlreturn = (new CommenterRepository())->ajouter($commenter);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Le commentaire a bien été posté.");
        } else {
            MessageFlash::ajouter("warning", "Le commentaire n'a pas pu être posté (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }
}