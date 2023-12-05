<?php

use App\Ecommerce\Modele\DataObject\Utilisateur;
use App\Ecommerce\Modele\Repository\UtilisateurRepository;

/** @var Utilisateur $utilisateur */

?>

<link href="../ressources/css/UserDetails.css" rel="stylesheet">

<form method="POST" action="controleurFrontal.php" enctype="multipart/form-data">
<div id="userHead"><label class="pfPlaceholder<?php
    if ($utilisateur->getUrlImage() == "") {
        echo ' isImageHere" style="background-image: url(../ressources/img/unknown.png)" ';
    } else {
        echo ' isImageHere" style="background-image: url('.htmlspecialchars($utilisateur->getUrlImage()).')" ';
    }
    ?>>
    </label>
    <div id="fullName"><?php echo htmlspecialchars($utilisateur->getNom())?> <?php echo htmlspecialchars($utilisateur->getPrenom())?></div>
    <div><?php echo htmlspecialchars($utilisateur->getLogin())?></div>
</div>
    <h2 class="zonesDistinction warnTitle">Vous êtes sur le point de supprimer votre compte, ainsi que toutes les données qui lui sont associées</h2>
    <p>Cela inclut :</p>
    <ul>
        <li>Vos annonces</li>
        <li>Vos commandes effectuées</li>
        <li>Vos commentaires</li>
        <li>Votre liste de favoris</li>
    </ul>
    <div class="entryFrame zonesDistinction">
        <h3>Veuillez taper votre mot de passe pour confirmer la suppression</h3>
        <input type="password" placeholder="Mot de passe" value="" name="password" id="oldPassword_id" required>
    </div>
    <input type='hidden' name='id_utilisateur' value='<?php echo htmlspecialchars($utilisateur->getIdUtilisateur()); ?>'>
    <input type='hidden' name='action' value='supprimer'>
    <input type='hidden' name='controleur' value='utilisateur'>
    <div id="submitButtons">
        <a class="leftAction recommended" href="controleurFrontal.php?action=afficherFormulaireMiseAJour&controleur=utilisateur">Retour en arrière</a>
        <input type="submit" id="dangerButton" value="Supprimer le compte">
    </div>
</form>
