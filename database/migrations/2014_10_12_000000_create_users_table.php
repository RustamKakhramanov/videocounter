<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Имя пользователя');
            $table->string('role')
                ->default(UserRole::defaultValue())
                ->index()
                ->comment('Роль');
            $table->string('email')->unique()
                ->comment('Адрес электронной почты');
            $table->timestamp('email_verified_at')->nullable()
                ->comment('Дата подтверждения почты');
            $table->string('password')->comment('Пароль');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
