<?php

namespace App\Services;


use App\Models\DailyStat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyStatService
{
    protected $date;

    /**
     * DailyStatService constructor.
     * @param Carbon|string|null $date
     */
    public function __construct($date = null)
    {
        if ($date) {
            $this->date = ($date instanceof Carbon)
                ? $date->format('Y-m-d')
                : $date;
        } else {
            $this->date = now()->timezone('MSK')->format('Y-m-d');
        }

        DailyStat::query()->firstOrCreate([
            'date' => $this->date,
        ]);
    }

    public function incrementViews()
    {
        DB::table('daily_stats')
            ->where('date', '=', $this->date)
            ->increment('views');
    }

    public function incrementFrames()
    {
        DB::table('daily_stats')
            ->where('date', '=', $this->date)
            ->increment('frames');
    }

    public function incrementFaces()
    {
        DB::table('daily_stats')
            ->where('date', '=', $this->date)
            ->increment('faces');
    }
}