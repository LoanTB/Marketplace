<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Configuration\ConfigurationDatabase;
use \PDO;

class ConnexionBaseDeDonnee {
    private static $instance = null;
    private PDO $pdo;
    public static function getPdo(): PDO {
        return ConnexionBaseDeDonnee::getInstance()->pdo;
    }
    private function __construct () {
        $this->pdo = new PDO("mysql:host=".ConfigurationDatabase::getHostname().";port=".ConfigurationDatabase::getPort().";dbname=".ConfigurationDatabase::getDatabase(),ConfigurationDatabase::getLogin(),ConfigurationDatabase::getPassword());
    }
    private static function getInstance() : ConnexionBaseDeDonnee {
        if (is_null(ConnexionBaseDeDonnee::$instance))
            ConnexionBaseDeDonnee::$instance = new ConnexionBaseDeDonnee();
        return ConnexionBaseDeDonnee::$instance;
    }
}