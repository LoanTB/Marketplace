<?php
use App\Ecommerce\Modele\Repository\UtilisateurRepository;
/* @var $article */

$userEntity = (new UtilisateurRepository)->recupererParUnique($article->getIdUtilisateur(), 0);
echo '
<div id="mainClass">
    <link rel="stylesheet" href="../ressources/css/ArticleDetail.css">
    
    <h1 id="articleTitle">'.htmlspecialchars($article->getNom()).'</h1>

    <div id="picturesZone">
            <img src="https://picsum.photos/200">
            <img src="https://picsum.photos/200/300">
            <img src="https://picsum.photos/300">
    </div>

    <div id="articleDescription">
        <p>'.htmlspecialchars($article->getDescription()).'</p>
    </div>

    <div id="sidebarPrice" class="sidebarItem">
        <h2 id="price">
            '.htmlspecialchars($article->getPrix()).' €
        </h2>
    </div>
    <div id="sidebarAuthor" class="sidebarItem">
        <div id="authorProps">
            <img src="https://picsum.photos/30">
            <div id="authorDesc">
                <p>Posté par :</p>
                <h3>' . $userEntity->getPrenom() . ' ' . $userEntity->getNom() . '</h3>
            </div>
        </div>
        <div class="CTAbuttons">
            <a id="addToCart">Ajouter au panier</a>
            <button class="animated-button">
                <span>Ajouter aux favoris</span>
                <span></span>
            </button>
        </div>
        <div class="annonceProps">
            <p><strong>Mise en ligne :</strong> 24 octobre à 12h00</p>
            <p><strong>Mise à jour :</strong> 17 novembre à 17h24</p>
        </div>
    </div>
</div>
';