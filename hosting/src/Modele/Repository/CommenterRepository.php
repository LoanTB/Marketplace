<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Modele\DataObject\relations\Commenter;
use App\Ecommerce\Modele\Repository\AbstractRepository;

class CommenterRepository extends AbstractRepository{
    private string $nomTable = "Commenter";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_utilisateur",
        "id_article",
        "titre",
        "texte",
        "note"
    );

    protected function getNomTable(): string {
        return $this->nomTable;
    }
    protected function getUniques(): array {
        return $this->uniques;
    }
    protected function getNomsColonnes(): array{
        return $this->nomsColonnes;
    }

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Commenter {
        return new Commenter($objetFormatTableau["id_utilisateur"],$objetFormatTableau["id_article"],$objetFormatTableau["titre"],$objetFormatTableau["texte"],$objetFormatTableau["note"],$raw);
    }
}