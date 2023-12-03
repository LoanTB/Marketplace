<?php
namespace App\Ecommerce\Lib;

class ImgurUploader {
    private $clientId;
    private $apiUrl;

    public function __construct() {
        $this->clientId = '4eb5daa9993a516';
        $this->apiUrl = 'https://api.imgur.com/3/image';
    }

    public function uploadImage($file) {

        if (isset($file['image']) && $file['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($file['image']['tmp_name']);

            $headers = array(
                'Authorization: Client-ID ' . $this->clientId,
            );

            $postData = array(
                'image' => base64_encode($imageData),
            );

            $options = array(
                'http' => array(
                    'header'  => implode("\r\n", $headers),
                    'method'  => 'POST',
                    'content' => http_build_query($postData),
                ),
            );

            $context  = stream_context_create($options);
            $response = file_get_contents($this->apiUrl, false, $context);

            $jsonResponse = json_decode($response, true);

            if ($jsonResponse && isset($jsonResponse['data']['link'])) {
                return $jsonResponse['data']['link'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>