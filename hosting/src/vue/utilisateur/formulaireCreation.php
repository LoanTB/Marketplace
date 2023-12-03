<link href="../ressources/css/Authentication.css" rel="stylesheet">
<div id="bgFilter">
    <div id="loginForm">
        <form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
            <h1>Créer un compte</h1>
            <fieldset class="entries">
                <legend>Profitez de tous les avantages de FutureMarket grâce à un compte</legend>
                <div class="entryFrame">
                    <h3>Identification</h3>
                    <div class="inline">
                        <input type="text" value="<?php if (isset($_REQUEST["prenom"])){echo htmlspecialchars($_REQUEST["prenom"]);}?>" placeholder="Prénom" name="prenom" id="prenom_utilisateur_id" required/>
                        <input type="text" value="<?php if (isset($_REQUEST["nom"])){echo htmlspecialchars($_REQUEST["nom"]);}?>" placeholder="Nom" name="nom" id="nom_utilisateur_id" required/>
                    </div>
                    <input type="text" value="<?php if (isset($_REQUEST["login"])){echo htmlspecialchars($_REQUEST["login"]);}?>" placeholder="Identifiant" name="login" id="login_utilisateur_id" required/>
                </div>
                <div class="entryFrame">
                    <h3>Communication</h3>
                    <input type="email" value="<?php if (isset($_REQUEST["email"])){echo htmlspecialchars($_REQUEST["email"]);}?>" placeholder="Adresse e-mail" name="email" id="email_utilisateur_id" required>
                    <div class="inline">
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
                               value="<?php if (isset($_REQUEST['telephone_number'])) {echo htmlspecialchars($_REQUEST['telephone_number']);} ?>"
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
                    <input type="password" placeholder="Mot de passe" value="" name="password" id="password_utilisateur_id" required>
                    <input type="password" value="" placeholder="Confirmer le mot de passe" name="passwordConfirmation" id="passwordConfirmation_utilisateur_id" required>
                </div>

                <input type='hidden' name='action' value='creerDepuisFormulaire'>
                <input type='hidden' name='controleur' value='utilisateur'>
                <div>
                    <input type="submit" value="S'inscrire" />
                </div>
            </fieldset>
        </form>
        <div id="modalFooter">
            <p>Déjà inscrit ?</p>
            <a href="controleurFrontal.php?action=formulaireConnexion&controleur=utilisateur">Se connecter</a>
        </div>
    </div>
</div>