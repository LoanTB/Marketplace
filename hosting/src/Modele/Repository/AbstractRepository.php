<?php
namespace App\Ecommerce\Modele\Repository;
use App\Ecommerce\Modele\DataObject\AbstractDataObject;
use App\Ecommerce\Lib\ConnexionBaseDeDonnee as dataBase;
use PDOException;

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
            $AbstractDataObject[] = $this->construireDepuisTableau($dataFormatTableau,true);
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
        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $values = array(
                $this->getUniques()[$uniqueIndex] => $uniqueValue,
            );
            $pdoStatement->execute($values);
            $dataFormatTableau = $pdoStatement->fetch();
            if (!$dataFormatTableau){
                return null;
            } else {
                return $this->construireDepuisTableau($dataFormatTableau,true);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function recupererParUniqueDansRequest(): ?AbstractDataObject{
        if (self::requestContainsUnique()){
            $uniqueValue = self::requestUniqueValue();
            $uniqueIndex = self::requestUniqueIndice();
        } else {
            return null;
        }
        $sql = /** @lang OracleSqlPlus */
            "SELECT * from {$this->getNomTable()} WHERE {$this->getUniques()[$uniqueIndex]} = :{$this->getUniques()[$uniqueIndex]}";
        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $values = array(
                $this->getUniques()[$uniqueIndex] => $uniqueValue,
            );
            $pdoStatement->execute($values);
            $dataFormatTableau = $pdoStatement->fetch();
            if (!$dataFormatTableau){
                return null;
            } else {
                return $this->construireDepuisTableau($dataFormatTableau,true);
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function supprimerParUnique(string $uniqueValue,int $uniqueIndex) : bool {
        $sql = /** @lang OracleSqlPlus */
            "DELETE FROM {$this->getNomTable()} WHERE {$this->getUniques()[$uniqueIndex]} = :{$this->getUniques()[$uniqueIndex]}";

        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $values = array(
                $this->getUniques()[$uniqueIndex] => $uniqueValue,
            );
            $pdoStatement->execute($values);
            $pdoStatement->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return true;
        }
        return false;
    }

    public function supprimerParUniqueDansRequest() : bool {
        if (self::requestContainsUnique()){
            $uniqueValue = self::requestUniqueValue();
            $uniqueIndex = self::requestUniqueIndice();
        } else {
            return true;
        }

        $sql = /** @lang OracleSqlPlus */
            "DELETE FROM {$this->getNomTable()} WHERE {$this->getUniques()[$uniqueIndex]} = :{$this->getUniques()[$uniqueIndex]}";

        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $values = array(
                $this->getUniques()[$uniqueIndex] => $uniqueValue,
            );
            $pdoStatement->execute($values);
            $pdoStatement->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return true;
        }
        return false;
    }

    public function ajouter(AbstractDataObject $object) : bool {
        $sql = /** @lang OracleSqlPlus */
            "INSERT INTO {$this->getNomTable()} (";
        foreach ($this->getNomsColonnes() as $nomColone){
            if ($object->formatTableau()[$nomColone] != null){
                $sql = $sql."{$nomColone},";
            }
        }
        $sql = substr($sql,0,-1).") VALUES (";
        foreach ($this->getNomsColonnes() as $nomColone){
            if ($object->formatTableau()[$nomColone] != null){
                $sql = $sql.":{$nomColone},";
            }
        }
        $sql = substr($sql,0,-1).")";
        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $pdoStatement->execute($object->formatTableau(false));
            $pdoStatement->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return true;
        }
        return false;
    }

    public function mettreAJour(AbstractDataObject $object): bool {
        $sql = "UPDATE {$this->getNomTable()} SET";
        foreach ($this->getNomsColonnes() as $nomColone){
            $sql = $sql." {$nomColone} = :{$nomColone}, ";
        }
        $sql = substr($sql,0,-2)." WHERE {$this->getUniques()[0]} = :{$this->getUniques()[0]}";
        try {
            $pdoStatement = dataBase::getPdo()->prepare($sql);
            $pdoStatement->execute($object->formatTableau());
            $pdoStatement->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return true;
        }
        return false;
    }

    protected abstract function construireDepuisTableau(array $objetFormatTableau,bool $raw) : AbstractDataObject;
}