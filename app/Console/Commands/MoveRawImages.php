<?php

namespace App\Console\Commands;

use App\Models\Camera;
use App\Models\RawFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MoveRawImages extends Command
{
    protected $signature = 'images:move {--test}';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $message = sprintf('Start moving: %s', now());

        $this->line($message);

        Log::info($message);

        $isTestMode = $this->option('test');

        do {
            $glob = storage_path('app/public/raw/*.jpg');

            $images = glob($glob);

            /** @var Camera $camera */
            $camera = Camera::query()->firstOrFail();

            foreach ($images as $image) {
                $camera->touch();

                $name = File::basename($image);

                $target = storage_path(sprintf('app/public/moved/%s', $name));

                File::move($image, $target);

                File::delete($image);

                RawFile::query()->create([
                    'name' => $name,
                ]);

                $message = sprintf('File was moved to %s', $target);

                $this->info($message);

                Log::debug($message);
            }
        } while (!$isTestMode);
    }
}
