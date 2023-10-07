<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Modele\HTTP\Session;

class MessageFlash {
    // Les messages sont enregistrés en session associée à la clé suivante
    private static string $cleFlash = "_messagesFlash";

    // $type parmi "success", "info", "warning" ou "danger"
    public static function ajouter(string $type, string $message) : void {
        $values = self::lireTousMessages();
        $values[] = '<div class="alert alert-'.$type.'">'.$message.'</div>';
        Session::getInstance()->enregistrer(MessageFlash::$cleFlash,$values);
    }

    public static function contientMessage(string $type): bool {
        $values = self::lireTousMessages();
        foreach ($values as $value){
            if (strpos($type, $value)) {
                return true;
            }
        }
        return false;
    }

    // Attention : la lecture doit détruire le message
    public static function lireMessages(string $type): array {
        $values = self::lireTousMessages();
        $selection = [];
        foreach ($values as $value){
            if (strpos($type, $value)) {
                $selection[] = $value;
                unset($value);
            }
        }
        Session::getInstance()->enregistrer(MessageFlash::$cleFlash,$values);
        return $selection;
    }

    public static function lireTousMessages() : array {
        if (Session::getInstance()->contient(MessageFlash::$cleFlash)){
            $values = Session::getInstance()->lire(MessageFlash::$cleFlash);
        } else {
            $values = [];
        }
        Session::getInstance()->supprimer(MessageFlash::$cleFlash);
        return $values;
    }

}

