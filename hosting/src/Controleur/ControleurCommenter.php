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

    public static function recupererMonCommentaires(string $id_article, string $id_utilisateur): ?Commenter {
        $commentaires = self::recupererListeCommentaires($id_article);
        foreach ($commentaires as $commentaire){
            if ($commentaire->getIdUtilisateur() == $id_utilisateur){
                return $commentaire;
            }
        }
        return null;
    }

    public static function recupererTousSaufMonCommentaires(string $id_article, string $id_utilisateur): array {
        $commentaires = self::recupererListeCommentaires($id_article);
        $selection = [];
        foreach ($commentaires as $commentaire){
            if ($commentaire->getIdUtilisateur() != $id_utilisateur){
                $selection[] = $commentaire;
            }
        }
        return $selection;
    }

    public static function ajouterCommentaire(): void {
        if (!ConnexionUtilisateur::estConnecte() or !isset($_REQUEST["id_article"]) or !isset($_REQUEST["titre"]) or !isset($_REQUEST["texte"]) or !isset($_REQUEST["note"])) {
            ControleurGenerique::accesNonAutorise("H");
            return;
        }

        $commenter = new Commenter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$_REQUEST["id_article"],$_REQUEST["titre"],$_REQUEST["texte"],$_REQUEST["note"],$raw = false);
        $sqlreturn = (new CommenterRepository())->ajouter($commenter);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Votre commentaire a bien été publié.");
        } else if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
        } else {
            MessageFlash::ajouter("warning", "Votre commentaire n'as pas pu être publié (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function modifierCommentaire(): void {
        if (!ConnexionUtilisateur::estConnecte() or !isset($_REQUEST["id_article"]) or !isset($_REQUEST["titre"]) or !isset($_REQUEST["texte"]) or !isset($_REQUEST["note"])) {
            ControleurGenerique::accesNonAutorise("X");
            return;
        }

        $commenter = new Commenter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$_REQUEST["id_article"],$_REQUEST["titre"],$_REQUEST["texte"],$_REQUEST["note"],$raw = false);
        $sqlreturn = (new CommenterRepository())->mettreAJour($commenter);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Votre commentaire a bien été modifié.");
        } else if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
        } else {
            MessageFlash::ajouter("warning", "Votre commentaire n'as pas pu être modifié (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }
}