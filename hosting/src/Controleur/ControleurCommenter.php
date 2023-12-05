<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\relations\commenter;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\relations\commenterRepository;
use DateTime;

class ControleurCommenter extends ControleurGenerique {

    public static function recupererListeCommentaires(string $id_article): array {
        return (new commenterRepository())->recupererParColonne($id_article,1);
    }

    public static function recupererCommentaireUtilisateur(string $id_article, string $id_utilisateur): ?commenter {
        $commentaires = self::recupererListeCommentaires($id_article);
        foreach ($commentaires as $commentaire){
            if ($commentaire->getIdUtilisateur() == $id_utilisateur){
                return $commentaire;
            }
        }
        return null;
    }

    public static function recupererListeSaufCommentaireUtilisateur(string $id_article, string $id_utilisateur): array {
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

        $article = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);

        if ($article == null){
            MessageFlash::ajouter("warning", "L'article n'existe pas ou plus");
            ControleurGenerique::rediriger();
            return;
        }

        if ($article->getIdUtilisateur() == ConnexionUtilisateur::getIdUtilisateurConnecte()){
            MessageFlash::ajouter("warning", "Vous ne pouvez pas commenter votre propre article");
            ControleurGenerique::rediriger();
            return;
        }

        $commenter = new commenter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$_REQUEST["id_article"],$_REQUEST["titre"],$_REQUEST["texte"],$_REQUEST["note"],(new DateTime())->format('Y-m-d H:i:s'),(new DateTime())->format('Y-m-d H:i:s'),$raw = false);
        $sqlreturn = (new commenterRepository())->ajouter($commenter);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Commentaire mis en ligne");
        } else if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information de mauvaise taille a été entrée, veuillez la raccourcir.");
        } else {
            MessageFlash::ajouter("warning", "Impossible de poster votre commentaire (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function modifierCommentaire(): void {
        if (!ConnexionUtilisateur::estConnecte() or !isset($_REQUEST["id_article"]) or !isset($_REQUEST["titre"]) or !isset($_REQUEST["texte"]) or !isset($_REQUEST["note"])) {
            ControleurGenerique::accesNonAutorise("X");
            return;
        }

        $ancienCommentaire = (new commenterRepository())->recupererParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1);

        if (count($ancienCommentaire) == 1){
            $ancienCommentaire = $ancienCommentaire[0];
        } else {
            MessageFlash::ajouter("warning", "Le commentaire à modifier n'existe pas.");
            ControleurGenerique::rediriger();
            return;
        }

        $commenter = new commenter(ConnexionUtilisateur::getIdUtilisateurConnecte(),$_REQUEST["id_article"],$_REQUEST["titre"],$_REQUEST["texte"],$_REQUEST["note"],(new DateTime())->format('Y-m-d H:i:s'),$ancienCommentaire->getJour(),$raw = false);
        $sqlreturn = (new commenterRepository())->mettreAJourParDeuxPremieresColonne($commenter);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Commentaire modifié");
        } else if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
        } else {
            MessageFlash::ajouter("warning", "Modification du commentaire impossible (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function supprimerCommentaire(): void {
        if (!ConnexionUtilisateur::estConnecte() or !isset($_REQUEST["id_article"])) {
            ControleurGenerique::accesNonAutorise("J");
            return;
        }

        $sqlreturn = (new commenterRepository())->supprimerParDeuxColonne(ConnexionUtilisateur::getIdUtilisateurConnecte(),0,$_REQUEST["id_article"],1);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Commentaire modifié");
        } else if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
        } else {
            MessageFlash::ajouter("warning", "Modification du commentaire impossible (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }
}