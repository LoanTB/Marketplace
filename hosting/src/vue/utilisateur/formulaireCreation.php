<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire enregistrement utilisateur :</legend>
        <p>
            <label for="login_utilisateur_id">Login</label> :
            <input type="text" value="<?php if (isset($_REQUEST["login"])){echo $_REQUEST["login"];}?>" name="login" id="login_utilisateur_id" required/>
        </p>
        <p>
            <label for="email_utilisateur_id">Email</label> :
            <input type="email" value="<?php if (isset($_REQUEST["email"])){echo $_REQUEST["email"];}?>" name="email" id="email_utilisateur_id" required>
        </p>
        <p>
            <label for="telephone">Téléphone</label> :
            <select name="telephone_country" id="telephone_country_utilisateur_id">
                <option value="1">Canada / États-Unis (+1)</option>
                <option value="7">Russie / Kazakhstan (+7)</option>
                <option value="20">Égypte (+20)</option>
                <option value="27">Afrique du Sud (+27)</option>
                <option value="30">Grèce (+30)</option>
                <option value="31">Pays-Bas (+31)</option>
                <option value="32">Belgique (+32)</option>
                <option value="33">France (+33)</option>
                <option value="34">Espagne (+34)</option>
                <option value="39">Italie (+39)</option>
                <option value="40">Roumanie (+40)</option>
                <option value="41">Suisse (+41)</option>
                <option value="43">Autriche (+43)</option>
                <option value="44">Royaume-Uni (+44)</option>
                <option value="45">Danemark (+45)</option>
                <option value="46">Suède (+46)</option>
                <option value="47">Norvège (+47)</option>
                <option value="48">Pologne (+48)</option>
                <option value="49">Allemagne (+49)</option>
                <option value="51">Pérou (+51)</option>
                <option value="52">Mexique (+52)</option>
                <option value="53">Cuba (+53)</option>
                <option value="54">Argentine (+54)</option>
                <option value="55">Brésil (+55)</option>
                <option value="56">Chili (+56)</option>
                <option value="57">Colombie (+57)</option>
                <option value="58">Venezuela (+58)</option>
                <option value="60">Malaisie (+60)</option>
                <option value="61">Australie (+61)</option>
                <option value="62">Indonésie (+62)</option>
                <option value="63">Philippines (+63)</option>
                <option value="64">Nouvelle-Zélande (+64)</option>
                <option value="65">Singapour (+65)</option>
                <option value="66">Thaïlande (+66)</option>
                <option value="81">Japon (+81)</option>
                <option value="82">Corée du Sud (+82)</option>
                <option value="84">Vietnam (+84)</option>
                <option value="86">Chine (+86)</option>
                <option value="90">Turquie (+90)</option>
                <option value="91">Inde (+91)</option>
                <option value="92">Pakistan (+92)</option>
                <option value="93">Afghanistan (+93)</option>
                <option value="94">Sri Lanka (+94)</option>
                <option value="95">Myanmar / Birmanie (+95)</option>
                <option value="98">Iran (+98)</option>
            </select>
            <input type="text"
                   value="<?php if (isset($_REQUEST['telephone_number'])) {echo $_REQUEST['telephone_number'];} ?>"
                   placeholder="Numéro de téléphone"
                   name="telephone_number"
                   id="telephone_number_utilisateur_id"
                   pattern="\d{9}"
                   title="Le numéro de téléphone doit contenir 9 chiffres"
                   inputmode="numeric">
        </p>

        <p>
            <label for="password_utilisateur_id">Phrase de passe</label> :
            <input type="password" value="<?php if (isset($_REQUEST["password"])){echo $_REQUEST["password"];}?>" name="password" id="password_utilisateur_id" required>
        </p>
        <p>
            <label for="passwordConfirmation_utilisateur_id">Confirmation phrase de passe</label> :
            <input type="password" value="<?php if (isset($_REQUEST["passwordConfirmation"])){echo $_REQUEST["passwordConfirmation"];}?>" name="passwordConfirmation" id="passwordConfirmation_utilisateur_id" required>
        </p>
        <p>
            <label for="nom_utilisateur_id">Nom</label> :
            <input type="text" value="<?php if (isset($_REQUEST["nom"])){echo $_REQUEST["nom"];}?>" name="nom" id="nom_utilisateur_id" required/>
        </p>
        <p>
            <label for="prenom_utilisateur_id">Prenom</label> :
            <input type="text" value="<?php if (isset($_REQUEST["prenom"])){echo $_REQUEST["prenom"];}?>" name="prenom" id="prenom_utilisateur_id" required/>
        </p>
        <?php
        if (\App\Ecommerce\Lib\ConnexionUtilisateur::estAdministrateur()){
            echo '<p>';
            echo '<label for="admin_id">Administrateur</label>';
            echo '<input type="checkbox" placeholder="" name="admin" id="admin_id" value="true">';
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