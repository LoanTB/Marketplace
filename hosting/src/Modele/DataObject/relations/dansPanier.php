<?php

namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class dansPanier extends AbstractDataObject{

    private int $id_utilisateur;
    private int $id_article;

    public function __construct(int $id_utilisateur, int $id_article, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->id_article = $id_article;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setIdArticle($id_article);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "id_article" => $this->getIdArticle()
        );
    }

    public function getIdUtilisateur(): int {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getIdArticle(): int {
        return $this->id_article;
    }

    public function setIdArticle(int $id_article): void {
        $this->id_article = $id_article;
    }
}