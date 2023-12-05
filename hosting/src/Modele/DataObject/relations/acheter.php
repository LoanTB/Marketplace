<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class acheter extends AbstractDataObject{

    private int $id_utilisateur;
    private int $id_article;
    private int $quantite;
    private int $prix;
    private string $jour;

    public function __construct(?int $id_utilisateur, int $id_article, int $quantite, float $prix, string $jour, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->id_article = $id_article;
            $this->quantite = $quantite;
            $this->prix = $prix;
            $this->jour = $jour;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setIdArticle($id_article);
            $this->setQuantite($quantite);
            $this->setPrix($prix);
            $this->setJour($jour);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "id_article" => $this->getIdArticle(),
            "quantite" => $this->getQuantite(),
            "prix" => $this->getPrix(),
            "jour" => $this->getJour()
        );
    }

    /**
     * @return int
     */
    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    /**
     * @param int $id_utilisateur
     */
    public function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @return int
     */
    public function getIdArticle(): int
    {
        return $this->id_article;
    }

    /**
     * @param int $id_article
     */
    public function setIdArticle(int $id_article): void
    {
        $this->id_article = $id_article;
    }

    /**
     * @return int
     */
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return int
     */
    public function getPrix(): int
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     */
    public function setPrix(int $prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return string
     */
    public function getJour(): string
    {
        return $this->jour;
    }

    /**
     * @param string $jour
     */
    public function setJour(string $jour): void
    {
        $this->jour = $jour;
    }
}