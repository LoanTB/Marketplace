<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Modele\DataObject\Commenter;

class commenterRepository extends AbstractRepository{
    private string $nomTable = "Commenter";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_utilisateur",
        "id_article",
        "titre",
        "texte",
        "note",
        "jourModification",
        "jour"
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
        return new Commenter($objetFormatTableau["id_utilisateur"],$objetFormatTableau["id_article"],$objetFormatTableau["titre"],$objetFormatTableau["texte"],$objetFormatTableau["note"],$objetFormatTableau["jourModification"],$objetFormatTableau["jour"],$raw);
    }
}