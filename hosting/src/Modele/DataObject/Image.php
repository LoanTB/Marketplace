<?php

namespace App\Ecommerce\Modele\DataObject;

class Image extends AbstractDataObject{
    private string $url_image;

    public function __construct(string $url_image, bool $raw = true){
        if ($raw) {
            $this->url_image = $url_image;
        } else {
            $this->setUrlImage($url_image);
        }
    }

    public function formatTableau(): array{
        return array(
            "url_image" => $this->getUrlImage()
        );
    }

    public function getUrlImage(): string
    {
        return $this->url_image;
    }

    public function setUrlImage(string $url_image): void
    {
        $this->url_image = $url_image;
    }
}