<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Modele\DataObject\Image;

class ImageRepository extends AbstractRepository{
    private string $nomTable = "Image";

    private array $uniques = array(
        "url_image"
    );

    private array $nomsColonnes = array(
        "url_image"
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Image {
        return new Image($objetFormatTableau["url_image"],$raw);
    }
}