<?php

namespace App\Enums;

class UserRole extends \Konekt\Enum\Enum
{
    public const __default = self::MANAGER;
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';

    protected static $labels = [
        self::ADMIN => 'Администратор',
        self::MANAGER => 'Менеджер'
    ];
}