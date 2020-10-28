<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Konekt\Enum\Eloquent\CastsEnums;

class User extends Authenticatable
{
    use Notifiable,
        CastsEnums;

    protected $fillable = [
        'name', 'email', 'role', 'password',
    ];

    protected $enums = [
        'role' => UserRole::class
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
