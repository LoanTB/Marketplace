<?php
namespace App\Ecommerce\Modele\HTTP;

use App\Ecommerce\Configuration\ConfigurationSite;
use Exception;

class Session {
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    private function verifierDerniereActivite() : void {
        if (isset($_SESSION['derniereActivite']) && (time() - $_SESSION['derniereActivite'] > (ConfigurationSite::getExpirationSession()))){
            session_unset();     // unset $_SESSION variable for the run-time
        }
        $_SESSION['derniereActivite'] = time(); // update last activity time stamp
    }

    public static function getInstance(): Session {
        if (is_null(Session::$instance))
            Session::$instance = new Session();
        Session::$instance->verifierDerniereActivite();
        return Session::$instance;
    }

    public function contient($nom): bool {
        return isset($_SESSION[$nom]);
    }

    public function enregistrer(string $nom, mixed $valeur): void {
        $_SESSION[$nom] =  serialize($valeur);
    }

    public function lire(string $nom): mixed {
        if (self::contient($nom)){
            return unserialize($_SESSION[$nom]);
        } else {
            return null;
        }
    }

    public function supprimer($nom): void {
        if (self::contient($nom)){
            unset($_SESSION[$nom]);
        }
    }

    public function detruire() : void {
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        // Il faudra reconstruire la session au prochain appel de getInstance()
        $instance = null;
    }
}

