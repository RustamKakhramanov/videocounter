<?php

namespace App\Console\Commands\Face;

use App\Services\FaceSearch as FaceSearchService;
use Illuminate\Console\Command;

class FaceSearch extends Command
{
    protected $signature = 'face:search {token}';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $token = $this->argument('token');

        $service = new FaceSearchService($token);

        dump($service->isInFaceSet());
    }
}
