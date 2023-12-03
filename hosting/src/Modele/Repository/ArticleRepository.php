<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use PDOException;

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
        "dateCreation",
        "dateModification",
        "id_utilisateur"
    );

    public function recupererRecherche(string $recherche): array {
        $pdoStatement = dataBase::getPdo()->prepare("SELECT * FROM Article WHERE nom LIKE :recherche OR description LIKE :recherche");
        $pdoStatement->execute(array(
            "recherche" => "%".$recherche."%"
        ));
        $articles = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $articles[] = $this->construireDepuisTableau($dataFormatTableau,true);
        }
        return $articles;
    }

    public function ajouterArticleAvecIllustrations(Article $article, array $illustrations): string {
        try {
            dataBase::getPdo()->beginTransaction();
            (new ArticleRepository())->ajouter($article);
            $articleId = dataBase::getPdo()->lastInsertId();
            foreach ($illustrations as $illustration){
                (new illustrerRepository())->ajouter(new illustrer($articleId,$illustration->getUrlImage(),$illustration->getOrdre()));
            }
            dataBase::getPdo()->commit();
        } catch (PDOException $e) {
            dataBase::getPdo()->rollBack();
            return $e;
        }
        return "";
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
        return new Article($objetFormatTableau["id_article"],$objetFormatTableau["nom"],$objetFormatTableau["description"],$objetFormatTableau["prix"],$objetFormatTableau["quantite"],$objetFormatTableau["dateCreation"],$objetFormatTableau["dateModification"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}
