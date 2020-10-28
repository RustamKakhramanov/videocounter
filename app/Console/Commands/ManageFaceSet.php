<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ManageFaceSet extends Command
{
    protected $signature = 'facepp:faceset {action=get} {--hide}';

    protected $description = 'Command description';

    protected $client;

    protected $apiKey;

    protected $apiSecret;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;

        $this->apiKey = config('app.faceplusplusp.key');

        $this->apiSecret = config('app.faceplusplusp.secret');
    }

    public function handle()
    {
        $action = $this->argument('action');

        $show = !$this->option('hide');

        switch ($action) {
            case 'create':
                $response = $this->create();
                break;
            case 'delete':
                $response = $this->delete();
                break;
            case 'clear':
                $response = $this->clear();
                break;
            case 'get':
            default:
                $response = $this->get();
                break;
        }

        if ($show) {
            dump(json_decode($response->getBody()->getContents()));
        }
    }

    private function clear()
    {
        $key = config('app.faceplusplusp.faceset');

        if (empty($key)) {
            $this->error('Empty FaceSet token key');
            exit;
        }

        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/faceset/removeface', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'faceset_token' => $key,
                'face_tokens' => 'RemoveAllFaceTokens',
            ],
        ]);

        return $response;
    }

    private function delete()
    {
        $key = config('app.faceplusplusp.faceset');

        if (empty($key)) {
            $this->error('Empty FaceSet token key');
            exit;
        }

        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/faceset/getdetail', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'faceset_token' => $key,
                'check_empty' => 0,
            ],
        ]);

        return $response;
    }

    private function get()
    {
        $key = config('app.faceplusplusp.faceset');

        if (empty($key)) {
            $this->error('Empty FaceSet token key');
            exit;
        }

        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/faceset/getdetail', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'faceset_token' => $key,
            ],
        ]);

        return $response;
    }

    private function create()
    {
        $response = $this->client->post('https://api-us.faceplusplus.com/facepp/v3/faceset/create', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'display_name' => 'Videocounter',
                'outer_id' => '1',
            ],
        ]);

        return $response;
    }
}
