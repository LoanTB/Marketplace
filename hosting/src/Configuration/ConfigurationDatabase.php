<?php
namespace App\Ecommerce\Configuration;
class ConfigurationDatabase {
    static private array $databaseConfiguration = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'login',
        'port' => '3316',
        'login' => 'login',
        'password' => "XORpassword"^"key"
    );
    static public function getHostname() : string {
        return ConfigurationDatabase::$databaseConfiguration['hostname'];
    }
    static public function getDatabase() : string {
        return ConfigurationDatabase::$databaseConfiguration['database'];
    }
    static public function getPort() : string {
        return ConfigurationDatabase::$databaseConfiguration['port'];
    }
    static public function getLogin() : string {
        return ConfigurationDatabase::$databaseConfiguration['login'];
    }
    static public function getPassword() : string {
        return ConfigurationDatabase::$databaseConfiguration['password'];
    }
}