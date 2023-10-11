<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Configuration\ConfigurationSite;
use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

class VerificationEmail {
    public static function envoiEmailValidation(Utilisateur $utilisateur): bool {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonce());
        $absoluteURL = ConfigurationSite::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controleur=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";
        $subject = "[Ecommerce] - Confirmation email compte client";
        return mail($utilisateur->getEmailAValider(),$subject,$corpsEmail);
    }

    public static function traiterEmailValidation($login, $nonce): bool {
        $utilisateur = (new UtilisateurRepository())->recupererParClePrimaire($login);
        if ($utilisateur == null){return false;}
        if ($nonce == $utilisateur->getNonce()){
            $utilisateur->setEmail($utilisateur->getEmailAValider());
            $utilisateur->setNonce("");
            $utilisateur->setEmailAValider("");
            (new UtilisateurRepository())->mettreAJour($utilisateur);
            return true;
        } else {
            return false;
        }
    }

    public static function aValideEmail(Utilisateur $utilisateur) : bool {
        return ($utilisateur->getNonce() == "" and $utilisateur->getEmailAValider() == "");
    }
}

