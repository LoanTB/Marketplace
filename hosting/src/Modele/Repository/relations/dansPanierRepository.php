<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\AbstractRepository;

class dansPanierRepository extends AbstractRepository{
    private string $nomTable = "dansPanier";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_article",
        "id_utilisateur"
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : dansPanier {
        return new dansPanier($objetFormatTableau["id_article"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}