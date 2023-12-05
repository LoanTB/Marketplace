<?php

namespace App\Ecommerce\Configuration;

class ConfigurationSite {

    static private bool $debug = false;
    static private array $siteConfiguration = array(
        'expirationSession' => 60*60*24, // Session expire obligatoirement après 24h
        'expirationCookieDefault' => 60*60*24, // Un cookie expire par défault 24h après sa création si non précisée
        'absoluteURL' => "http://webinfo.iutmontp.univ-montp2.fr/~tremouletl/web/controleurFrontal.php"
    );

    public static function getDebug(): bool {
        return self::$debug;
    }

    static public function getExpirationSession() : string {
        return ConfigurationSite::$siteConfiguration['expirationSession'];
    }

    static public function getExpirationCookieDefault() : string {
        return ConfigurationSite::$siteConfiguration['expirationCookieDefault'];
    }

    static public function getAbsoluteURL() : string {
        return ConfigurationSite::$siteConfiguration['absoluteURL'];
    }
}