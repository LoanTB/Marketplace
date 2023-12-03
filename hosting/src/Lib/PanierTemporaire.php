<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;

class PanierTemporaire {
    private static string $cle = "_panierTemporaire";

    public static function ajouter(string $id_article) : void {
        $values = self::lire();
        $values[] = $id_article;
        Session::getInstance()->enregistrer(PanierTemporaire::$cle,$values);
    }

    public static function supprimer(string $id_article): void {
        $values = self::lire();
        self::vider();
        foreach ($values as $value){
            if (strpos($id_article, $value)) {
                unset($value);
            }
        }
        Session::getInstance()->enregistrer(PanierTemporaire::$cle,$values);
    }

    public static function lire() : array {
        if (Session::getInstance()->contient(PanierTemporaire::$cle)){
            $values = Session::getInstance()->lire(PanierTemporaire::$cle);
        } else {
            $values = [];
        }
        return $values;
    }

    public static function vider() : void {
        Session::getInstance()->supprimer(PanierTemporaire::$cle);
    }
}

