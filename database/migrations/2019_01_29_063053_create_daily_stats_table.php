<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date')
                ->unique()
                ->comment('Дата');
            $table->unsignedInteger('views')
                ->default(0)
                ->comment('Посмотрело за день');
            $table->unsignedInteger('frames')
                ->default(0)
                ->comment('Обработано кадров за день');
            $table->unsignedInteger('faces')
                ->default(0)
                ->comment('Обнаружено лиц за день');
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
        Schema::dropIfExists('daily_stats');
    }
}
