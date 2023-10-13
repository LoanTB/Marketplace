<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire enregistrement utilisateur :</legend>
        <p>
            <label for="login_utilisateur_id">Login</label> :
            <input type="text" value="" name="login" id="login_utilisateur_id" required/>
        </p>
        <p>
            <label for="email_utilisateur_id">Email</label> :
            <input type="email" value="" name="email" id="email_utilisateur_id" required>
        </p>
        <p>
            <label for="telephone_utilisateur_id">Téléphone</label> :
            <input type="tel" value="" name="telephone" id="telephone_utilisateur_id">
        </p>
        <p>
            <label for="password_utilisateur_id">Phrase de passe</label> :
            <input type="password" value="" name="password" id="password_utilisateur_id" required>
        </p>
        <p>
            <label for="passwordConfirmation_utilisateur_id">Confirmation phrase de passe</label> :
            <input type="password" value="" name="passwordConfirmation" id="passwordConfirmation_utilisateur_id" required>
        </p>
        <p>
            <label for="nom_utilisateur_id">Nom</label> :
            <input type="text" value="" name="nom" id="nom_utilisateur_id" required/>
        </p>
        <p>
            <label for="prenom_utilisateur_id">Prenom</label> :
            <input type="text" value="" name="prenom" id="prenom_utilisateur_id" required/>
        </p>
        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>