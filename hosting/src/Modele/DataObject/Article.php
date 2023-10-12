<?php
namespace App\Ecommerce\Modele\DataObject;

class Article extends AbstractDataObject {
    private int $id_article;
    private string $nom;
    private string $description;
    private float $prix;
    private int $quantite;
    private int $id_client_vendeur;
    private int $id_admin_certificateur;

    public function __construct(int $id_article, string $nom, string $description, float $prix, int $quantite, int $id_client_vendeur, int $id_admin_certificateur, bool $raw = true){
        if ($raw) {
            $this->id_article = $id_article;
            $this->nom = $nom;
            $this->description = $description;
            $this->prix = $prix;
            $this->quantite = $quantite;
            $this->id_client_vendeur = $id_client_vendeur;
            $this->id_admin_certificateur = $id_admin_certificateur;
        } else {
            $this->setIdArticle($id_article);
            $this->setNom($nom);
            $this->setDescription($description);
            $this->setPrix($prix);
            $this->setQuantite($quantite);
            $this->setIdClientVendeur($id_client_vendeur);
            $this->setIdAdminCertificateur($id_admin_certificateur);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_article" => $this->getIdArticle(),
            "prix" => $this->getPrix(),
            "nom" => $this->getNom(),
            "description" => $this->getDescription(),
            "quantite" => $this->getQuantite(),
            "id_client_vendeur" => $this->getIdClientVendeur(),
            "id_admin_certificateur" => $this->getIdAdminCertificateur()
        );
    }

    public function getIdArticle(): int
    {
        return $this->id_article;
    }

    public function setIdArticle(int $id_article): void
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

    public function getIdClientVendeur(): int
    {
        return $this->id_client_vendeur;
    }

    public function setIdClientVendeur(int $id_client_vendeur): void
    {
        $this->id_client_vendeur = $id_client_vendeur;
    }

    public function getIdAdminCertificateur(): int
    {
        return $this->id_admin_certificateur;
    }

    public function setIdAdminCertificateur(int $id_admin_certificateur): void
    {
        $this->id_admin_certificateur = $id_admin_certificateur;
    }
}