<?php
namespace App\Ecommerce\Modele\DataObject;
class Commande extends AbstractDataObject
{
    private ?int $id_commande;
    private string $nom;
    private float $prix;
    private int $quantite;
    private string $jour;
    private int $id_article;
    private int $id_utilisateur;

    public function __construct(?int $id_commande, string $nom, float $prix, int $quantite, string $jour, int $id_article, int $id_utilisateur, bool $raw = true)
    {
        if ($raw) {
            $this->id_commande = $id_commande;
            $this->nom = $nom;
            $this->prix = $prix;
            $this->quantite = $quantite;
            $this->jour = $jour;
            $this->id_article = $id_article;
            $this->id_utilisateur = $id_utilisateur;
        } else {
            $this->setIdCommande($id_commande);
            $this->setNom($nom);
            $this->setPrix($prix);
            $this->setQuantite($quantite);
            $this->setJour($jour);
            $this->setIdArticle($id_article);
            $this->setIdUtilisateur($id_utilisateur);
        }
    }

    public function formatTableau(): array
    {
        return array(
            "id_commande" => $this->getIdCommande(),
            "nom" => $this->getNom(),
            "prix" => $this->getPrix(),
            "quantite" => $this->getQuantite(),
            "jour" => $this->getJour(),
            "id_article" => $this->getIdArticle(),
            "id_utilisateur" => $this->getIdUtilisateur(),
        );
    }

    /**
     * @return int|null
     */
    public function getIdCommande(): ?int
    {
        return $this->id_commande;
    }

    /**
     * @param int|null $id_commande
     */
    public function setIdCommande(?int $id_commande): void
    {
        $this->id_commande = $id_commande;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return float
     */
    public function getPrix(): float
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
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
}