<?php
namespace App\Ecommerce\Modele\DataObject;
class Article extends AbstractDataObject {
    private ?int $id_article;
    private string $nom;
    private string $description;
    private float $prix;
    private int $quantite;
    private string $jourModification;
    private ?string $jour;
    private int $id_utilisateur;

    public function __construct(?int $id_article, string $nom, string $description, float $prix, int $quantite, string $jourModification, ?string $jour, int $id_utilisateur, bool $raw = true){
        if ($raw) {
            $this->id_article = $id_article;
            $this->nom = $nom;
            $this->description = $description;
            $this->prix = $prix;
            $this->quantite = $quantite;
            $this->jourModification = $jourModification;
            $this->jour = $jour;
            $this->id_utilisateur = $id_utilisateur;
        } else {
            $this->setIdArticle($id_article);
            $this->setNom($nom);
            $this->setDescription($description);
            $this->setPrix($prix);
            $this->setQuantite($quantite);
            $this->setJourModification($jourModification);
            $this->setJour($jour);
            $this->setIdUtilisateur($id_utilisateur);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_article" => $this->getIdArticle(),
            "nom" => $this->getNom(),
            "description" => $this->getDescription(),
            "prix" => $this->getPrix(),
            "quantite" => $this->getQuantite(),
            "jourModification" => $this->getJourModification(),
            "jour" => $this->getJour(),
            "id_utilisateur" => $this->getIdUtilisateur(),
        );
    }

    public function getIdArticle(): ?int
    {
        return $this->id_article;
    }

    public function setIdArticle(?int $id_article): void
    {
        $this->id_article = $id_article;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getJourModification(): string
    {
        return $this->jourModification;
    }

    public function setJourModification(string $jourModification): void
    {
        $this->jourModification = $jourModification;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(?string $jour): void
    {
        $this->jour = $jour;
    }

    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }
}