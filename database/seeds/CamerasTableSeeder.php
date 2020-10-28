<?php

use App\Models\Camera;
use Illuminate\Database\Seeder;

class CamerasTableSeeder extends Seeder
{
    public function run()
    {
        factory(Camera::class)->create();
    }
}
