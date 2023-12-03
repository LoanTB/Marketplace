<?php
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
?>
<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire modification utilisateur :</legend>
        <p>
            <label for="login_id">Login</label> :
            <input type="text" value="<?php echo htmlspecialchars($utilisateur->getLogin())?>" name="login" id="login_id" required readonly/>
        </p>
        <p>
            <label for="email_id">Email</label>
            <input type="email" value="<?php echo htmlspecialchars($utilisateur->getEmail())?>" name="email" id="email_id" required>
        </p>
        <p>
            <label for="oldPassword_id">Ancienne phrase de passe</label>
            <input type="password" value="" placeholder="" name="oldPassword" id="oldPassword_id" required>
        </p>
        <p>
            <label for="password_id">Nouvelle phrase de passe</label>
            <input type="password" value="" placeholder="" name="password" id="password_id">
        </p>
        <p>
            <label for="passwordConfirmation_id">Vérification de la nouvelle phrase de passe</label>
            <input type="password" value="" placeholder="" name="passwordConfirmation" id="passwordConfirmation_id">
        </p>
        <p>
            <label for="nom_id">Nom</label> :
            <input type="text" value="<?php echo htmlspecialchars($utilisateur->getNom())?>" name="nom" id="nom_id" required/>
        </p>
        <p>
            <label for="prenom_id">Prenom</label> :
            <input type="text" value="<?php echo htmlspecialchars($utilisateur->getPrenom())?>" name="prenom" id="prenom_id" required/>
        </p>
        <?php
        if (\App\Ecommerce\Lib\ConnexionUtilisateur::estAdministrateur()){
            echo '<p>';
            echo '    <label for="estAdmin_id">Administrateur</label>';
            if ($utilisateur->getEstAdmin()) {
                echo '    <input type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id" checked>';

            } else {
                echo '    <input type="checkbox" placeholder="" name="estAdmin" id="estAdmin_id">';
            }
            echo '</p>';
        }
        ?>
        <input type='hidden' name='action' value='mettreAJour'>
        <input type='hidden' name='controleur' value='utilisateur'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>