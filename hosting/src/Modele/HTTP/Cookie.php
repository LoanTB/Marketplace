<?php
namespace App\Ecommerce\Modele\HTTP;

use App\Ecommerce\Configuration\ConfigurationSite;

class Cookie {
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void {
        if ($dureeExpiration == null){$dureeExpiration = ConfigurationSite::getExpirationCookieDefault();}
        setcookie($cle, serialize($valeur), time()+$dureeExpiration);
    }

    public static function lire(string $cle): mixed {
        return unserialize($_COOKIE[$cle]);
    }

    public static function contient($cle) : bool {
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer($cle) : void {
        if (self::contient($cle)){
            unset($_COOKIE[$cle]);
            setcookie($cle,"",1);
        }
    }
}
