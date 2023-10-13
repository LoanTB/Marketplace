<?php
/* @var $article */
echo '<p> Article '.htmlspecialchars($article->getNom()).' : '.htmlspecialchars($article->getDescription()).' coute '.htmlspecialchars($article->getPrix()).' avec comme identifiant ' . htmlspecialchars($article->getIdArticle()) . '</p>';
