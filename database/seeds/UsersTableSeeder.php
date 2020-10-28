<?php

use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\User::class, 2)->create([
            'role' => UserRole::MANAGER,
        ]);

        factory(\App\Models\User::class, 1)->create([
            'role' => UserRole::ADMIN,
        ]);
    }
}
