<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire connexion utilisateur :</legend>
        <p>
            <label for="login_id">Login</label> :
            <input type="text" placeholder="pesco" name="login" id="login_id" required/>
        </p>
        <p>
            <label for="password_id">Phrase de passe</label> :
            <input type="password" value="" placeholder="" name="password" id="password_id" required>
        </p>
        <input type='hidden' name='action' value='connecter'>
        <input type='hidden' name='controleur' value='utilisateur'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form><?php
