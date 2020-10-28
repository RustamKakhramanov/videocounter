<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaceLog extends Model
{
    protected $fillable = [
        'type',
        'token',
        'result',
        'response',
        'data',
    ];
}
