<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\DailyStat;
use App\Models\Face;

class DashboardController extends Controller
{
    public function index()
    {
        $date = now()->timezone('MSK');

        $counter = DailyStat::query()
            ->where('date', '=', $date->format('Y-m-d'))
            ->first();

        $faces = Face::query()
            ->orderByDesc('id')
            ->limit(9)
            ->get();

        $camera = Camera::query()->first();

        $data = [
            'date' => $date->format('d.m.Y'),
            'counter' => $counter,
            'faces' => $faces,
            'camera' => $camera,
        ];

        return view('welcome', $data);
    }
}
