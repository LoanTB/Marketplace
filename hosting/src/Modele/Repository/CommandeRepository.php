<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\Article;
use App\Ecommerce\Modele\DataObject\Commande;
use App\Ecommerce\Modele\DataObject\relations\illustrer;
use App\Ecommerce\Modele\Repository\relations\illustrerRepository;
use DateTime;
use PDOException;

class CommandeRepository extends AbstractRepository{
    private string $nomTable = "Commande";

    private array $uniques = array(
        "id_commande"
    );

    private array $nomsColonnes = array(
        "id_commande",
        "nom",
        "prix",
        "quantite",
        "jour",
        "id_article",
        "id_utilisateur"
    );

    public function acheterArticle(string|int $id_utilisateur,Article $article, int $quantite): string {
        try {
            dataBase::getPdo()->beginTransaction();
            $article->setQuantite($article->getQuantite()-1);
            (new ArticleRepository())->mettreAJour($article);
            (new CommandeRepository())->ajouter(new Commande(null,$article->getNom(),$article->getPrix(),$quantite,(new DateTime())->format('Y-m-d H:i:s'),$article->getIdArticle(),$id_utilisateur,false));
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

    public function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Commande {
        return new Commande($objetFormatTableau["id_commande"],$objetFormatTableau["nom"],$objetFormatTableau["prix"],$objetFormatTableau["quantite"],$objetFormatTableau["jour"],$objetFormatTableau["id_article"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}
