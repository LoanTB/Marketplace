<?php
require_once __DIR__ . '/../src/Lib/Psr4AutoloaderClass.php';

use App\Ecommerce\Lib\Psr4AutoloaderClass;

$loader = new Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('App\Ecommerce', __DIR__ . '/../src/');

if (isset($_REQUEST['controleur'])){
    $controleur = $_REQUEST['controleur'];
} else {
    if (true){
        $controleur = "article";
    } else {
        $controleur = "generique";
    }
}

$nomDeClasseControleur = "Controleur".ucfirst($controleur);
$chemainClass = "App\\Ecommerce\\Controleur\\";
if (class_exists(($chemainClass.$nomDeClasseControleur))){
    if (isset($_REQUEST['action'])){
        if (in_array($_REQUEST["action"],get_class_methods($chemainClass.$nomDeClasseControleur))) {
            $action = $_REQUEST["action"];
            ($chemainClass.$nomDeClasseControleur)::$action();
        } else {
            ($chemainClass.$nomDeClasseControleur)::afficherErreur("Action inconnue");
        }
    } else {
        ($chemainClass.$nomDeClasseControleur)::afficherListe();
        //($chemainClass.$nomDeClasseControleur)::afficherErreur("Action indéfinie");
    }
} else {
    ($chemainClass."ControleurGenerique")::afficherErreur("Classe inconnue");
}