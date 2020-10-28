<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Имя файла');
            $table->boolean('in_work')
                ->index()->default(0)
                ->comment('Взят в обработку');
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
        Schema::dropIfExists('raw_files');
    }
}
