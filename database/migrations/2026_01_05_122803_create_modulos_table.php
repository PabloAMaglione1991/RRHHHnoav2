<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulosTable extends Migration
{
    public function up()
    {
        Schema::create('t_modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('t_modulos');
    }
}
