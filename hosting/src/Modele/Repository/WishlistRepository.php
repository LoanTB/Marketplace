<?php
namespace App\Ecommerce\Modele\Repository;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\Wishlist;
use PDOException;

class WishlistRepository extends AbstractRepository{
    private string $nomTable = "Wishlist";

    private array $uniques = array(
        "id_wishlist"
    );

    private array $nomsColonnes = array(
        "id_wishlist",
        "nom"
    );

    public function ajouterPourUtilisateur(Wishlist $wishlist, int $id_utilisateur) : string {
        try {
            $sql = "INSERT INTO Wishlist (id_wishlist,nom) VALUES (:id_wishlist,:nom)";
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $pdoStatement->execute($wishlist->formatTableau(true));
            $id_wishlist = dataBase::getPdo()->lastInsertId();
            $sql = "INSERT INTO enregistrer (id_utilisateur,id_wishlist) VALUES (:id_utilisateur,:id_wishlist)";
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $pdoStatement->execute(array(
                "id_utilisateur" => $id_utilisateur,
                "id_wishlist" => $id_wishlist
            ));
            $pdoStatement->fetch();
        } catch (PDOException $e) {
            return $e->getCode();
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

    public function construireDepuisTableau(array $objetFormatTableau,bool $raw) : Wishlist {
        return new Wishlist($objetFormatTableau["id_wishlist"],$objetFormatTableau["nom"],$raw);
    }
}