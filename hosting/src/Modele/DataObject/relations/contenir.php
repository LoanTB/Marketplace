<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class contenir extends AbstractDataObject{

    private int $id_article;
    private int $id_wishlist;
    private string $jour;

    public function __construct(int $id_article, int $id_wishlist, string $jour, bool $raw = true){
        if ($raw) {
            $this->id_article = $id_article;
            $this->id_wishlist = $id_wishlist;
            $this->jour = $jour;
        } else {
            $this->setIdArticle($id_article);
            $this->setIdWishlist($id_wishlist);
            $this->setJour($jour);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_article" => $this->getIdArticle(),
            "id_wishlist" => $this->getIdWishlist(),
            "jour" => $this->getJour()
        );
    }

    public function getIdArticle(): int {
        return $this->id_article;
    }

    public function setIdArticle(int $id_article): void {
        $this->id_article = $id_article;
    }

    public function getIdWishlist(): int {
        return $this->id_wishlist;
    }

    public function setIdWishlist(int $id_wishlist): void {
        $this->id_wishlist = $id_wishlist;
    }

    public function getJour(): string
    {
        return $this->jour;
    }

    public function setJour(string $jour): void
    {
        $this->jour = $jour;
    }
}