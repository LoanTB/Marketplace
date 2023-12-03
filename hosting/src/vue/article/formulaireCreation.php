<?php

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

$utilisateur = (new UtilisateurRepository)->recupererParUnique(ConnexionUtilisateur::getIdUtilisateurConnecte(), 0);
?>
<link rel="stylesheet" href="../ressources/css/ArticleDetail.css">
<link href="../ressources/css/Editor.css" rel="stylesheet">

<form method="<?php if(\App\Ecommerce\Configuration\ConfigurationSite::getDebug()){echo "GET";}else{echo "POST";}?>" action="controleurFrontal.php">
        <input type='hidden' name='action' value='creerDepuisFormulaire'>
        <input type='hidden' name='controleur' value='article'>
        <div id="mainClass">
            <label id="articleTitle">
                <input type="text" name="nom" placeholder="Titre de l'annonce" required>
            </label>

            <div id="picturesZone">
                <label class="imagePlaceholder" for="img1" id="img1label">
                    <div class="svg image-add-icon"></div>
                    <input type="file" id="img1" required>
                </label>
                <label class="imagePlaceholder" for="img2" id="img2label">
                    <div class="svg image-add-icon"></div>
                    <input type="file" id="img2">
                </label>
                <label class="imagePlaceholder" for="img3" id="img3label">
                    <div class="svg image-add-icon"></div>
                    <input type="file" id="img3">
                </label>
            </div>

            <label id="articleDescription">
                <textarea name="description" placeholder="Description de l'article"></textarea>
            </label>

            <div id="sidebarPrice" class="sidebarItem">
                <input type="number" value="" placeholder="Prix" id="price" name="prix" id="prix_article_id" required>
                <div id="devise">€</div>
            </div>
            <div id="sidebarAuthor" class="sidebarItem">
                <div id="quantitySelector">
                    <div>
                        <div class="svg catalog-icon-fill"></div>
                        <label id="qtyLegend" for="qty">Quantité</label>
                    </div>
                    <input type="number" name="quantite" value="1" id="qty" required>
                </div>
                <div class="CTAbuttons">
                    <div id="authorProps">
                        <img src="https://picsum.photos/200">
                        <div id="authorDesc">
                            <p>Poster en tant que</p>
                            <h3><?php echo htmlspecialchars($utilisateur->getNom()).' '.htmlspecialchars($utilisateur->getPrenom()); ?></h3>
                        </div>
                    </div>
                    <a href="controleurFrontal.php?action=afficherListe&controleur=article" class="animated-button">
                        <span>Annuler</span>
                        <span></span>
                    </a>
                    <input type="submit" value="Publier l'annonce" id="addToCart">
                </div>
                <!--
                <div class="annonceProps">
                    <p><strong>Mise en ligne :</strong> 24 octobre à 12h00</p>
                    <p><strong>Mise à jour :</strong> 17 novembre à 17h24</p>
                </div>-->
            </div>
        </div>
</form>

<script src="../ressources/js/uploadPreview.js"></script>
