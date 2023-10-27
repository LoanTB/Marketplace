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
        $subject = "[Ecommerce] - Confirmation email compte utilisateur";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $corpsEmail = "
        <!DOCTYPE html>
        <html lang='fr'>
        <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Email de Validation</title>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
    
        .email-container {
            max-width: 600px;
            margin: auto;
        }
    
        .header {
            background: linear-gradient(to right, violet, blue);
            color: white;
            padding: 20px;
            text-align: center;
        }
    
        .content {
            padding: 20px;
            background-color: #f1f1f1;
        }
    
        .validation-code {
            background-color: white;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            border: 1px solid #ddd;
        }
    
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            color: white;
            background-color: blue;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        </style>
        </head>
        <body>
        
        <div class='email-container'>
            <div class='header'>
                <h1>Validation de votre Email</h1>
            </div>
        
            <div class='content'>
                <p>Bonjour,</p>
                <p>Merci de valider votre adresse email en cliquant sur le bouton ci-dessous. Votre code de validation est :</p>
        
                <a href='$lienValidationEmail' class='button'>Valider mon Email</a>
        
                <p>Merci,</p>
                <p>Ecommerce</p>
            </div>
        </div>
        
        </body>
        </html>";

        // Envoi de l'email
        return mail($utilisateur->getEmail(), $subject, $corpsEmail, $headers);
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
        return ($utilisateur->getNonceEmail() == "");
    }
}

