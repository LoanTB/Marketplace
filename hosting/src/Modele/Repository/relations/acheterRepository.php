<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\relations\acheter;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\ImageRepository;

class acheterRepository extends AbstractRepository{
    private string $nomTable = "acheter";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_utilisateur",
        "id_article",
        "quantite",
        "prix",
        "jour"
    );

    public function recupererArticlesAchetesParUtilisateur(string|int $id_utilisateur): array {
        $sql = "SELECT *
                from Article
                WHERE id_article in (
                    SELECT id_article
                    FROM Achat
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : acheter {
        return new acheter($objetFormatTableau["id_utilisateur"],$objetFormatTableau["id_article"],$objetFormatTableau["quantite"],$objetFormatTableau["jour"],$raw);
    }
}