<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    public function isOnline()
    {
        return now()->diffInMinutes($this->updated_at, true) < 2;
    }
}
