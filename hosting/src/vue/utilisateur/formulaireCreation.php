<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire enregistrement utilisateur :</legend>
        <p>
            <label for="login_id">Login</label> :
            <input type="text" placeholder="pesco" name="login" id="login_id" required/>
        </p>
        <p>
            <label for="email_id">Email</label>
            <input type="email" value="" placeholder="pesco@yopmail.com" name="email" id="email_id" required>
        </p>
        <p>
            <label for="password_id">Phrase de passe</label>
            <input type="password" value="" placeholder="" name="password" id="password_id" required>
        </p>
        <p>
            <label for="passwordConfirmation_id">Vérification de la phrase de passe</label>
            <input type="password" value="" placeholder="" name="passwordConfirmation" id="passwordConfirmation_id" required>
        </p>
        <p>
            <label for="nom_id">Nom</label> :
            <input type="text" placeholder="Escobar" name="nom" id="nom_id" required/>
        </p>
        <p>
            <label for="prenom_id">Prenom</label> :
            <input type="text" placeholder="Pablo" name="prenom" id="prenom_id" required/>
        </p>
        <?php
        if (\App\Ecommerce\Lib\ConnexionUtilisateur::estAdministrateur()){
            echo '<p>';
            echo '<label for="estAdmin_id">Administrateur</label>';
            echo '<input type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">';
            echo '</p>';
        }
        ?>
        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='utilisateur'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>