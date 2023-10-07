<?php
namespace App\Ecommerce\Modele\Repository;
use App\Ecommerce\Modele\DataObject\AbstractDataObject;
use App\Ecommerce\Modele\Repository\ConnexionBaseDeDonnee as BD;

abstract class AbstractRepository{
    protected abstract function getNomTable(): string;
    protected abstract function getNomClePrimaire(): string;
    protected abstract function getNomsColonnes(): array;

    /**
     * @return AbstractDataObject[]
     */
    public function recuperer(): array {

        $pdoStatement = BD::getPdo()->query(/** @lang OracleSqlPlus */ "SELECT * FROM {$this->getNomTable()}");
        $AbstractDataObject = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $AbstractDataObject[] = $this->construireDepuisTableau($dataFormatTableau);
        }
        return $AbstractDataObject;
    }

    /**
     * @param string $valeurClePrimaire
     * @return AbstractDataObject|null
     */
    public function recupererParClePrimaire(string $valeurClePrimaire): ?AbstractDataObject{
        $sql = /** @lang OracleSqlPlus */
            "SELECT * from {$this->getNomTable()} WHERE {$this->getNomClePrimaire()} = :{$this->getNomClePrimaire()}";
        $pdoStatement = BD::getPdo()->prepare($sql);
        $values = array(
            $this->getNomClePrimaire() => $valeurClePrimaire,
        );
        $pdoStatement->execute($values);
        $dataFormatTableau = $pdoStatement->fetch();
        if (!$dataFormatTableau){
            return null;
        } else {
            return $this->construireDepuisTableau($dataFormatTableau);
        }
    }

    public function supprimerParClePrimaire(string $valeurClePrimaire) : void {
        $sql = /** @lang OracleSqlPlus */
            "DELETE FROM {$this->getNomTable()} WHERE {$this->getNomClePrimaire()} = :{$this->getNomClePrimaire()}";
        $pdoStatement = BD::getPdo()->prepare($sql);
        $values = array(
            $this->getNomClePrimaire() => $valeurClePrimaire,
        );
        $pdoStatement->execute($values);
        $pdoStatement->fetch();
    }

    public function ajouter(AbstractDataObject $object) : void {
        $sql = /** @lang OracleSqlPlus */
            "INSERT INTO {$this->getNomTable()} (";
        foreach ($this->getNomsColonnes() as $nomColone){
            $sql = $sql."{$nomColone},";
        }
        $sql = substr($sql,0,-1).") VALUES (";
        foreach ($this->getNomsColonnes() as $nomColone){
            $sql = $sql.":{$nomColone},";
        }
        $sql = substr($sql,0,-1).")";
        $pdoStatement = BD::getPdo()->prepare($sql);
        $pdoStatement->execute($object->formatTableau());
        $pdoStatement->fetch();
    }

    public function mettreAJour(AbstractDataObject $object): void {
        $sql = "UPDATE {$this->getNomTable()} SET";
        foreach ($this->getNomsColonnes() as $nomColone){
            $sql = $sql." {$nomColone} = :{$nomColone}, ";
        }
        $sql = substr($sql,0,-2)." WHERE {$this->getNomClePrimaire()} = :{$this->getNomClePrimaire()}";
        $pdoStatement = BD::getPdo()->prepare($sql);
        $pdoStatement->execute($object->formatTableau());
        $pdoStatement->fetch();
    }

    protected abstract function construireDepuisTableau(array $objetFormatTableau) : AbstractDataObject;
}