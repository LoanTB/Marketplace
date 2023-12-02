<?php
namespace App\Ecommerce\Controleur;

use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\PreferenceControleur;
use App\Ecommerce\Lib\Redirections;

class ControleurGenerique {

    protected static function afficherVue(string $cheminVue, array $parametres = []){
        extract($parametres);
        require __DIR__ . "/../vue/$cheminVue";
    }
    protected static function afficherNouvelleVue(string $cheminVue, array $parametres = []) : void {
        Redirections::supprimerPointControle();
        self::afficherVue($cheminVue,$parametres);
    }

    protected static function afficherVueAvecConservationPointControle(string $cheminVue, array $parametres = []) : void {
        self::afficherVue($cheminVue,$parametres);
    }

    protected static function afficherVueAvecPointControle(string $cheminVue, array $parametres = []) : void {
        Redirections::sauvgarder($cheminVue,$parametres);
        self::afficherVue($cheminVue,$parametres);
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "erreurGenerale.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function formulairePreference() : void {
        self::afficherNouvelleVue("vueGenerale.php",[
            "pagetitle" => "Formulaire des préférences",
            "cheminVueBody" => "formulairePreference.php"
        ]);
    }

    public static function enregistrerPreference() : void {
        PreferenceControleur::enregistrer($_REQUEST["controleur_defaut"]);
        MessageFlash::ajouter("success", "Péférences enregistrées");
        ("App\\Ecommerce\\Controleur\\Controleur".ucfirst(PreferenceControleur::lire()))::afficherListe();
    }

    public static function accesNonAutorise() : void {
        MessageFlash::ajouter("danger", "Tentative d'accès illégitime détectée. Cette action sera signalée.");
        self::redirigerVersMain();
    }

    public static function redirigerVersMain() : void {
        ControleurArticle::afficherListe();
    }

    public static function rediriger() : void {
        if (Redirections::existe()){
            $data = Redirections::obtenirRedirection();
            self::afficherVueAvecConservationPointControle($data["url"],$data["parametres"]);
        } else {
            self::redirigerVersMain();
        }
    }
}