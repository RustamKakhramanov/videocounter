<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawFile extends Model
{
    protected $casts = [
        'in_work' => 'boolean',
    ];

    protected $fillable = [
        'name',
    ];

    /**
     * @return boolean
     */
    public function inWork()
    {
        return $this->in_work;
    }
}
