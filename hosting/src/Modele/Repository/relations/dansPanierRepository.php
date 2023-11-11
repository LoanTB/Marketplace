<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\ArticleRepository;

class dansPanierRepository extends AbstractRepository{
    private string $nomTable = "dansPanier";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_article",
        "id_utilisateur"
    );

    public function recupererArticlesDePanierUtilisateur(string|int $id_utilisateur): array {
        $sql = "SELECT *
                from Article
                WHERE id_article in (
                    SELECT id_article
                    FROM dansPanier
                    WHERE id_utilisateur = :id_utilisateur
                )";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            "id_utilisateur" => $id_utilisateur
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : dansPanier {
        return new dansPanier($objetFormatTableau["id_article"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}