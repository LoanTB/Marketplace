<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\DataObject\relations\acheter;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\ArticleRepository;
use App\Ecommerce\Modele\Repository\ImageRepository;
use DateTime;
use PDOException;

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

    public function recupererHistoriqueAchats(string|int $id_utilisateur): array {
        $achats = (new acheterRepository())->recupererParColonne($id_utilisateur,0);
        $historique = [];
        foreach ($achats as $achat){
            $historique[] = [$achat,(new ArticleRepository())->recupererParUnique($achat->getIdArticle(),0)];
        }
        return $historique;
    }

    public function acheterArticle(string|int $id_utilisateur,Article $article, int $quantite): string {
        try {
            dataBase::getPdo()->beginTransaction();
            $article->setQuantite($article->getQuantite()-1);
            (new ArticleRepository())->mettreAJour($article);
            (new acheterRepository())->ajouter(new acheter($id_utilisateur,$article->getIdArticle(),$quantite,$article->getPrix(),(new DateTime())->format('Y-m-d H:i:s')));
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

    protected function construireDepuisTableau(array $objetFormatTableau,bool $raw) : acheter {
        return new acheter($objetFormatTableau["id_utilisateur"],$objetFormatTableau["id_article"],$objetFormatTableau["quantite"],$objetFormatTableau["prix"],$objetFormatTableau["jour"],$raw);
    }
}