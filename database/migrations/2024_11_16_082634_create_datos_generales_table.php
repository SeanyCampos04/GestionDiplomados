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
        Schema::create('datos_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('departamento_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->string('correo_alternativo')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('extension')->nullable();
            $table->string('nivel_estudios')->nullable();
            $table->string('sexo')->nullable();
            $table->string('titulo')->nullable();
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
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
        Schema::dropIfExists('datos_generales');
    }
};
