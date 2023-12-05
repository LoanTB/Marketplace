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
            "pagetitle" => "Liste des articles",
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
            "pagetitle" => "Résultats de la recherche",
            "cheminVueBody" => "article/resultatsRecherche.php",
            "articles" => (new ArticleRepository())->recupererRecherche($_REQUEST["recherche"]),
            "requete" => $_REQUEST["recherche"]
        ]);
    }

    public static function afficherDetail() : void {
        if (!((new ArticleRepository())->requestContainsUnique())){
            ControleurGenerique::accesNonAutorise("A");
            return;
        }

        self::afficherVueAvecPointControle("vueGenerale.php",[
            "pagetitle" => "Détails de l'article",
            "cheminVueBody" => "article/detail.php",
            "id_article" => (new ArticleRepository())->requestUniqueValue()
        ]);
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
            "pagetitle" => "Publier un article",
            "cheminVueBody" => "article/formulaireCreation.php"
        ]);
    }

    public static function afficherFormulaireMiseAJour() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Modifier un article",
            "cheminVueBody" => "article/formulaireMiseAJour.php"
        ]);
    }


    public static function supprimerImage() : void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["image"])){
            ControleurGenerique::accesNonAutorise("ZH");
            return;
        }

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("ZI");
            return;
        }

        if ($_REQUEST["image"] === "0") {
            MessageFlash::ajouter("warning", "Impossible de supprimer la première image. Merci de plutôt la modifier");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $article = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);
        if (is_null($article)) {
            MessageFlash::ajouter("warning", "L'article demandé est introuvable !");
            self::afficherListe();
        }

        if (!ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur()) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("XG");
            return;
        }

        $oldImage = (new illustrerRepository())->recupererParDeuxColonne($_REQUEST["id_article"], 0,$_REQUEST["image"],2);
        if (count($oldImage) == 1) {
            $sqlreturn = (new illustrerRepository())->supprimer($oldImage[0]);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "Suppression de l'image impossible (" . $sqlreturn . "), veuillez réessayer plus tard.");
            }
        } else {
            MessageFlash::ajouter("warning", "Impossible de récupérer l'image, veuillez réessayer plus tard.");
        }
        self::afficherFormulaireMiseAJour();
    }






    public static function creerDepuisFormulaire() : void {
        if (!isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"]) or !ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("C");
            return;
        }

        if ($_REQUEST["prix"] <= 0){
            MessageFlash::ajouter("warning", "Le prix doit être supérieur à 0€");
            self::afficherFormulaireCreation();
            return;
        }

        if ($_REQUEST["quantite"] <= 0){
            MessageFlash::ajouter("warning", "Veuillez entrer une quantité supérieure à 0");
            self::afficherFormulaireCreation();
            return;
        }

        $article = new Article(null,$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],(new DateTime())->format('Y-m-d H:i:s'),(new DateTime())->format('Y-m-d H:i:s'),ConnexionUtilisateur::getIdUtilisateurConnecte(),$raw = false);

        $uploader = new ImgurUploader();
        $imgurLinks = array();

        for ($i = 0; $i < 3; $i++) {
            $inputName = 'image' . $i;
            if ($_FILES[$inputName] && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                $fileToUpload = array('image' => $_FILES[$inputName]);
                $imgurLink = $uploader->uploadImage($fileToUpload);
                if ($imgurLink) {
                    $imgurLinks[] = $imgurLink;
                } else {
                    MessageFlash::ajouter("warning", "Erreur lors de l'importation des images, veuillez réessayer ultérieurement");
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
                MessageFlash::ajouter("warning", "Une erreur est survenue lors de l'enregistrement des images, veuillez réessayer plus tard.");
                self::afficherListe();
                return;
            }
            $illustrations[] = new illustrer(null, $link, $i);
            $i ++;
        }

        $sqlreturn = (new ArticleRepository())->ajouterArticleAvecIllustrations($article, $illustrations);
        if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "Erreur lors de la création de l'article (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","Article mis en ligne");
        self::afficherListe();
    }

    public static function mettreAJour() : void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"]) or !ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("D");
            return;
        }

        $ancienArticle = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);

        if (!ConnexionUtilisateur::estUtilisateur($ancienArticle->getIdUtilisateur()) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("E");
            return;
        }

        if ($_REQUEST["prix"] <= 0){
            MessageFlash::ajouter("warning", "Le prix doit être supérieur à 0€");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if ($_REQUEST["quantite"] <= 0){
            MessageFlash::ajouter("warning", "Veuillez entrer une quantité supérieure à 0");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $article = new Article($ancienArticle->getIdArticle(),$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],(new DateTime())->format('Y-m-d H:i:s'),$ancienArticle->getJour(),$ancienArticle->getIdUtilisateur(),$raw = false);

        $uploader = new ImgurUploader();

        $oldIllustrations = (new illustrerRepository())->recupererParColonne($_REQUEST["id_article"], 0);

        $urlsTemp = array();

        for ($i = 0; $i < 3; $i++) {
            $inputName = 'image' . $i;
            if ($_FILES[$inputName] && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
                $fileToUpload = array('image' => $_FILES[$inputName]);
                $imgurLink = $uploader->uploadImage($fileToUpload);
                if ($imgurLink) {
                    $image = new Image($imgurLink);
                    $sqlreturn = (new ImageRepository())->ajouter($image);
                    if ($sqlreturn != "") {
                        MessageFlash::ajouter("warning", "Une erreur est survenue lors de l'enregistrement des images, veuillez réessayer plus tard.");
                        self::afficherListe();
                        return;
                    }
                    $urlsTemp[$i] = $imgurLink;
                } else {
                    MessageFlash::ajouter("warning", "Erreur lors de l'importation des images, veuillez réessayer ultérieurement");
                    self::afficherFormulaireCreation();
                    return;
                }
            }
        }

        foreach ($urlsTemp as $cle => $valeur) {
            for ($x = 0; $x < count($oldIllustrations); $x++) {
                if ($oldIllustrations[$x] && $oldIllustrations[$x]->getOrdre() === $cle) {
                    $sqlreturn = (new illustrerRepository())->supprimer($oldIllustrations[$x]);
                    if ($sqlreturn != "") {
                        MessageFlash::ajouter("warning", "Mise à jour de l'article impossible (s".$sqlreturn."), veuillez réessayer plus tard.");
                        self::afficherListe();
                        return;
                    }
                }
            }
            $cleTemp = $cle;
            if ($cle > count($oldIllustrations)) {
                $cleTemp = count($oldIllustrations);
            }
            $newIllustration = new illustrer($_REQUEST["id_article"], $valeur, $cleTemp);
            $sqlreturn = (new illustrerRepository())->ajouter($newIllustration);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "Mise à jour de l'article impossible (a".$sqlreturn."), veuillez réessayer plus tard.");
                self::afficherListe();
                return;
            }
        }

        $sqlreturn = (new ArticleRepository())->mettreAJour($article);

        if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "Mise à jour de l'article impossible (m".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","Article mis à jour avec succès");
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

        if (!ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur()) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("G");
            return;
        }

        $sqlreturn = (new ArticleRepository())->supprimerParUnique($article->getIdArticle(),0);

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success","L'article a bien été supprimé");
        } else {
            MessageFlash::ajouter("warning", "Suppression de l'article impossible (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::redirigerVersMain();
    }
}
