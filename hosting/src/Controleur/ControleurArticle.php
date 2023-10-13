<?php
namespace App\Ecommerce\Controleur;
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\MotDePasse;
use App\Ecommerce\Lib\VerificationEmail;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\Repository\ArticleRepository;

class ControleurArticle extends ControleurGenerique {
    public static function afficherListe() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Boutique",
            "cheminVueBody" => "article/liste.php",
            "articles" => (new ArticleRepository())->recuperer()
        ]);
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "article/erreur.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function afficherDetail() : void {
        if ((new ArticleRepository())->requestContainsUnique()){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        $article = (new ArticleRepository())->recupererParUnique($_REQUEST[(new ArticleRepository())->requestUniqueValue()],(new ArticleRepository())->requestUniqueIndice());
        if ($article == null){
            MessageFlash::ajouter("warning", "Article innexistant");
            self::afficherListe();
        } else {
            self::afficherVue("vueGenerale.php",[
                "pagetitle" => "Détails de l'article",
                "cheminVueBody" => "article/detail.php",
                "article" => $article
            ]);
        }
    }

    public static function afficherFormulaireCreation() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire création articles",
            "cheminVueBody" => "article/formulaireCreation.php"
        ]);
    }

    public static function afficherFormulaireMiseAJour() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire modification articles",
            "cheminVueBody" => "article/formulaireMiseAJour.php"
        ]);
    }

    public static function creerDepuisFormulaire() : void {
        if (!isset($_REQUEST["nom"]) or !isset($_REQUEST["description"]) or !isset($_REQUEST["prix"]) or !isset($_REQUEST["quantite"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }
        $article = new Article(null,$_REQUEST["nom"],$_REQUEST["description"],$_REQUEST["prix"],$_REQUEST["quantite"],ConnexionUtilisateur::getIdUtilisateurConnecte(),null,$raw = false);
        (new ArticleRepository())->ajouter($article);
        MessageFlash::ajouter("success","L'article a bien été mis en ligne.");
        self::afficherListe();
    }

    public static function mettreAJour() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["oldPassword"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["prenom"]) or !isset($_REQUEST["email"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (!ConnexionArticle::estArticle($_REQUEST["login"]) and !ConnexionArticle::estAdministrateur()){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)){
            MessageFlash::ajouter("warning", "Email invalide, veuillez entrer une email valide.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if (explode('@', $_REQUEST["email"])[1] != "yopmail.com"){
            MessageFlash::ajouter("warning", "Seuls les emails 'yopmail.com' sont autorisées pour le moment.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if (ConnexionArticle::estAdministrateur()){
            $estAdmin = isset($_REQUEST["estAdmin"]);
        } else {
            if (isset($_REQUEST["estAdmin"])){
                ControleurGenerique::alerterAccesNonAutorise();
                self::afficherListe();
                return;
            }
            $estAdmin = false;
        }

        if (MotDePasse::verifier($_REQUEST["oldPassword"],(new ArticleRepository())->recupererParClePrimaire($_REQUEST["login"])->getPassword())){
            $article = (new ArticleRepository())->recupererParClePrimaire($_REQUEST["login"]);
            if (isset($_REQUEST["password"])){
                if ($_REQUEST["password"] == $_REQUEST["passwordConfirmation"]){
                    $article->setPassword($_REQUEST["password"]);
                } else {
                    MessageFlash::ajouter("warning", "Les mots de passes sont différents");
                    self::afficherFormulaireMiseAJour();
                    return;
                }
            } else {
                $article->setPassword($_REQUEST["oldPassword"]);
            }
        } else {
            MessageFlash::ajouter("warning", "Mot de passse incorrecte !");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $article->setNom($_REQUEST["nom"]);
        $article->setPrenom($_REQUEST["prenom"]);
        $article->setEstAdmin($estAdmin);

        if ($article->getEmail() == $_REQUEST["email"]){
            $article->setEmail($_REQUEST["email"]);
            $article->setEmailAValider("");
            $article->setNonce("");
            (new ArticleRepository())->mettreAJour($article);
            MessageFlash::ajouter("success", "L'article avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été mise à jour");
        } else {
            $article->setEmail("");
            $article->setEmailAValider($_REQUEST["email"]);
            $article->setNonce(MotDePasse::genererChaineAleatoire());
            if (VerificationEmail::envoiEmailValidation($article)){
                (new ArticleRepository())->mettreAJour($article);
                MessageFlash::ajouter("success", "L'article avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été mise à jour, un mail de validation a été envoyé pour confirmer la nouvelle email. <a href='http://".explode('@', $article->getEmailAValider())[0].".yopmail.com'>Consultez la boite mail</a>");
                self::deconnecter();
                return;
            } else {
                MessageFlash::ajouter("warning", "L'email de confirmation n'as pas pu être envoyée, veuillez réessayer plus tard.");
            }
        }
        self::afficherListe();
    }

    public static function supprimer() : void {
        if (!isset($_REQUEST["login"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (!ConnexionArticle::estArticle($_REQUEST["login"]) and !ConnexionArticle::estAdministrateur()){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        (new ArticleRepository())->supprimerParClePrimaire($_REQUEST["login"]);
        MessageFlash::ajouter("success", "L'article avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été supprimé");
        if (ConnexionArticle::estArticle($_REQUEST["login"])){
            self::deconnecter();
        } else {
            self::afficherListe();
        }
    }

    public static function connecter() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["password"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        $article = (new ArticleRepository())->recupererParClePrimaire($_REQUEST["login"]);
        if ($article == null){
            MessageFlash::ajouter("warning", "L'article n'existe pas");
            self::formulaireConnexion();
        } else {
            if (!VerificationEmail::aValideEmail($article)){
                MessageFlash::ajouter("warning", "Veuillez confirmer votre email pour utiliser votre compte.");
                self::formulaireConnexion();
                return;
            }
            if (MotDePasse::verifier($_REQUEST["password"],$article->getPassword())){
                ConnexionArticle::connecter($_REQUEST["login"]);
                MessageFlash::ajouter("success", "Connexion réussie !");
                self::afficherListe();
            } else {
                MessageFlash::ajouter("warning", "Mot de passe incorrect !");
                self::formulaireConnexion();
            }
        }
    }

    public static function deconnecter() : void {
        ConnexionArticle::deconnecter();
        MessageFlash::ajouter("success", "Déonnexion effectuée");
        self::afficherListe();
    }

    public static function validerEmail() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["nonce"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }
        if (VerificationEmail::traiterEmailValidation($_REQUEST["login"],$_REQUEST["nonce"])){
            ConnexionArticle::deconnecter();
            MessageFlash::ajouter("success", "Email confirmée, vérification de compte validée.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "Email non confirmée, lien de confirmation invalide !");
            self::afficherListe();
        }
    }
}
