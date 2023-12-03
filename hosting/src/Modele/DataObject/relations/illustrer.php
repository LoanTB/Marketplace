<?php
namespace App\Ecommerce\Modele\DataObject\relations;

use App\Ecommerce\Modele\DataObject\AbstractDataObject;

class illustrer extends AbstractDataObject{

    private ?int $id_article;
    private int $url_image;
    private int $ordre;

    public function __construct(?int $id_article, int $url_image, int $ordre, bool $raw = true){
        if ($raw) {
            $this->id_article = $id_article;
            $this->url_image = $url_image;
            $this->ordre = $ordre;
        } else {
            $this->setIdArticle($id_article);
            $this->setUrlImage($url_image);
            $this->setOrdre($ordre);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_article" => $this->getIdArticle(),
            "url_image" => $this->getUrlImage(),
            "ordre" => $this->getOrdre()
        );
    }

    public function getIdArticle(): ?int {
        return $this->id_article;
    }

    public function setIdArticle(?int $id_article): void {
        $this->id_article = $id_article;
    }

    public function getUrlImage(): int
    {
        return $this->url_image;
    }

    public function setUrlImage(int $url_image): void
    {
        $this->url_image = $url_image;
    }

    public function getOrdre(): int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): void
    {
        $this->ordre = $ordre;
    }
}