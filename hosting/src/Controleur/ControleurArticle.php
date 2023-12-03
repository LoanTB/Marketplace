<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\DataObject\Image;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Lib\ImgurUploader;
use App\Ecommerce\Modele\Repository\ImageRepository;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use DateTime;
use mysql_xdevapi\Result;
use const http\Client\Curl\Versions\ARES;

class ControleurArticle extends ControleurGenerique {
    public static function afficherListe() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Boutique",
            "cheminVueBody" => "article/liste.php",
            "articles" => (new ArticleRepository())->recuperer()
        ]);
    }

    public static function afficherListeRecherche() : void {
        if (!isset($_REQUEST["recherche"])){
            self::afficherListe();
            return;
        }
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Boutique",
            "cheminVueBody" => "article/liste.php",
            "articles" => (new ArticleRepository())->recupererRecherche($_REQUEST["recherche"])
        ]);
    }

    public static function afficherDetail() : void {
        if (!((new ArticleRepository())->requestContainsUnique())){
            ControleurGenerique::accesNonAutorise("A");
            return;
        }

        $article = (new ArticleRepository())->recupererParUniqueDansRequest();
        if ($article == null){
            MessageFlash::ajouter("warning", "L'article demandé est introuvable !");
            self::afficherListe();
        } else {
            self::afficherVueAvecPointControle("vueGenerale.php",[
                "pagetitle" => "Détails de l'article",
                "cheminVueBody" => "article/detail.php",
                "article" => $article
            ]);
        }
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "article/erreur.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function afficherFormulaireCreation() : void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise("B");
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire création articles",
            "cheminVueBody" => "article/formulaireCreation.php"
        ]);
    }

    public static function afficherFormulaireMiseAJour() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire modification articles",
            "cheminVueBody" => "article/formulaireMiseAJour.php"
        ]);
    }

    public static function creerDepuisFormulaire() : void {
        if (!isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"]) or !ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("C");
            return;
        }

        if ($_REQUEST["prix"] <= 0){
            MessageFlash::ajouter("warning", "Le prix ne peut pas être plus petit ou égale à 0.");
            self::afficherFormulaireCreation();
            return;
        }

        if ($_REQUEST["quantite"] <= 0){
            MessageFlash::ajouter("warning", "La quantité ne peut pas être plus petite ou égale à 0.");
            self::afficherFormulaireCreation();
            return;
        }

        $article = new Article(null,$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],(new DateTime())->format('Y-m-d H:i:s'),(new DateTime())->format('Y-m-d H:i:s'),ConnexionUtilisateur::getIdUtilisateurConnecte(),$raw = false);

        $uploader = new ImgurUploader();
        $imgurLinks = array();

        for ($i = 1; $i <= 3; $i++) {
            $inputName = 'image' . $i;
            if ($_FILES[$inputName] && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                $fileToUpload = array('image' => $_FILES[$inputName]);
                $imgurLink = $uploader->uploadImage($fileToUpload);
                if ($imgurLink) {
                    $imgurLinks[] = $imgurLink;
                } else {
                    MessageFlash::ajouter("warning", "Impossible d'héberger les images, veuillez réessayer ultérieurement");
                    self::afficherFormulaireCreation();
                    return;
                }
            }
        }
        $i = 0;
        $illustrations = [];
        foreach ($imgurLinks as $link) {
            $image = new Image($link);
            $sqlreturn = (new ImageRepository())->ajouter($image);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "L'image n'a pas pu être sauvegardé dans la table image, veuillez réessayer plus tard.");
                self::afficherListe();
                return;
            }
            $illustrations[] = new illustrer(null, $link, $i);
            $i ++;
        }

        $sqlreturn = (new ArticleRepository())->ajouterArticleAvecIllustrations($article, $illustrations);
        if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","L'article a bien été mis en ligne.");
        self::afficherListe();
    }

    public static function mettreAJour() : void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"]) or !ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("D");
            return;
        }

        $ancienArticle = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);

        if (!ConnexionUtilisateur::estUtilisateur($ancienArticle->getIdUtilisateur())){
            ControleurGenerique::accesNonAutorise("E");
            return;
        }

        if ($_REQUEST["prix"] <= 0){
            MessageFlash::ajouter("warning", "Le prix ne peut pas être plus petit ou égale à 0.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if ($_REQUEST["quantite"] <= 0){
            MessageFlash::ajouter("warning", "La quantité ne peut pas être plus petite ou égale à 0.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $article = new Article($_REQUEST["id_article"],$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],(new DateTime())->format('Y-m-d H:i:s'),(new DateTime())->format('Y-m-d H:i:s'),ConnexionUtilisateur::getIdUtilisateurConnecte(),$raw = false);

        $uploader = new ImgurUploader();
        $imgurLinks = array();

        for ($i = 1; $i <= 3; $i++) {
            $inputName = 'image' . $i;
            if ($_FILES[$inputName] && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                $fileToUpload = array('image' => $_FILES[$inputName]);
                $imgurLink = $uploader->uploadImage($fileToUpload);
                if ($imgurLink) {
                    $imgurLinks[] = $imgurLink;
                } else {
                    MessageFlash::ajouter("warning", "Impossible d'héberger les images, veuillez réessayer ultérieurement");
                    self::afficherFormulaireCreation();
                    return;
                }
            }
        }

        $i = 0;
        $newIllustrations = [];
        foreach ($imgurLinks as $link) {
            $image = new Image($link);
            $sqlreturn = (new ImageRepository())->ajouter($image);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "L'image n'a pas pu être sauvegardé dans la table image, veuillez réessayer plus tard.");
                self::afficherListe();
                return;
            }
            $newIllustrations[] = new illustrer($_REQUEST["id_article"], $link, $i);
            $i ++;
        }

        $oldIllustrations = (new illustrerRepository())->recupererParColonne($_REQUEST["id_article"], 0);

        foreach ($newIllustrations as $newIllustration) {
            $remplacer = false;
            foreach ($oldIllustrations as $oldIllustration) {
                if ($oldIllustration->getOrdre() == $newIllustration->getOrdre()) {
                    $sqlreturn = (new illustrerRepository())->supprimer($oldIllustration);
                    if ($sqlreturn != "") {
                        MessageFlash::ajouter("warning", "L'article n'as pas pu être mis à jour (s".$sqlreturn."), veuillez réessayer plus tard.");
                        self::afficherListe();
                        return;
                    }
                    $sqlreturn = (new illustrerRepository())->ajouter($newIllustration);
                    if ($sqlreturn != "") {
                        MessageFlash::ajouter("warning", "L'article n'as pas pu être mis à jour (a".$sqlreturn."), veuillez réessayer plus tard.");
                        self::afficherListe();
                        return;
                    }
                    $remplacer = true;
                }
            }
            if (!$remplacer) {
                $sqlreturn = (new illustrerRepository())->ajouter($newIllustration);
                if ($sqlreturn != "") {
                    MessageFlash::ajouter("warning", "L'article n'as pas pu être mis à jour (a".$sqlreturn."), veuillez réessayer plus tard.");
                    self::afficherListe();
                    return;
                }
            }
        }

        $sqlreturn = (new ArticleRepository())->mettreAJour($article);

        if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être mis à jour (m".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","L'article a bien été mis à jour.");
        self::afficherListe();
    }

    public static function supprimer() : void {
        if (!isset($_REQUEST["id_article"])){
            ControleurGenerique::accesNonAutorise("F");
            return;
        }

        $article = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);
        if (is_null($article)) {
            MessageFlash::ajouter("warning", "L'article demandé est introuvable !");
            self::afficherListe();
        }

        if (!ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())){
            ControleurGenerique::accesNonAutorise("G");
            return;
        }

        $sqlreturn = (new ArticleRepository())->supprimerParUnique($_REQUEST["id_article"],0);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success","L'article a bien été supprimé.");
        } else {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être supprimé (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::redirigerVersMain();
    }
}
