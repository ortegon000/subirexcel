<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblcodigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblcodigos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('marca');
            $table->string('modelo');
            $table->string('imei');
            $table->string('codigo1');
            $table->string('codigo2')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblcodigos');
    }
}
