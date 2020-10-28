<?php

namespace App\Services;


use GuzzleHttp\Client;

class FaceSearch
{
    protected $client;

    protected $apiKey;

    protected $apiSecret;

    public $apiFaceSetToken;

    protected $token;

    protected $faces;

    protected $response;

    public function __construct($token)
    {
        $this->client = new Client();

        $this->token = $token;

        $this->apiKey = config('app.faceplusplusp.key');

        $this->apiSecret = config('app.faceplusplusp.secret');

        $this->apiFaceSetToken = config('app.faceplusplusp.faceset');

        $this->faces = collect([]);

        $this->check();
    }

    public function first()
    {
        return $this->faces->first();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function isInFaceSet()
    {
        $isInFaceSet = $this->faces->isNotEmpty()
            && $this->faces->first()->confidence > config('app.faceplusplusp.confidence');

        return $isInFaceSet;
    }

    public function isNotInFaceSet()
    {
        return !$this->isInFaceSet();
    }

    public function isLookingOnScreen()
    {
        $d = $this->analiz();
        $test = abs($d->faces[0]->attributes->headpose->yaw_angle) < config('app.faceplusplusp.max_yaw_angle');
        //dump($test, abs($d->faces[0]->attributes->headpose->yaw_angle), config('app.faceplusplusp.max_yaw_angle'));
        return $test;
    }

    protected function check()
    {
        /** @var object $content */
        $this->send();

        $this->faces = collect($this->response->results ?? []);
    }

    protected function send()
    {
        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/search', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'face_token' => $this->token,
                'faceset_token' => $this->apiFaceSetToken,
            ],
            'http_errors' => false,
        ]);

        $this->response = json_decode($response->getBody()->getContents());
    }

    protected function analiz()
    {
        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/face/analyze', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'face_tokens' => $this->token,
                'return_attributes' => 'headpose',
            ],
            'http_errors' => false,
        ]);

        return json_decode($response->getBody()->getContents());
    }
}