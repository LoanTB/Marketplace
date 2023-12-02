<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\MotDePasse;
use App\Ecommerce\Lib\Redirections;
use App\Ecommerce\Lib\VerificationEmail;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\Repository\ArticleRepository;

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

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "article/erreur.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function afficherDetail() : void {
        if (!((new ArticleRepository())->requestContainsUnique())){
            ControleurGenerique::accesNonAutorise();
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

    public static function afficherFormulaireCreation() : void {
        if (!ConnexionUtilisateur::estConnecte()) {
            ControleurGenerique::accesNonAutorise();
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
            ControleurGenerique::accesNonAutorise();
            return;
        }
        $article = new Article(null,$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],ConnexionUtilisateur::getIdUtilisateurConnecte(),$raw = false);

        $sqlreturn = (new ArticleRepository())->ajouter($article);

        if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information trop longue à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireCreation();
            return;
        } else if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","L'article a bien été mis en ligne.");
        self::afficherListe();
    }

    public static function mettreAJour() : void {
        if (!isset($_REQUEST["id_article"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"]) or !ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $ancienArticle = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);

        if (!ConnexionUtilisateur::estUtilisateur($ancienArticle->getIdUtilisateur())){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $article = new Article($_REQUEST["id_article"],$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],ConnexionUtilisateur::getIdUtilisateurConnecte(),$raw = false);

        $sqlreturn = (new ArticleRepository())->mettreAJour($article);

        if ($sqlreturn == "22001"){
            MessageFlash::ajouter("warning", "Une information trop longue à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireCreation();
            return;
        } else if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "L'article n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            self::afficherListe();
            return;
        }

        MessageFlash::ajouter("success","L'article a bien été mis à jour.");
        self::afficherListe();
    }

    public static function supprimer() : void {
        if (!isset($_REQUEST["id_article"])){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $article = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"],0);
        if (is_null($article)) {
            MessageFlash::ajouter("warning", "L'article demandé est introuvable !");
            self::afficherListe();
        }

        if (!ConnexionUtilisateur::estUtilisateur($article->getIdUtilisateur())){
            ControleurGenerique::accesNonAutorise();
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
