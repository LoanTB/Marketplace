<?php

use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

/** @var Utilisateur $utilisateur */

$indicateurCode = substr($utilisateur->getTelephone(), 0, 3);

?>

<link href="../ressources/css/UserDetails.css" rel="stylesheet">

<form method="POST" action="controleurFrontal.php" enctype="multipart/form-data">
<div id="userHead"><label class="pfPlaceholder<?php
    if ($utilisateur->getUrlImage() != "") {
        echo ' isImageHere" style="background-image: url('.htmlspecialchars($utilisateur->getUrlImage()).')" for="pfp" id="pfpLabel">
        <div class="svg image-add-icon"></div>
        <a href="controleurFrontal.php?action=supprimerPfp&controleur=utilisateur&id_utilisateur='.htmlspecialchars(rawurlencode($utilisateur->getIdUtilisateur())).'" class="deleteButton" class="deleteButton"><div class="svg delete-icon locked-on-img"></div></a>';
    } else {
        echo '" for="pfp" id="pfpLabel">
        <div class="svg image-add-icon"></div>';
    }
    ?><input type="file" name="image" id="pfp">
    </label>
    <div id="fullName"><?php echo htmlspecialchars($utilisateur->getNom())?> <?php echo htmlspecialchars($utilisateur->getPrenom())?></div>
    <div><?php echo htmlspecialchars($utilisateur->getLogin())?></div>
