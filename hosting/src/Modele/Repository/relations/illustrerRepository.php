<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\ImageRepository;

class illustrerRepository extends AbstractRepository{
    private string $nomTable = "illustrer";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_article",
        "url_image",
        "ordre"
    );

    public function recupererImagesArticle(string|int $id_article): array {
        $sql = "SELECT *
                from Image
                WHERE url_image in (
                    SELECT url_image
                    FROM illustrer
                    WHERE id_article = :id_article
                )";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            "id_article" => $id_article
        );
        $pdoStatement->execute($values);
        $AbstractDataObject = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $AbstractDataObject[] = (new ImageRepository())->construireDepuisTableau($dataFormatTableau,true);
        }
        return $AbstractDataObject;
    }

    protected function getNomTable(): string {
        return $this->nomTable;
    }
    protected function getUniques(): array {
        return $this->uniques;
    }
    protected function getNomsColonnes(): array{
        return $this->nomsColonnes;
    }

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : illustrer {
        return new illustrer($objetFormatTableau["id_article"],$objetFormatTableau["url_image"],$objetFormatTableau["ordre"],$raw);
    }
}