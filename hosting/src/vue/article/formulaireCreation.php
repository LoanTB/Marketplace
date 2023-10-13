<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
    <fieldset>
        <legend>Formulaire mise en ligne d'article :</legend>
        <p>
            <label for="nom_article_id">Nom</label>
            <input type="text" value="" name="nom" id="nom_article" required/>
        </p>
        <p>
            <label for="description_article_id">Description</label>
            <input type="text" value="" placeholder="pesco@yopmail.com" name="description" id="description_article_id" required>
        </p>
        <p>
            <label for="prix_article_id">Prix</label>
            <input type="number" value="" placeholder="" name="prix" id="prix_article_id" required>
        </p>
        <p>
            <label for="quantite_article_id">Quantitee</label>
            <input type="number" value="1" placeholder="" name="quantite" id="quantite_article_id" required>
        </p>
        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='article'>
        <p>
            <input type="submit" value="Envoyer" />
        </p>
    </fieldset>
</form>