</div>
    <div class="entryFrame">
        <h3>Identification</h3>
        <div class="inline">
            <input type="text" value="<?php echo htmlspecialchars($utilisateur->getPrenom())?>" placeholder="Prénom" name="prenom" id="prenom_id" required/>
            <input type="text" value="<?php echo htmlspecialchars($utilisateur->getNom())?>" placeholder="Nom" name="nom" id="nom_id" required/>
        </div>
        <input type="text" value="<?php echo htmlspecialchars($utilisateur->getLogin())?>" placeholder="Identifiant" name="login" id="login_id" required/>
    </div>
    <div class="entryFrame">
        <h3>Communication</h3>
        <input type="email" value="<?php echo htmlspecialchars($utilisateur->getEmail())?>" placeholder="Adresse e-mail" name="email" id="email_id" required>
        <div class="inline">
            <select name="telephone_country" id="telephone_country_utilisateur_id">
                <option value="1" <?php if ($indicateurCode == "1") echo "selected"; ?>>Canada / États-Unis (+1)</option>
                <option value="7" <?php if ($indicateurCode == "7") echo "selected"; ?>>Russie / Kazakhstan (+7)</option>
                <option value="20" <?php if ($indicateurCode == "20") echo "selected"; ?>>Égypte (+20)</option>
                <option value="27" <?php if ($indicateurCode == "27") echo "selected"; ?>>Afrique du Sud (+27)</option>
                <option value="30" <?php if ($indicateurCode == "30") echo "selected"; ?>>Grèce (+30)</option>
                <option value="31" <?php if ($indicateurCode == "31") echo "selected"; ?>>Pays-Bas (+31)</option>
                <option value="32" <?php if ($indicateurCode == "32") echo "selected"; ?>>Belgique (+32)</option>
                <option value="33" <?php if ($indicateurCode == "33") echo "selected"; ?>>France (+33)</option>
                <option value="34" <?php if ($indicateurCode == "34") echo "selected"; ?>>Espagne (+34)</option>
                <option value="39" <?php if ($indicateurCode == "39") echo "selected"; ?>>Italie (+39)</option>
                <option value="40" <?php if ($indicateurCode == "40") echo "selected"; ?>>Roumanie (+40)</option>
                <option value="41" <?php if ($indicateurCode == "41") echo "selected"; ?>>Suisse (+41)</option>
                <option value="43" <?php if ($indicateurCode == "43") echo "selected"; ?>>Autriche (+43)</option>
                <option value="44" <?php if ($indicateurCode == "44") echo "selected"; ?>>Royaume-Uni (+44)</option>
                <option value="45" <?php if ($indicateurCode == "45") echo "selected"; ?>>Danemark (+45)</option>
                <option value="46" <?php if ($indicateurCode == "46") echo "selected"; ?>>Suède (+46)</option>
                <option value="47" <?php if ($indicateurCode == "47") echo "selected"; ?>>Norvège (+47)</option>
                <option value="48" <?php if ($indicateurCode == "48") echo "selected"; ?>>Pologne (+48)</option>
                <option value="49" <?php if ($indicateurCode == "49") echo "selected"; ?>>Allemagne (+49)</option>
                <option value="51" <?php if ($indicateurCode == "51") echo "selected"; ?>>Pérou (+51)</option>
                <option value="52" <?php if ($indicateurCode == "52") echo "selected"; ?>>Mexique (+52)</option>
                <option value="53" <?php if ($indicateurCode == "53") echo "selected"; ?>>Cuba (+53)</option>
                <option value="54" <?php if ($indicateurCode == "54") echo "selected"; ?>>Argentine (+54)</option>
                <option value="55" <?php if ($indicateurCode == "55") echo "selected"; ?>>Brésil (+55)</option>
                <option value="56" <?php if ($indicateurCode == "56") echo "selected"; ?>>Chili (+56)</option>
                <option value="57" <?php if ($indicateurCode == "57") echo "selected"; ?>>Colombie (+57)</option>
                <option value="58" <?php if ($indicateurCode == "58") echo "selected"; ?>>Venezuela (+58)</option>
                <option value="60" <?php if ($indicateurCode == "60") echo "selected"; ?>>Malaisie (+60)</option>
                <option value="61" <?php if ($indicateurCode == "61") echo "selected"; ?>>Australie (+61)</option>
                <option value="62" <?php if ($indicateurCode == "62") echo "selected"; ?>>Indonésie (+62)</option>
                <option value="63" <?php if ($indicateurCode == "63") echo "selected"; ?>>Philippines (+63)</option>
                <option value="64" <?php if ($indicateurCode == "64") echo "selected"; ?>>Nouvelle-Zélande (+64)</option>
                <option value="65" <?php if ($indicateurCode == "65") echo "selected"; ?>>Singapour (+65)</option>
                <option value="66" <?php if ($indicateurCode == "66") echo "selected"; ?>>Thaïlande (+66)</option>
                <option value="81" <?php if ($indicateurCode == "81") echo "selected"; ?>>Japon (+81)</option>
                <option value="82" <?php if ($indicateurCode == "82") echo "selected"; ?>>Corée du Sud (+82)</option>
                <option value="84" <?php if ($indicateurCode == "84") echo "selected"; ?>>Vietnam (+84)</option>
                <option value="86" <?php if ($indicateurCode == "86") echo "selected"; ?>>Chine (+86)</option>
                <option value="90" <?php if ($indicateurCode == "90") echo "selected"; ?>>Turquie (+90)</option>
                <option value="91" <?php if ($indicateurCode == "91") echo "selected"; ?>>Inde (+91)</option>
                <option value="92" <?php if ($indicateurCode == "92") echo "selected"; ?>>Pakistan (+92)</option>
                <option value="93" <?php if ($indicateurCode == "93") echo "selected"; ?>>Afghanistan (+93)</option>
                <option value="94" <?php if ($indicateurCode == "94") echo "selected"; ?>>Sri Lanka (+94)</option>
                <option value="95" <?php if ($indicateurCode == "95") echo "selected"; ?>>Myanmar / Birmanie (+95)</option>
                <option value="98" <?php if ($indicateurCode == "98") echo "selected"; ?>>Iran (+98)</option>
            </select>
            <input type="text"
                   value="<?php echo htmlspecialchars(substr($utilisateur->getTelephone(), 3)); ?>"
                   placeholder="Téléphone"
                   name="telephone_number"
                   id="telephone_number_utilisateur_id"
                   pattern="\d{9}"
                   title="Le numéro de téléphone doit contenir 9 chiffres"
                   inputmode="numeric">
        </div>
    </div>
    <div class="entryFrame">
        <h3>Sécurité</h3>
        <input type="password" placeholder="Ancien mot de passe (obligatoire)" value="" name="password" id="oldPassword_id" required>
        <input type="password" placeholder="Nouveau mot de passe" value="" name="newPassword" id="password_id">
        <input type="password" value="" placeholder="Confirmer le nouveau mot de passe" name="passwordConfirmation" id="passwordConfirmation_id">
        <?php
        if (\App\Ecommerce\Lib\ConnexionUtilisateur::estAdministrateur()){
            echo '<div id="adminCheckbox">';
            if ($utilisateur->getAdmin()) {
                echo '    <input type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id" checked>';
            } else {
                echo '    <input type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">';
            }
            echo '<label for="estAdmin_id">Administrateur</label></div>';
        }
        ?>
    </div>
    <input type='hidden' name='id_utilisateur' value='<?php echo htmlspecialchars($utilisateur->getIdUtilisateur()); ?>'>
    <input type='hidden' name='action' value='mettreAJour'>
    <input type='hidden' name='controleur' value='utilisateur'>
    <div id="submitButtons">
        <a class="leftAction warning" href="controleurFrontal.php?action=suppressionConfirmation&controleur=utilisateur">Supprimer le compte</a>
        <input type="submit" value="Enregistrer">
    </div>
</form>
<script src="../ressources/js/pfPreview.js"></script>
