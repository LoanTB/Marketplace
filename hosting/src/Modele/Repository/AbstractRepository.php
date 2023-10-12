<?php
namespace App\Ecommerce\Modele\Repository;
use App\Ecommerce\Modele\DataObject\AbstractDataObject;
use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;

abstract class AbstractRepository{
    protected abstract function getNomTable(): string;
    protected abstract function getUniques(): array;
    protected abstract function getNomsColonnes(): array;

    /**
     * @return int
     */
    public function requestUniqueIndice(): int {
        $uniques = $this->getUniques();
        for ($i=0;$i<count($uniques);$i++){
            if (isset($_REQUEST[$uniques[$i]])){
                return $i;
            }
        }
        return -1;
    }

    public function requestUniqueValue(): string {
        $uniques = $this->getUniques();
        foreach ($uniques as $unique){
            if (isset($_REQUEST[$unique])){
                return $unique;
            }
        }
        return "";
    }

    public function requestContainsUnique(): string {
        $uniques = $this->getUniques();
        foreach ($uniques as $unique){
            if (isset($_REQUEST[$unique])){
                return true;
            }
        }
        return false;
    }

    /**
     * @return AbstractDataObject[]
     */
    public function recuperer(): array {
        $pdoStatement = dataBase::getPdo()->query(/** @lang OracleSqlPlus */ "SELECT * FROM {$this->getNomTable()}");
        $AbstractDataObject = [];
        foreach ($pdoStatement as $dataFormatTableau) {
            $AbstractDataObject[] = $this->construireDepuisTableau($dataFormatTableau);
        }
        return $AbstractDataObject;
    }

    /**
     * @param string $uniqueValue,int $uniqueIndex
     * @return AbstractDataObject|null
     */
    public function recupererParUnique(string $uniqueValue,int $uniqueIndex): ?AbstractDataObject{
        $sql = /** @lang OracleSqlPlus */
            "SELECT * from {$this->getNomTable()} WHERE {$this->getUniques()[$uniqueIndex]} = :{$this->getUniques()[$uniqueIndex]}";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            $this->getUniques()[$uniqueIndex] => $uniqueValue,
        );
        $pdoStatement->execute($values);
        $dataFormatTableau = $pdoStatement->fetch();
        if (!$dataFormatTableau){
            return null;
        } else {
            return $this->construireDepuisTableau($dataFormatTableau);
        }
    }

    public function supprimerParUnique(string $uniqueValue,int $uniqueIndex) : void {
        $sql = /** @lang OracleSqlPlus */
            "DELETE FROM {$this->getNomTable()} WHERE {$this->getUniques()[$uniqueIndex]} = :{$this->getUniques()[$uniqueIndex]}";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $values = array(
            $this->getUniques()[$uniqueIndex] => $uniqueValue,
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
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $pdoStatement->execute($object->formatTableau());
        $pdoStatement->fetch();
    }

    public function mettreAJour(AbstractDataObject $object): void {
        $sql = "UPDATE {$this->getNomTable()} SET";
        foreach ($this->getNomsColonnes() as $nomColone){
            $sql = $sql." {$nomColone} = :{$nomColone}, ";
        }
        $sql = substr($sql,0,-2)." WHERE {$this->getUniques()[0]} = :{$this->getUniques()[0]}";
        $pdoStatement = dataBase::getPdo()->prepare($sql);
        $pdoStatement->execute($object->formatTableau());
        $pdoStatement->fetch();
    }

    protected abstract function construireDepuisTableau(array $objetFormatTableau) : AbstractDataObject;
}