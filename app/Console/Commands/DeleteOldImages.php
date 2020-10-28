<?php

namespace App\Console\Commands;

use App\Models\RawFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteOldImages extends Command
{
    protected $signature = 'images:delete {--minutes=5}';

    protected $description = 'Delete old images in moved after X minutes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $minutes = (int)$this->option('minutes');

        $seconds = $minutes * 60;

        $images = RawFile::query()
            ->where('created_at', '<=', now()->subSeconds($seconds))
            ->get();

        $this->line(sprintf('Start deleting: %s, %s', now(), $images->count()));

        foreach ($images as $image) {
            $pathname = storage_path(sprintf('app/public/moved/%s', $image->name));

            File::delete($pathname);

            $this->info(sprintf('File was deleted: %s', $image->name));

            $image->delete();
        }
    }
}
