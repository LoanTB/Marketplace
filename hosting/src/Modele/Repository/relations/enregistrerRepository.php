<?php
namespace App\Ecommerce\Modele\Repository\relations;

use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use App\Ecommerce\Modele\DataObject\relations\dansPanier;
use App\Ecommerce\Modele\Repository\AbstractRepository;
use App\Ecommerce\Modele\Repository\WishlistRepository;

class enregistrerRepository extends AbstractRepository{
    private string $nomTable = "enregistrer";

    private array $uniques = array();

    private array $nomsColonnes = array(
        "id_utilisateur",
        "id_wishlist"
    );

    public function recupererWishlistsUtilisateur(string|int $id_utilisateur): array {
        $sql = "SELECT *
                from Wishlist
                WHERE id_wishlist in (
                    SELECT id_wishlist
                    FROM enregistrer
                    WHERE id_utilisateur = :id_utilisateur
                )";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            "id_utilisateur" => $id_utilisateur
        );
        $pdoStatement->execute($values);
        $AbstractDataObject = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $AbstractDataObject[] = (new WishlistRepository)->construireDepuisTableau($dataFormatTableau,true);
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
        return new dansPanier($objetFormatTableau["id_wishlist"],$objetFormatTableau["id_utilisateur"],$raw);
    }
}