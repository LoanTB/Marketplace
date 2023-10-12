<?php
/* @var $utilisateur */
echo '<p> Client '.htmlspecialchars($utilisateur->getprenom()).' '.htmlspecialchars($utilisateur->getnom()).' de login ' . htmlspecialchars($utilisateur->getlogin()) . '<a></p>';
