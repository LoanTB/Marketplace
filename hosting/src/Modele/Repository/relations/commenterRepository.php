<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Modele\DataObject\relations\commenter;
use App\Ecommerce\Modele\Repository\AbstractRepository;

class commenterRepository extends AbstractRepository{
    private string $nomTable = "commenter";

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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : commenter {
        return new commenter($objetFormatTableau["id_utilisateur"],$objetFormatTableau["id_article"],$objetFormatTableau["titre"],$objetFormatTableau["texte"],$objetFormatTableau["note"],$objetFormatTableau["jourModification"],$objetFormatTableau["jour"],$raw);
    }
}