<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\Article;

class ArticleRepository extends AbstractRepository{
    private string $nomTable = "Article";

    private array $uniques = array(
        "id_article"
    );

    private array $nomsColonnes = array(
        "id_article",
        "nom",
        "description",
        "prix",
        "quantite",
        "id_utilisateur"
    );

    public function recupererRecherche(string $recherche): array {
        $pdoStatement = dataBase::getPdo()->prepare("SELECT * FROM Article WHERE Title LIKE '%:recherche%' OR Description LIKE '%:recherche%'");
        $pdoStatement->execute(array(
            "recherche" => $recherche
        ));
        $articles = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $articles[] = $this->construireDepuisTableau($dataFormatTableau,true);
        }
        return $articles;
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

    public function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Article {
        return new Article($objetFormatTableau["id_article"],$objetFormatTableau["nom"],$objetFormatTableau["description"],$objetFormatTableau["prix"],$objetFormatTableau["quantite"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}