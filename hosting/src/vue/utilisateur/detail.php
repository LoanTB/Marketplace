<?php
/* @var $utilisateur */
echo '<p> Utilisateur '.htmlspecialchars($utilisateur->getprenom()).' '.htmlspecialchars($utilisateur->getnom()).' de login ' . htmlspecialchars($utilisateur->getlogin()) . '<a></p>';
