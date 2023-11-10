<?php
namespace App\Ecommerce\Modele\Repository;

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
        "id_utilisateur_vendeur"
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Article {
        return new Article($objetFormatTableau["id_article"],$objetFormatTableau["nom"],$objetFormatTableau["description"],$objetFormatTableau["prix"],$objetFormatTableau["quantite"],$objetFormatTableau["id_utilisateur_vendeur"],$raw);
    }
}