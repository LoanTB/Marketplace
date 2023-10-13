<?php
namespace App\Ecommerce\Lib;

use App\Ecommerce\Configuration\ConfigurationSite;
use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

class VerificationEmail {
    public static function envoiEmailValidation(Utilisateur $utilisateur): bool {
        $loginURL = rawurlencode($utilisateur->getLogin());
        $nonceURL = rawurlencode($utilisateur->getNonceEmail());
        $absoluteURL = ConfigurationSite::getAbsoluteURL();
        $lienValidationEmail = "$absoluteURL?action=validerEmail&controleur=utilisateur&login=$loginURL&nonce=$nonceURL";
        $corpsEmail = "<a href=\"$lienValidationEmail\">Validation</a>";
        $subject = "[Ecommerce] - Confirmation email compte utilisateur";
        return mail($utilisateur->getEmail(),$subject,$corpsEmail);
    }

    public static function traiterEmailValidation($nonce): bool {
        $utilisateur = (new UtilisateurRepository())->recupererParUniqueDansRequest();
        if ($utilisateur == null){return false;}
        if ($nonce == $utilisateur->getNonceEmail()){
            $utilisateur->setNonceEmail("");
            (new UtilisateurRepository())->mettreAJour($utilisateur);
            return true;
        } else {
            return false;
        }
    }

    public static function aValideEmail(Utilisateur $utilisateur) : bool {
        return ($utilisateur->getNonce() == "");
    }
}

