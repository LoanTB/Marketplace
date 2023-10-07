<?php
namespace App\Ecommerce\Controleur;
use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\MotDePasse;
use App\Ecommerce\Lib\VerificationEmail;
use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
class ControleurUtilisateur extends ControleurGenerique {
    // Déclaration de type de retour void : la fonction ne retourne pas de valeur
    public static function afficherListe() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/liste.php",
            "utilisateurs" => (new UtilisateurRepository())->recuperer()
        ]);
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "utilisateur/erreur.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function afficherDetail() : void {
        if (!isset($_REQUEST["login"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
        if ($utilisateur == null){
            self::afficherVue("vueGenerale.php",[
                "pagetitle" => "Erreur",
                "cheminVueBody" => "utilisateur/erreur.php"
            ]);
        } else {
            self::afficherVue("vueGenerale.php",[
                "pagetitle" => "Détails de l'utilisateur",
                "cheminVueBody" => "utilisateur/detail.php",
                "utilisateur" => $utilisateur
            ]);
        }
    }

    public static function afficherFormulaireCreation() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire création utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireCreation.php"
        ]);
    }

    public static function afficherFormulaireMiseAJour() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire modification utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireMiseAJour.php"
        ]);
    }

    public static function formulaireConnexion() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire connexion utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php"
        ]);
    }



    public static function creerDepuisFormulaire() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["password"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["prenom"]) or !isset($_REQUEST["email"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (ConnexionUtilisateur::estAdministrateur()){
            $estAdmin = isset($_REQUEST["estAdmin"]);
        } else {
            if (isset($_REQUEST["estAdmin"])){
                ControleurGenerique::alerterAccesNonAutorise();
                self::afficherListe();
                return;
            }
            $estAdmin = false;
        }

        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)){
            MessageFlash::ajouter("warning", "Email invalide, veuillez entrer une email valide.");
            self::afficherFormulaireCreation();
            return;
        }

        if (explode('@', $_REQUEST["email"])[1] != "yopmail.com"){
            MessageFlash::ajouter("warning", "Seuls les emails 'yopmail.com' sont autorisées pour le moment.");
            self::afficherFormulaireCreation();
            return;
        }

        if ($_REQUEST["password"] == $_REQUEST["passwordConfirmation"]){
            $utilisateur = new Utilisateur($_REQUEST["login"],"",$_REQUEST["password"],$_REQUEST["nom"],$_REQUEST["prenom"], $estAdmin, $_REQUEST["email"], MotDePasse::genererChaineAleatoire(), $raw = false);
            if (VerificationEmail::envoiEmailValidation($utilisateur)){
                (new UtilisateurRepository())->ajouter($utilisateur);

                MessageFlash::ajouter("success","L'utilisateur a bien été créé, un mail de validation a été envoyé. <a href='http://".explode('@', $utilisateur->getEmailAValider())[0].".yopmail.com'>Consultez la boite mail</a>");
            } else {
                MessageFlash::ajouter("warning", "L'email de confirmation n'as pas pu être envoyée, veuillez réessayer plus tard.");
            }
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "Les mots de passes sont différents");
            self::afficherFormulaireCreation();
        }
    }

    public static function mettreAJour() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["oldPassword"]) or !isset($_REQUEST["nom"]) or !isset($_REQUEST["prenom"]) or !isset($_REQUEST["email"])){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["login"]) and !ConnexionUtilisateur::estAdministrateur()){
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

        if (ConnexionUtilisateur::estAdministrateur()){
            $estAdmin = isset($_REQUEST["estAdmin"]);
        } else {
            if (isset($_REQUEST["estAdmin"])){
                ControleurGenerique::alerterAccesNonAutorise();
                self::afficherListe();
                return;
            }
            $estAdmin = false;
        }

        if (MotDePasse::verifier($_REQUEST["oldPassword"],(new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"])->getPassword())){
            $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
            if (isset($_REQUEST["password"])){
                if ($_REQUEST["password"] == $_REQUEST["passwordConfirmation"]){
                    $utilisateur->setPassword($_REQUEST["password"]);
                } else {
                    MessageFlash::ajouter("warning", "Les mots de passes sont différents");
                    self::afficherFormulaireMiseAJour();
                    return;
                }
            } else {
                $utilisateur->setPassword($_REQUEST["oldPassword"]);
            }
        } else {
            MessageFlash::ajouter("warning", "Mot de passse incorrecte !");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $utilisateur->setNom($_REQUEST["nom"]);
        $utilisateur->setPrenom($_REQUEST["prenom"]);
        $utilisateur->setEstAdmin($estAdmin);

        if ($utilisateur->getEmail() == $_REQUEST["email"]){
            $utilisateur->setEmail($_REQUEST["email"]);
            $utilisateur->setEmailAValider("");
            $utilisateur->setNonce("");
            (new UtilisateurRepository())->mettreAJour($utilisateur);
            MessageFlash::ajouter("success", "L'utilisateur avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été mise à jour");
        } else {
            $utilisateur->setEmail("");
            $utilisateur->setEmailAValider($_REQUEST["email"]);
            $utilisateur->setNonce(MotDePasse::genererChaineAleatoire());
            if (VerificationEmail::envoiEmailValidation($utilisateur)){
                (new UtilisateurRepository())->mettreAJour($utilisateur);
                MessageFlash::ajouter("success", "L'utilisateur avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été mise à jour, un mail de validation a été envoyé pour confirmer la nouvelle email. <a href='http://".explode('@', $utilisateur->getEmailAValider())[0].".yopmail.com'>Consultez la boite mail</a>");
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

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["login"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::alerterAccesNonAutorise();
            self::afficherListe();
            return;
        }

        (new UtilisateurRepository())->supprimerParClePrimaire($_REQUEST["login"]);
        MessageFlash::ajouter("success", "L'utilisateur avec le login ".htmlspecialchars($_REQUEST["login"])." a bien été supprimé");
        if (ConnexionUtilisateur::estUtilisateur($_REQUEST["login"])){
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

        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($_REQUEST["login"]);
        if ($utilisateur == null){
            MessageFlash::ajouter("warning", "L'utilisateur n'existe pas");
            self::formulaireConnexion();
        } else {
            if (!VerificationEmail::aValideEmail($utilisateur)){
                MessageFlash::ajouter("warning", "Veuillez confirmer votre email pour utiliser votre compte.");
                self::formulaireConnexion();
                return;
            }
            if (MotDePasse::verifier($_REQUEST["password"],$utilisateur->getPassword())){
                ConnexionUtilisateur::connecter($_REQUEST["login"]);
                MessageFlash::ajouter("success", "Connexion réussie !");
                self::afficherListe();
            } else {
                MessageFlash::ajouter("warning", "Mot de passe incorrect !");
                self::formulaireConnexion();
            }
        }
    }

    public static function deconnecter() : void {
        ConnexionUtilisateur::deconnecter();
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
            ConnexionUtilisateur::deconnecter();
            MessageFlash::ajouter("success", "Email confirmée, vérification de compte validée.");
            self::afficherListe();
        } else {
            MessageFlash::ajouter("warning", "Email non confirmée, lien de confirmation invalide !");
            self::afficherListe();
        }
    }
}
