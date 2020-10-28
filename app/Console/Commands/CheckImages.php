<?php

namespace App\Console\Commands;

use App\Models\Face;
use App\Models\FaceLog;
use App\Models\RawFile;
use App\Services\DailyStatService;
use App\Services\FaceCutter;
use App\Services\FaceDetect;
use App\Services\FaceSearch;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CheckImages extends Command
{
    protected $signature = 'images:check {--test} {--log}';

    protected $description = 'Send image to API';

    protected $client;

    protected $apiKey;

    protected $apiSecret;

    protected $apiFaceSetToken;

    protected $logOutput;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;

        $this->apiKey = config('app.faceplusplusp.key');

        $this->apiSecret = config('app.faceplusplusp.secret');

        $this->apiFaceSetToken = config('app.faceplusplusp.faceset');
    }

    public function handle()
    {
        $this->info2(sprintf('Start checking: %s', now()), true);

        $isTestMode = $this->option('test');

        $this->logOutput = $this->option('log');

        do {
            try {
                DB::beginTransaction();

                $file = $this->getFileForCheck();

                if ($file) {

                    $this->setLockForFile($file);

                    DB::commit();

                    (new DailyStatService())->incrementFrames();

                    $faceService = new FaceDetect($this->pathname($file));

                    foreach ($faceService->getFaces() as $face) {

                        $searchService = new FaceSearch($face->face_token);

                        FaceLog::query()->create([
                            'type' => 'search',
                            'token' => $face->face_token,
                            'result' => json_encode((array)$searchService->first()),
                            'response' => json_encode((array)$searchService->getResponse()),
                        ]);

                        (new DailyStatService())->incrementFaces();

                        $plus1 = $searchService->isNotInFaceSet();
                        $plus2 = $searchService->isLookingOnScreen();

                        //dump($plus1, $plus2);

                        if ($plus1 && $plus2) {
                            $faceImageService = (new FaceCutter($this->pathname($file), $face->face_rectangle));

                            Face::query()->create([
                                'face_token' => $face->face_token,
                                'image' => $faceImageService->cut()->getAsBase64(),
                            ]);

                            $this->addToFaceSet($face->face_token);

                            (new DailyStatService())->incrementViews();

                            $this->debug(sprintf('Face was added %s', $face->face_token));
                        } else {
                            $this->error2(sprintf('Face was searched %s', $face->face_token));
                        }
                    }

                    $this->clearFile($file);

                    $this->debug(sprintf('File was checked and deleted %s', $file->name));

                } else {
                    DB::rollBack();
                }
            } catch (\Exception $e) {
                Log::error($e);
                DB::rollBack();
            }

            sleep(1);

        } while (!$isTestMode);
    }

    protected function pathname($file)
    {
        return storage_path(sprintf('app/public/moved/%s', $file->name));
    }

    protected function clearFile($file)
    {
        DB::table('raw_files')
            ->where('id', '=', $file->id)
            ->delete();

        File::delete($this->pathname($file));
    }

    protected function getFileForCheck()
    {
        /** @var RawFile $file */
        $file = DB::table('raw_files')
            ->where('in_work', '=', 0)
            ->limit(1)
            ->orderBy('id')
            ->lockForUpdate()
            ->first();

        return $file;
    }

    protected function setLockForFile($file)
    {
        DB::table('raw_files')
            ->where('id', '=', $file->id)
            ->update([
                'in_work' => 1,
            ]);
    }

    protected function info2($message, $show = false)
    {
        if ($this->logOutput || $show) {
            $this->line($message);

            Log::debug($message);
        }
    }

    protected function error2($message, $show = false)
    {
        if ($this->logOutput || $show) {
            $this->error($message);

            Log::debug($message);
        }
    }

    protected function debug($message, $show = false)
    {
        if ($this->logOutput || $show) {
            $this->info($message);

            Log::debug($message);
        }
    }

    protected function addToFaceSet($faceToken)
    {
        $this->client->post('https://api-us.faceplusplus.com/facepp/v3/faceset/addface', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'api_secret' => $this->apiSecret,
                'faceset_token' => $this->apiFaceSetToken,
                'face_tokens' => $faceToken,
            ],
        ]);
    }
}
