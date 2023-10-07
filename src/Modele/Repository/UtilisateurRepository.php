<?php
namespace App\Ecommerce\Modele\Repository;
use App\Ecommerce\Modele\DataObject\Utilisateur;
class UtilisateurRepository extends AbstractRepository{
    private string $nomTable = "Utilisateur";
    private string $nomClePrimaire = "login";

    private array $nomsColonnes = array(
        "login",
        "email",
        "password",
        "nom",
        "prenom",
        "estAdmin",
        "emailAValider",
        "nonce"
    );

    public function getNomTable(): string {
        return $this->nomTable;
    }
    protected function getNomClePrimaire(): string {
        return $this->nomClePrimaire;
    }
    protected function getNomsColonnes(): array{
        return $this->nomsColonnes;
    }

    public function construireDepuisTableau(array $objetFormatTableau) : Utilisateur {
        return new Utilisateur($objetFormatTableau["login"],$objetFormatTableau["email"],$objetFormatTableau["password"],$objetFormatTableau["nom"],$objetFormatTableau["prenom"],$objetFormatTableau["estAdmin"],$objetFormatTableau["emailAValider"],$objetFormatTableau["nonce"]);
    }
}