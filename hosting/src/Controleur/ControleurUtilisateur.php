<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\MotDePasse;
use App\Ecommerce\Lib\VerificationEmail;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

class ControleurUtilisateur extends ControleurGenerique {

    public static function afficherListe() : void {
        if (!ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Liste des utilisateurs",
            "cheminVueBody" => "utilisateur/liste.php",
            "utilisateurs" => (new UtilisateurRepository())->recuperer()
        ]);
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "utilisateur/erreur.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function afficherDetail() : void {
        if (!(new UtilisateurRepository())->requestContainsUnique() || !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $utilisateur = (new UtilisateurRepository())->recupererParUniqueDansRequest();
        if ($utilisateur == null){
            MessageFlash::ajouter("warning", "Utilisateur innexistant");
            self::afficherListe();
        } else {
            self::afficherNouvelleVue("vueGenerale.php",[
                "pagetitle" => "Détails de l'utilisateur",
                "cheminVueBody" => "utilisateur/detail.php",
                "utilisateur" => $utilisateur
            ]);
        }
    }

    public static function afficherFormulaireCreation() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire création utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireCreation.php"
        ]);
    }

    public static function afficherFormulaireMiseAJour() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire modification utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireMiseAJour.php"
        ]);
    }

    public static function formulaireConnexion() : void {
        self::afficherVueAvecConservationPointControle("vueGenerale.php",[
            "pagetitle" => "Formulaire connexion utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php"
        ]);
    }



    public static function creerDepuisFormulaire() : void {
        $infos = array();
        $utilisateurRepository = new UtilisateurRepository();
        foreach ($utilisateurRepository->getNotNull() as $key){
            if (!isset($_REQUEST[$key])){
                ControleurGenerique::accesNonAutorise();
                return;
            } else {
                $infos[$key] = $_REQUEST[$key];
            }
        }
        $infos["nonce_email"] = MotDePasse::genererChaineAleatoire(20);

        if (isset($_REQUEST["admin"]) and $_REQUEST["admin"] == "true"){
            if (ConnexionUtilisateur::estAdministrateur()){
                $infos["admin"] = true;
            } else {
                ControleurGenerique::accesNonAutorise();
                return;
            }
        } else {
            $infos["admin"] = false;
        }

        if (isset($_REQUEST["url_image"])){
            $infos["url_image"] = $_REQUEST["url_image"];
        } else {
            $infos["url_image"] = "";
        }

        if (isset($_REQUEST["telephone_number"]) and $_REQUEST["telephone_number"] != "" and isset($_REQUEST["telephone_country"]) and $_REQUEST["telephone_country"] != ""){
            if (preg_match("/^[0-9]{9}$/", $_REQUEST["telephone_number"]) and preg_match("/^[0-9]{1,2}$/", $_REQUEST["telephone_country"])){
                $infos["telephone"] = "+".$_REQUEST["telephone_country"].$_REQUEST["telephone_number"];
            } else {
                MessageFlash::ajouter("warning", "Téléphone invalide, veuillez entrer un numéro de téléphone valide.");
                self::afficherFormulaireCreation();
                return;
            }
        } else {
            $infos["telephone"] = null;
        }
        $infos["nonce_telephone"] = MotDePasse::genererChaineAleatoire(20);

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

        if ($_REQUEST["password"] != $_REQUEST["passwordConfirmation"]){
            MessageFlash::ajouter("warning", "Les mots de passes sont différents");
            self::afficherFormulaireCreation();
            return;
        }

        $infos["id_utilisateur"] = null;
        foreach ($utilisateurRepository->getNomsColonnes() as $key){
            if (!array_key_exists($key,$infos)){
                $infos[$key] = $_REQUEST[$key];
            }
        }
        $utilisateur = $utilisateurRepository->construireDepuisTableau($infos,false);

        $sqlreturn = $utilisateurRepository->ajouter($utilisateur);

        if ($sqlreturn == "23000") {
            MessageFlash::ajouter("warning", "Le login ou l'email ou le numéro de téléphone est déjà utilisé.");
            self::afficherFormulaireCreation();
            return;
        } else if ($sqlreturn == "22001"){ // TODO (Pour tout le monde) : Ajouter d'autres explications exceptions pour que l'utilisateur comprenne ce qu'il a mal fait
            MessageFlash::ajouter("warning", "Une information trop longue à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireCreation();
            return;
        } else if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        if (VerificationEmail::envoiEmailValidation($utilisateur)){
            MessageFlash::ajouter("success", "L'utilisateur a bien été créé. Un mail de validation a été envoyé : <a href='https://yopmail.com' target='_blank'>Consultez la boite mail</a>");
        } else {
            $utilisateurRepository->supprimerParUniqueDansRequest();
            MessageFlash::ajouter("warning", "L'email de confirmation n'as pas pu être envoyé, la création du compte à été annulé, veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function mettreAJour() : void {
        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["login"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $infos = array();
        $utilisateurRepository = new UtilisateurRepository();
        foreach ($utilisateurRepository->getNotNull() as $key){
            if (!isset($_REQUEST[$key])){
                ControleurGenerique::accesNonAutorise();
                return;
            } else {
                $infos[$key] = $_REQUEST[$key];
            }
        }

        $infos["nonce_email"] = MotDePasse::genererChaineAleatoire(20);

        if (isset($_REQUEST["admin"]) and $_REQUEST["admin"] == "true"){
            if (ConnexionUtilisateur::estAdministrateur()){
                $infos["admin"] = true;
            } else {
                ControleurGenerique::accesNonAutorise();
                return;
            }
        } else {
            $infos["admin"] = false;
        }

        if (isset($_REQUEST["url_image"])){
            $infos["url_image"] = $_REQUEST["url_image"];
        } else {
            $infos["url_image"] = "";
        }

        if (isset($_REQUEST["telephone_number"]) and $_REQUEST["telephone_number"] != "" and isset($_REQUEST["telephone_country"]) and $_REQUEST["telephone_country"] != ""){
            if (preg_match("/^[0-9]{9}$/", $_REQUEST["telephone_number"]) and preg_match("/^[0-9]{1,2}$/", $_REQUEST["telephone_country"])){
                $infos["telephone"] = "+".$_REQUEST["telephone_country"].$_REQUEST["telephone_number"];
            } else {
                MessageFlash::ajouter("warning", "Téléphone invalide, veuillez entrer un numéro de téléphone valide.");
                self::afficherFormulaireMiseAJour();
                return;
            }
        } else {
            $infos["telephone"] = null;
        }
        $infos["nonce_telephone"] = MotDePasse::genererChaineAleatoire(20);

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

        if ($_REQUEST["password"] != $_REQUEST["passwordConfirmation"]){
            MessageFlash::ajouter("warning", "Les mots de passes sont différents.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $infos["id_utilisateur"] = null;
        foreach ($utilisateurRepository->getNomsColonnes() as $key){
            if (!array_key_exists($key,$infos)){
                $infos[$key] = $_REQUEST[$key];
            }
        }
        $oldUtilisateur = $utilisateurRepository->recupererParUniqueDansRequest();

        if (!MotDePasse::verifier($infos["password"],$oldUtilisateur->getPassword())){
            MessageFlash::ajouter("warning", "Mot de passe incorrecte.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $newUtilisateur = $utilisateurRepository->construireDepuisTableau($infos,false);
        if ($newUtilisateur->getEmail() == $oldUtilisateur->getEmail()){
            $newUtilisateur->setNonceEmail($oldUtilisateur->getNonceEmail());
        }
        if ($newUtilisateur->getTelephone() == $oldUtilisateur->getTelephone()){
            $newUtilisateur->setNonceTelephone($oldUtilisateur->getNonceTelephone());
        }

        $sqlreturn = $utilisateurRepository->mettreAJour($newUtilisateur);

        if ($sqlreturn == "") {
            if ($newUtilisateur->getEmail() == $oldUtilisateur->getEmail()) {
                MessageFlash::ajouter("success", "L'utilisateur a bien été modifié.");
            }
        } else if ($sqlreturn == "23000") {
            MessageFlash::ajouter("warning", "Le login ou l'email ou le numéro de téléphone est déjà utilisé.");
            self::afficherFormulaireMiseAJour();
            return;
        } else if ($sqlreturn == "22001"){ // TODO (Pour tout le monde) : Ajouter d'autres explications exceptions pour que l'utilisateur comprenne ce qu'il a mal fait
            MessageFlash::ajouter("warning", "Une information trop longue à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireMiseAJour();
            return;
        } else {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        if ($newUtilisateur->getEmail() != $oldUtilisateur->getEmail()) {
            if (VerificationEmail::envoiEmailValidation($newUtilisateur)) {
                MessageFlash::ajouter("success","L'utilisateur a bien été modifié. L'email, un nouveau code de verification vous a été envoyé : <a href='https://yopmail.com'>Consultez la boite mail</a>");
            } else {
                $utilisateurRepository->mettreAJour($oldUtilisateur);
                MessageFlash::ajouter("warning", "L'email de confirmation n'as pas pu être envoyée, veuillez réessayer plus tard.");
            }
        }
        ControleurGenerique::rediriger();
    }

    public static function connecter() : void {
        $utilisateurRepository = new UtilisateurRepository();
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["password"])){
            ControleurGenerique::accesNonAutorise();
            return;
        }

        $utilisateur = $utilisateurRepository->recupererParUniqueDansRequest();
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
                ConnexionUtilisateur::connecter($utilisateur->getIdUtilisateur());
                MessageFlash::ajouter("success", "Connexion réussie !");
                ControleurGenerique::rediriger();
            } else {
                MessageFlash::ajouter("warning", "Mot de passe incorrect !");
                self::formulaireConnexion();
            }
        }
    }

    public static function deconnecter() : void {
        ConnexionUtilisateur::deconnecter();
        MessageFlash::ajouter("success", "Déonnexion effectuée");
        ControleurGenerique::rediriger();
    }

    public static function validerEmail() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["nonce"])){
            ControleurGenerique::accesNonAutorise();
            return;
        }
        if (VerificationEmail::traiterEmailValidation($_REQUEST["nonce"])){
            ConnexionUtilisateur::deconnecter();
            MessageFlash::ajouter("success", "Email confirmée, vérification de compte validée.");
            ControleurGenerique::rediriger();
        } else {
            MessageFlash::ajouter("warning", "Email non confirmée, lien de confirmation invalide !");
            ControleurGenerique::rediriger();
        }
    }
}
