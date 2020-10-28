<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    protected $fillable = [
        'date',
        'counter',
    ];

    protected $dates = [
        'date',
    ];
}
