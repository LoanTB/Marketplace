<?php

namespace App\Ecommerce\Modele\DataObject;

class Commenter extends AbstractDataObject{
    private int $id_utilisateur;
    private int $id_article;
    private string $titre;
    private string $texte;
    private float $note;
    private string $jourModification;
    private ?string $jour;

    public function __construct(int $id_utilisateur, int $id_article, string $titre, string $texte, float $note, string $jourModification, ?string $jour, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->id_article = $id_article;
            $this->titre = $titre;
            $this->texte = $texte;
            $this->note = $note;
            $this->jourModification = $jourModification;
            $this->jour = $jour;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setIdArticle($id_article);
            $this->setTitre($titre);
            $this->setTexte($texte);
            $this->setNote($note);
            $this->setJourModification($jourModification);
            $this->setJour($jour);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "id_article" => $this->getIdArticle(),
            "titre" => $this->getTitre(),
            "texte" => $this->getTexte(),
            "note" => $this->getNote(),
            "jourModification" => $this->getJourModification(),
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
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * @param string $texte
     */
    public function setTexte(string $texte): void
    {
        $this->texte = $texte;
    }

    /**
     * @return float
     */
    public function getNote(): float
    {
        return $this->note;
    }

    /**
     * @param float $note
     */
    public function setNote(float $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getJourModification(): string
    {
        return $this->jourModification;
    }

    /**
     * @param string $jourModification
     */
    public function setJourModification(string $jourModification): void
    {
        $this->jourModification = $jourModification;
    }

    /**
     * @return ?string
     */
    public function getJour(): ?string
    {
        return $this->jour;
    }

    /**
     * @param ?string $jour
     */
    public function setJour(?string $jour): void
    {
        $this->jour = $jour;
    }
}