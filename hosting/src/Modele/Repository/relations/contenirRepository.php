<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\relations\contenir;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\ArticleRepository;

class contenirRepository extends AbstractRepository{
    private string $nomTable = "contenir";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_article",
        "id_wishlist",
        "jour"
    );

    public function recupererArticlesWishlist(string|int $id_wishlist): array {
        $sql = "SELECT *
                from Article
                WHERE id_article in (
                    SELECT id_article
                    FROM contenir
                    WHERE id_wishlist = :id_wishlist
                )";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            "id_wishlist" => $id_wishlist
        );
        $pdoStatement->execute($values);
        $AbstractDataObject = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $AbstractDataObject[] = (new ArticleRepository)->construireDepuisTableau($dataFormatTableau,true);
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : contenir {
        return new contenir($objetFormatTableau["id_article"],$objetFormatTableau["id_wishlist"],$objetFormatTableau["jour"],$raw);
    }
}