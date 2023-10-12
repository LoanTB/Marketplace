<?php
namespace App\Ecommerce\Configuration;
class ConfigurationDatabase {
    static private array $databaseConfiguration = array(
        'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
        'database' => 'tremouletl',
        'port' => '3316',
        'login' => 'tremouletl',
        'password' => "B\x05}\x01GWJMx\\&/\x0be\x18S<\x7f^z"^"9dTF,$:6CoVV]Mn\$X3)J"
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