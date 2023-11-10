<?php

namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

class ConnexionUtilisateur {
    private static string $cleConnexion = "_utilisateurConnecte";

    public static function connecter(int $idUtilisateur): void {
        Session::getInstance()->enregistrer(ConnexionUtilisateur::$cleConnexion,$idUtilisateur);
    }

    public static function estConnecte(): bool {
        return Session::getInstance()->contient(ConnexionUtilisateur::$cleConnexion);
    }

    public static function deconnecter(): void {
        Session::getInstance()->supprimer(ConnexionUtilisateur::$cleConnexion);
    }

    public static function getIdUtilisateurConnecte(): ?string {
        if (ConnexionUtilisateur::estConnecte()){
            return Session::getInstance()->lire(ConnexionUtilisateur::$cleConnexion);
        } else {
            return null;
        }
    }

    public static function estUtilisateur($id): bool {
        return (ConnexionUtilisateur::getIdUtilisateurConnecte() == $id);
    }

    public static function estAdministrateur() : bool {
        if (ConnexionUtilisateur::estConnecte()){
            return (new UtilisateurRepository())->recupererParUnique(ConnexionUtilisateur::getIdUtilisateurConnecte(),0)->getAdmin();
        } else {
            return false;
        }
    }


}

