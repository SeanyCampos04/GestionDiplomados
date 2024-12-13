<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diplomados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('objetivo');
            $table->string('tipo');
            $table->string('clase');
            $table->string('sede');
            $table->string('responsable');
            $table->string('correo_contacto');
            $table->date('inicio_oferta');
            $table->date('termino_oferta');
            $table->date('inicio_realizacion');
            $table->date('termino_realizacion');
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
        Schema::dropIfExists('diplomados');
    }
};
