<?php
/* @var $utilisateur */
echo '<p> Article '.htmlspecialchars($utilisateur->getprenom()).' '.htmlspecialchars($utilisateur->getnom()).' avec comme identifiant ' . htmlspecialchars($utilisateur->getlogin()) . '<a></p>';
