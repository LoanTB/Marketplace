<link href="../ressources/css/Authentication.css" rel="stylesheet">
<div id="bgFilter">
    <div id="loginForm">
        <form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
            <h1>Connexion à votre compte</h1>
            <fieldset>
                <legend>Tirez le meilleur parti de FutureMarket en vous connectant</legend>
                <div class="entries">
                    <input type="text" placeholder="Identifiant/Email/Telephone" name="unique" id="unique_id" required>
                    <input type="password" value="" placeholder="Mot de passe" name="password" id="password_id" required>
                    <input type="hidden" name="action" value="connecter">
                    <input type="hidden" name="controleur" value="utilisateur">
                </div>
                <div>
                    <input type="submit" value="Connexion">
                </div>
            </fieldset>
        </form>
        <div id="modalFooter">
            <p>Pas encore inscrit ?</p>
            <a href="controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireCreation">Créer un compte</a>
        </div>
    </div>
</div>
