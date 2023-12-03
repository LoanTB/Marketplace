<?php use App\Ecommerce\Lib\PreferenceControleur; ?>
<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire préférence :</legend>
        <p>
            <input type="radio" id="utilisateurId" name="controleur_defaut" value="utilisateur" <?php if(PreferenceControleur::existe()){if (PreferenceControleur::lire() == "utilisateur"){echo "checked";}}?>>
            <label for="utilisateurId">Utilisateur</label>
        </p>
        <input type='hidden' name='action' value='enregistrerPreference'>
        <input type='hidden' name='controleur' value='generique'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>