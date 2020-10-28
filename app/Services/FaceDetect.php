<?php

namespace App\Services;


use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class FaceDetect
{
    protected $client;

    protected $apiKey;

    protected $apiSecret;

    protected $apiFaceSetToken;

    /** @var  Collection */
    protected $faces;

    protected $pathname;

    public function __construct($pathname)
    {
        $this->client = new Client();

        $this->pathname = $pathname;

        $this->apiKey = config('app.faceplusplusp.key');

        $this->apiSecret = config('app.faceplusplusp.secret');

        $this->apiFaceSetToken = config('app.faceplusplusp.faceset');

        $this->check();
    }

    public function hasFaces()
    {
        return $this->faces->isNotEmpty();
    }

    public function getFaces()
    {
        return $this->faces;
    }

    protected function check()
    {
        /** @var object $content */
        $content = $this->send();

        $this->faces = collect($content->faces);
    }

    protected function getBase64Image()
    {
        $type = pathinfo($this->pathname, PATHINFO_EXTENSION);

        $data = file_get_contents($this->pathname);

        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    protected function send()
    {
        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'image_base64' => $this->getBase64Image(),
                'return_attributes' => 'headpose',
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }
}