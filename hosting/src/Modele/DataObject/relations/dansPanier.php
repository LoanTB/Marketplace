<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class dansPanier extends AbstractDataObject{

    private int $id_utilisateur;
    private int $id_article;
    private int $quantite;
    private string $jour;

    public function __construct(int $id_utilisateur, int $id_article, int $quantite, string $jour, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->id_article = $id_article;
            $this->quantite = $quantite;
            $this->jour = $jour;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setIdArticle($id_article);
            $this->setQuantite($quantite);
            $this->setJour($jour);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "id_article" => $this->getIdArticle(),
            "quantite" => $this->getQuantite(),
            "jour" => $this->getJour()
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

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getJour(): string
    {
        return $this->jour;
    }

    public function setJour(string $jour): void
    {
        $this->jour = $jour;
    }
}