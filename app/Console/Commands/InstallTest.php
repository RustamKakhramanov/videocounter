<?php

namespace App\Console\Commands;

use App\Models\Camera;
use Illuminate\Console\Command;

class InstallTest extends Command
{
    protected $signature = 'install:test {dataset} {--times=0}';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dataSet = $this->argument('dataset');

        $times = $this->option('times');

        $this->call('migrate:fresh');

        factory(Camera::class)->create();

        $commandCopy = sprintf('cp -r storage/test/%s/* storage/app/public/raw', $dataSet);

        $this->line(exec($commandCopy));

        $this->call('images:move', [
            '--test' => true,
        ]);

        $this->call('facepp:faceset', [
            'action' => 'clear',
            '--hide' => true,
        ]);

        $this->call('facepp:faceset', [
            'action' => 'get',
        ]);

        for ($i = 0; $i < $times; $i++) {
            $this->call('images:check', [
                '--test' => true,
                '--log' => true,
            ]);
        }

        $this->call('facepp:faceset', [
            'action' => 'get',
        ]);
    }
}
