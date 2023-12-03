<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;
use App\Ecommerce\Modele\Repository\ArticleRepository;

class PanierTemporaire {
    private static string $cle = "_panierTemporaire";

    public static function ajouter(string $id_article) : void {
        $values = self::lire();
        $values[] = $id_article;
        Session::getInstance()->enregistrer(PanierTemporaire::$cle,$values);
    }

    public static function estDansPanier(string $id_article) : bool {
        $values = self::lire();
        foreach ($values as $value) {
            if ($value == $id_article){
                return true;
            }
        }
        return false;
    }

    public static function supprimer(string $id_article): void {
        $values = self::lire();
        $newValues = [];
        foreach ($values as $value){
            if ($id_article != $value) {
                $newValues[] = $value;
            }
        }
        //self::vider(); Marche bien sans
        Session::getInstance()->enregistrer(PanierTemporaire::$cle,$newValues);
    }

    public static function lire() : array {
        if (Session::getInstance()->contient(PanierTemporaire::$cle)){
            $id_articles = Session::getInstance()->lire(PanierTemporaire::$cle);
        } else {
            $id_articles = [];
        }
        return $id_articles;
    }

    public static function lireArticles() : array {
        if (Session::getInstance()->contient(PanierTemporaire::$cle)){
            $id_articles = Session::getInstance()->lire(PanierTemporaire::$cle);
        } else {
            $id_articles = [];
        }
        $articles = [];
        foreach ($id_articles as $id_article){
            $articles[] = (new ArticleRepository())->recupererParUnique($id_article,0);
        }
        return $articles;
    }

    public static function vider() : void {
        Session::getInstance()->supprimer(PanierTemporaire::$cle);
    }
}

