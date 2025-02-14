<?php

use App\Ecommerce\Lib\ConnexionUtilisateur;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;

$article = (new ArticleRepository())->recupererParUnique($_REQUEST["id_article"], 0);
$utilisateur = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
?>
<link rel="stylesheet" href="../ressources/css/ArticleDetail.css">
<link href="../ressources/css/Editor.css" rel="stylesheet">

<form method="POST" action="controleurFrontal.php" enctype="multipart/form-data">
        <input type='hidden' name='action' value='mettreAJour'>
        <input type='hidden' name='controleur' value='article'>
        <input type='hidden' name='id_article' value='<?php echo htmlspecialchars($article->getIdArticle())?>'>
        <div id="mainClass">
            <label id="articleTitle">
                <input type="text" name="nom" value="<?php echo htmlspecialchars($article->getNom())?>" placeholder="Titre de l'annonce" required>
            </label>

            <div id="picturesZone"><?php
                $imagesArray = (new illustrerRepository())->recupererImagesArticle($article->getIdArticle());
                for ($i=0; $i < 3; $i++) {
                    echo '<label class="imagePlaceholder';
                    if (count($imagesArray) > $i) {
                        echo ' isImageHere" style="background-image: url(\''.$imagesArray[$i].'\')"';
                    } else {
                        echo '"';
                    }
                    echo ' for="img'.($i+1).'" id="img'.($i+1).'label">
                    <div class="svg image-add-icon"> </div>';
                    if (count($imagesArray) > $i) {
                        echo '<a href="controleurFrontal.php?action=supprimerImage&controleur=article&id_utilisateur='.htmlspecialchars(rawurlencode($utilisateur->getIdUtilisateur())).'&id_article='.htmlspecialchars(rawurlencode($article->getIdArticle())).'&image='.$i.'" class="deleteButton"><div class="svg delete-icon locked-on-img"></div></a>';
                    }
                    echo '<input type="file" accept="image/*" name="image'.$i.'" id="img'.($i+1).'">
                    </label>';
                } ?>
            </div>

            <label id="articleDescription">
                <textarea name="description" placeholder="Description de l'article"><?php echo htmlspecialchars($article->getDescription())?></textarea>
            </label>

            <div id="sidebarPrice" class="sidebarItem">
                <input type="number" step="0.01" value="<?php echo htmlspecialchars($article->getPrix())?>" placeholder="Prix" id="price" name="prix" id="prix_article_id" required>
                <div id="devise">€</div>
            </div>
            <div id="sidebarAuthor" class="sidebarItem">
                <div id="quantitySelector">
                    <div>
                        <div class="svg catalog-icon-fill"></div>
                        <label id="qtyLegend" for="qty">Quantité</label>
                    </div>
                    <input type="number" name="quantite" value="<?php echo htmlspecialchars($article->getQuantite())?>" id="qty" required>
                </div>
                <div class="CTAbuttons">
                    <div id="authorProps">
                        <img src="<?php if ($utilisateur->getUrlImage()==null) echo '../ressources/img/unknown.png'; else echo $utilisateur->getUrlImage(); ?>">
                        <div id="authorDesc">
                            <p>Poster en tant que</p>
                            <h3><?php echo htmlspecialchars($utilisateur->getPrenom()).' '.htmlspecialchars($utilisateur->getNom()); ?></h3>
                        </div>
                    </div>
                    <a href="controleurFrontal.php?action=afficherListe&controleur=article" class="animated-button critical">
                        <span>Annuler</span>
                        <span></span>
                    </a>
                    <input type="submit" value="Modifier l'annonce" id="addToCart">
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
