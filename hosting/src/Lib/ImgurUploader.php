<?php

class ImgurUploader {
    private $clientId;
    private $apiUrl;

    public function __construct($clientId) {
        $this->clientId = $clientId;
        $this->apiUrl = 'https://api.imgur.com/3/image';
    }
}
?>