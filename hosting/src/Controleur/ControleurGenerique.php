<?php
namespace App\Ecommerce\Controleur;
use App\Ecommerce\Lib\MessageFlash;
use App\Ecommerce\Lib\PreferenceControleur;

class ControleurGenerique {
    protected static function afficherVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres);
        require __DIR__ . "/../vue/$cheminVue";
    }

    public static function afficherErreur(string $messageErreur = "") : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Erreur",
            "cheminVueBody" => "erreurGenerale.php",
            "messageErreur" => $messageErreur
        ]);
    }

    public static function formulairePreference() : void {
        self::afficherVue("vueGenerale.php",[
            "pagetitle" => "Formulaire des préférences",
            "cheminVueBody" => "formulairePreference.php"
        ]);
    }

    public static function enregistrerPreference() : void {
        PreferenceControleur::enregistrer($_REQUEST["controleur_defaut"]);
        MessageFlash::ajouter("success", "Péférences enregistrées");
        ("App\\Ecommerce\\Controleur\\Controleur".ucfirst(PreferenceControleur::lire()))::afficherListe();
    }

    public static function alerterAccesNonAutorise() : void {
        MessageFlash::ajouter("danger", "Tentative d'accès illégitime détectée. Cette action sera signalée.");
    }
}