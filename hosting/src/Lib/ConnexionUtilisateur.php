<?php

namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

class ConnexionUtilisateur {
    // L'utilisateur connecté sera enregistré en session associé à la clé suivante
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

    public static function getLoginUtilisateurConnecte(): ?string {
        if (ConnexionUtilisateur::estConnecte()){
            return Session::getInstance()->lire(ConnexionUtilisateur::$cleConnexion);
        } else {
            return null;
        }
    }

    public static function estUtilisateur($id): bool {
        return (ConnexionUtilisateur::getLoginUtilisateurConnecte() == $id);
    }

    public static function estAdministrateur() : bool {
        if (ConnexionUtilisateur::estConnecte()){
            return (new UtilisateurRepository())->recupererParClePrimaire(ConnexionUtilisateur::getLoginUtilisateurConnecte())->getEstAdmin();
        } else {
            return false;
        }
    }


}

