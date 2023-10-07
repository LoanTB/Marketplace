<?php
use \App\Ecommerce\Lib\ConnexionUtilisateur;
/* @var $utilisateurs */
foreach ($utilisateurs as $utilisateur) {
    echo '<p> Utilisateur ' . htmlspecialchars($utilisateur->getprenom()) . ' ' . htmlspecialchars($utilisateur->getnom()) . ' de login <a href="controleurFrontal.php?controleur=utilisateur&action=afficherDetail&login=' . rawurlencode($utilisateur->getlogin()) . '">' . htmlspecialchars($utilisateur->getlogin()) . '<a>.';
    if (ConnexionUtilisateur::estUtilisateur($utilisateur->getlogin()) or ConnexionUtilisateur::estAdministrateur()) {
        echo ' (<a href="controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireMiseAJour&login=' . rawurlencode($utilisateur->getlogin()) . '">modifier<a>/<a href="controleurFrontal.php?controleur=utilisateur&action=supprimer&login=' . rawurlencode($utilisateur->getlogin()) . '">supprimer<a>)</p>';
    } else {
        echo '</p>';
    }
}
echo '<p><a href="controleurFrontal.php?controleur=utilisateur&action=afficherFormulaireCreation">Créer un utilisateur<a></p>';