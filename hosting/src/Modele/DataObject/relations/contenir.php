<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class contenir extends AbstractDataObject{

    private int $id_article;
    private int $id_wishlist;

    public function __construct(int $id_article, int $id_wishlist, bool $raw = true){
        if ($raw) {
            $this->id_article = $id_article;
            $this->id_wishlist = $id_wishlist;
        } else {
            $this->setIdArticle($id_article);
            $this->setIdWishlist($id_wishlist);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_article" => $this->getIdArticle(),
            "id_wishlist" => $this->getIdWishlist()
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
}