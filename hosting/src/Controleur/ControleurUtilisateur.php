<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\MotDePasse;
use App\Ecommerce\Lib\PanierTemporaire;
use App\Ecommerce\Lib\Redirections;
use App\Ecommerce\Lib\VerificationEmail;
use App\Ecommerce\Lib\ImgurUploader;
use App\Ecommerce\Modele\DataObject\Image;
use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\ImageRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
use DateTime;

class ControleurUtilisateur extends ControleurGenerique {

    public static function afficherListe() : void {
        if (!ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("P");
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
            ControleurGenerique::accesNonAutorise("O");
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
        if (!ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("BA");
            return;
        }
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire modification utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireMiseAJour.php",
            "utilisateur" => (new UtilisateurRepository())->recupererParUnique(ConnexionUtilisateur::getIdUtilisateurConnecte(),0)
        ]);
    }

    public static function suppressionConfirmation() : void {
        if (!ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("BA");
            return;
        }
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Confirmer la suppression du compte ?",
            "cheminVueBody" => "utilisateur/suppressionConfirmation.php"
        ]);
    }

    public static function formulaireConnexion() : void {
        self::afficherVueAvecConservationPointControle("vueGenerale.php",[
            "pagetitle" => "Formulaire connexion utilisateurs",
            "cheminVueBody" => "utilisateur/formulaireConnexion.php"
        ]);
    }



    public static function supprimerPfp() : void {
        if (!isset($_REQUEST["id_utilisateur"])){
            ControleurGenerique::accesNonAutorise("AH");
            return;
        }

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("AI");
            return;
        }

        $utilisateur = (new UtilisateurRepository)->recupererParUniqueDansRequest();
        $utilisateur->setUrlImage("");
        $sqlreturn = (new UtilisateurRepository())->mettreAJour($utilisateur);
        if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "L'image n'a pas pu être supprimé, veuillez réessayer plus tard.");
            self::afficherFormulaireMiseAJour();
            return;
        }
        self::afficherFormulaireMiseAJour();
    }

    public static function supprimer() : void {
        if (!isset($_REQUEST["id_utilisateur"]) or !isset($_REQUEST["password"])){
            ControleurGenerique::accesNonAutorise("AH");
            return;
        }

        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("AI");
            return;
        }

        $ancienUtilisateur = (new UtilisateurRepository())->recupererParUniqueDansRequest();

        if ($ancienUtilisateur == null){
            MessageFlash::ajouter("warning", "Le compte n'existe pas.");
            ControleurGenerique::rediriger();
            return;
        }

        if (!MotDePasse::verifier($_REQUEST["password"],$ancienUtilisateur->getPassword())){
            MessageFlash::ajouter("warning", "Mot de passe incorrect.");
            ControleurUtilisateur::suppressionConfirmation();
            return;
        }

        $sqlreturn = (new UtilisateurRepository())->supprimerParUniqueDansRequest();

        ConnexionUtilisateur::deconnecter();
        PanierTemporaire::vider();

        if ($sqlreturn == "") {
            MessageFlash::ajouter("success", "Votre compte à bien été supprimé. Merci de votre confiance et à bientôt !");
        } else {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être supprimé (".$sqlreturn."), veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function creerDepuisFormulaire() : void {
        if (ConnexionUtilisateur::estConnecte() and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("L");
            return;
        }

        $infos = array();
        $utilisateurRepository = new UtilisateurRepository();
        foreach ($utilisateurRepository->getNotNull() as $key){
            if (!isset($_REQUEST[$key])){
                ControleurGenerique::accesNonAutorise("R");
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
                ControleurGenerique::accesNonAutorise("S");
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
            if (strlen($_REQUEST["telephone_country"]) == 1) {
                $_REQUEST["telephone_country"] = "0" . $_REQUEST["telephone_country"];
            }
            if (preg_match("/^[0-9]{9}$/", $_REQUEST["telephone_number"]) && preg_match("/^[0-9]{2}$/", $_REQUEST["telephone_country"])) {
                $infos["telephone"] = "+" . $_REQUEST["telephone_country"] . $_REQUEST["telephone_number"];
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

        if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $_REQUEST["email"]) and explode('@', $_REQUEST["email"])[1] != "yopmail.com"){
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
        $infos["jour"] = (new DateTime())->format('Y-m-d H:i:s');
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
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireCreation();
            return;
        } else if ($sqlreturn != "") {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        if (VerificationEmail::envoiEmailValidation($utilisateur)){
            MessageFlash::ajouter("success", "L'utilisateur a bien été créé. Un mail de validation a été envoyé :", "<a href='https://yopmail.com' target='_blank'>Consultez la boite mail</a>");
        } else {
            $utilisateurRepository->supprimerParUniqueDansRequest();
            MessageFlash::ajouter("warning", "L'email de confirmation n'a pas pu être envoyé, la création du compte à été annulé, veuillez réessayer plus tard.");
        }
        ControleurGenerique::rediriger();
    }

    public static function mettreAJour() : void {
        if (!ConnexionUtilisateur::estUtilisateur($_REQUEST["id_utilisateur"]) and !ConnexionUtilisateur::estAdministrateur()){
            ControleurGenerique::accesNonAutorise("T");
            return;
        }

        $ancienUtilisateur = (new UtilisateurRepository())->recupererParUniqueDansRequest();

        $infos = array();
        $utilisateurRepository = new UtilisateurRepository();
        foreach ($utilisateurRepository->getNotNull() as $key){
            if (!isset($_REQUEST[$key])){
                ControleurGenerique::accesNonAutorise("U");
                return;
            } else {
                $infos[$key] = $_REQUEST[$key];
            }
        }

        if ($infos["email"] != $ancienUtilisateur->getEmail()){
            $infos["nonce_email"] = MotDePasse::genererChaineAleatoire(20);
        } else {
            $infos["nonce_email"] = $ancienUtilisateur->getNonceEmail();
        }

        $uploader = new ImgurUploader();
        if ($_FILES['image'] && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileToUpload = array('image' => $_FILES['image']);
            $imgurLink = $uploader->uploadImage($fileToUpload);
            if (!$imgurLink) {
                MessageFlash::ajouter("warning", "Impossible d'héberger les images, veuillez réessayer ultérieurement");
                self::afficherFormulaireCreation();
                return;
            }
            $image = new Image($imgurLink);
            $sqlreturn = (new ImageRepository())->ajouter($image);
            if ($sqlreturn != "") {
                MessageFlash::ajouter("warning", "L'image n'a pas pu être sauvegardé dans la table image, veuillez réessayer plus tard.");
                self::afficherListe();
                return;
            }
            $infos["url_image"] = $imgurLink;
        } else {
            $infos["url_image"] = "";
        }

        if (isset($_REQUEST["admin"]) and $_REQUEST["admin"] == "true"){
            if (ConnexionUtilisateur::estAdministrateur()){
                $infos["admin"] = true;
            } else {
                ControleurGenerique::accesNonAutorise("V");
                return;
            }
        } else {
            $infos["admin"] = false;
        }

        if (isset($_REQUEST["telephone_number"]) and $_REQUEST["telephone_number"] != "" and isset($_REQUEST["telephone_country"]) and $_REQUEST["telephone_country"] != ""){
            if (strlen($_REQUEST["telephone_country"]) == 1) {
                $_REQUEST["telephone_country"] = "0" . $_REQUEST["telephone_country"];
            }
            if (preg_match("/^[0-9]{9}$/", $_REQUEST["telephone_number"]) && preg_match("/^[0-9]{2}$/", $_REQUEST["telephone_country"])) {
                $infos["telephone"] = "+" . $_REQUEST["telephone_country"] . $_REQUEST["telephone_number"];
            } else {
                MessageFlash::ajouter("warning", "Téléphone invalide, veuillez entrer un numéro de téléphone valide.");
                self::afficherFormulaireMiseAJour();
                return;
            }
            $infos["nonce_telephone"] = MotDePasse::genererChaineAleatoire(20);
        } else {
            $infos["telephone"] = $ancienUtilisateur->getTelephone();
            $infos["nonce_telephone"] = $ancienUtilisateur->getNonceTelephone();
        }

        if (!filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL)){
            MessageFlash::ajouter("warning", "Email invalide, veuillez entrer une email valide.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $_REQUEST["email"]) and explode('@', $_REQUEST["email"])[1] != "yopmail.com"){
            MessageFlash::ajouter("warning", "Seuls les emails 'yopmail.com' sont autorisées pour le moment.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if ($_REQUEST["newPassword"] != $_REQUEST["passwordConfirmation"]){
            MessageFlash::ajouter("warning", "Les mots de passes sont différents.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        if (!MotDePasse::verifier($infos["password"],$ancienUtilisateur->getPassword())){
            MessageFlash::ajouter("warning", "Mot de passe incorrecte.");
            self::afficherFormulaireMiseAJour();
            return;
        }

        $infos["id_utilisateur"] = $ancienUtilisateur->getIdUtilisateur();
        $infos["jour"] = $ancienUtilisateur->getJour();
        foreach ($utilisateurRepository->getNomsColonnes() as $key){
            if (!array_key_exists($key,$infos)){
                $infos[$key] = $_REQUEST[$key];
            }
        }

        $newUtilisateur = $utilisateurRepository->construireDepuisTableau($infos,false);

        $sqlreturn = $utilisateurRepository->mettreAJour($newUtilisateur);

        if ($sqlreturn == "") {
            if ($newUtilisateur->getEmail() == $ancienUtilisateur->getEmail()) {
                MessageFlash::ajouter("success", "L'utilisateur a bien été modifié.");
            }
        } else if ($sqlreturn == "23000") {
            MessageFlash::ajouter("warning", "Le login ou l'email ou le numéro de téléphone est déjà utilisé.");
            self::afficherFormulaireMiseAJour();
            return;
        } else if ($sqlreturn == "22001"){ // TODO (Pour tout le monde) : Ajouter d'autres explications exceptions pour que l'utilisateur comprenne ce qu'il a mal fait
            MessageFlash::ajouter("warning", "Une information de mauvaise taille à été entrée, veuillez la raccourcir.");
            self::afficherFormulaireMiseAJour();
            return;
        } else {
            MessageFlash::ajouter("warning", "Le compte n'as pas pu être créé (".$sqlreturn."), veuillez réessayer plus tard.");
            ControleurGenerique::rediriger();
            return;
        }

        if ($newUtilisateur->getEmail() != $ancienUtilisateur->getEmail()) {
            if (VerificationEmail::envoiEmailValidation($newUtilisateur)) {
                MessageFlash::ajouter("success","L'utilisateur a bien été modifié. L'email, un nouveau code de verification vous a été envoyé : <a href='https://yopmail.com'>Consultez la boite mail</a>");
            } else {
                $utilisateurRepository->mettreAJour($ancienUtilisateur);
                MessageFlash::ajouter("warning", "L'email de confirmation n'as pas pu être envoyée, veuillez réessayer plus tard.");
            }
        }
        ControleurGenerique::rediriger();
    }

    public static function connecter() : void {
        $utilisateurRepository = new UtilisateurRepository();
        if (!isset($_REQUEST["unique"]) or !isset($_REQUEST["password"]) or ConnexionUtilisateur::estConnecte()){
            ControleurGenerique::accesNonAutorise("Q");
            return;
        }

        if (preg_match("/^\+\d{11}$/", $_REQUEST["unique"])){
            $utilisateur = $utilisateurRepository->recupererParUnique($_REQUEST["unique"],3);
        }else if (preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $_REQUEST["unique"])){
            $utilisateur = $utilisateurRepository->recupererParUnique($_REQUEST["unique"],2);
        } else {
            $utilisateur = $utilisateurRepository->recupererParUnique($_REQUEST["unique"],1);
        }

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
                ControleurPanier::convertir($utilisateur->getIdUtilisateur());
                ControleurGenerique::rediriger();
            } else {
                MessageFlash::ajouter("warning", "Mot de passe incorrect !");
                self::formulaireConnexion();
            }
        }
    }

    public static function deconnecter() : void {
        ConnexionUtilisateur::deconnecter();
        PanierTemporaire::vider();
        Redirections::supprimerPointControle();
        MessageFlash::ajouter("success", "Déonnexion effectuée");
        ControleurGenerique::rediriger();
    }

    public static function validerEmail() : void {
        if (!isset($_REQUEST["login"]) or !isset($_REQUEST["nonce"])){
            ControleurGenerique::accesNonAutorise("W");
            return;
        }
        if (VerificationEmail::traiterEmailValidation($_REQUEST["nonce"])){
            ConnexionUtilisateur::deconnecter();
            MessageFlash::ajouter("success", "Email confirmée, vérification de compte validée.");
            ControleurUtilisateur::formulaireConnexion();
        } else {
            MessageFlash::ajouter("warning", "Email non confirmée, lien de confirmation invalide !");
            ControleurGenerique::rediriger();
        }
    }
}
