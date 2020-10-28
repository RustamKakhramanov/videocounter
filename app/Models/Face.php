<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Face extends Model
{
    protected $fillable = [
        'face_token',
        'image',
    ];
}
