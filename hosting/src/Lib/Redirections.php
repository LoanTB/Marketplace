<?php

namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;

class Redirections {
    private static string $cleConnexion = "_redirections";

    public static function sauvgarder(string $url, array $parametres = []): void {
        self::supprimerPointControle();
        $data = [
            'url' => $url,
            'parametres' => $parametres
        ];
        Session::getInstance()->enregistrer(Redirections::$cleConnexion, $data);
    }

    public static function supprimerPointControle(): void{
        Session::getInstance()->supprimer(Redirections::$cleConnexion);
    }

    public static function existe(): bool {
        return Session::getInstance()->contient(Redirections::$cleConnexion);
    }

    // Récupérer les données de redirection et les supprimer de la session
    public static function obtenirRedirection(): ?array {
        if (Redirections::existe()){
            return Session::getInstance()->lire(Redirections::$cleConnexion);
        } else {
            return null;
        }
    }
}
