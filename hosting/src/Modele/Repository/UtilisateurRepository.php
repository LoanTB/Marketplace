<?php
namespace App\Ecommerce\Modele\Repository;
use App\Ecommerce\Modele\DataObject\Utilisateur;
class UtilisateurRepository extends AbstractRepository{
    private string $nomTable = "Utilisateur";

    private array $uniques = array(
        "id_compte",
        "login",
        "email",
        "telephone"
    );

    private array $nomsColonnes = array(
        "id_compte",
        "login",
        "email",
        "telephone",
        "password",
        "nom",
        "prenom",
        "nonce_email",
        "nonce_telephone",
        "admin",
        "id_image"
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

    protected function construireDepuisTableau(array $objetFormatTableau) : Utilisateur {
        return new Utilisateur($objetFormatTableau["id_compte"], $objetFormatTableau["login"], $objetFormatTableau["email"], $objetFormatTableau["telephone"], $objetFormatTableau["password"], $objetFormatTableau["nom"], $objetFormatTableau["prenom"], $objetFormatTableau["nonce_email"], $objetFormatTableau["nonce_telephone"], $objetFormatTableau["admin"], $objetFormatTableau["id_image"]);
    }
}