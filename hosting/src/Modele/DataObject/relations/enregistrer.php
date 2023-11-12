<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class enregistrer extends AbstractDataObject{

    private int $id_utilisateur;
    private int $id_wishlist;

    public function __construct(int $id_utilisateur, int $id_wishlist, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->id_wishlist = $id_wishlist;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setIdWishlist($id_wishlist);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "id_wishlist" => $this->getIdWishlist()
        );
    }

    public function getIdUtilisateur(): int {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(int $id_utilisateur): void {
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getIdWishlist(): int {
        return $this->id_wishlist;
    }

    public function setIdWishlist(int $id_wishlist): void {
        $this->id_wishlist = $id_wishlist;
    }
}