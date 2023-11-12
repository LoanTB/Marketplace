<?php
namespace App\Ecommerce\Modele\DataObject;
class Wishlist extends AbstractDataObject {
    private ?int $id_wishlist;
    private string $nom;

    public function __construct(?int $id_wishlist, string $nom, bool $raw = true){
        if ($raw) {
            $this->id_wishlist = $id_wishlist;
            $this->nom = $nom;
        } else {
            $this->setIdWishlist($id_wishlist);
            $this->setNom($nom);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_wishlist" => $this->getIdWishlist(),
            "nom" => $this->getNom(),
        );
    }

    public function getIdWishlist(): ?int
    {
        return $this->id_wishlist;
    }

    public function setIdWishlist(?int $id_wishlist): void
    {
        $this->id_wishlist = $id_wishlist;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
}