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
        Schema::create('calificacion_modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participante_id')->constrained()->onDelete('cascade');
            $table->foreignId('modulo_id')->constrained()->onDelete('cascade');
            $table->foreignId('diplomado_id')->constrained()->onDelete('cascade');
            $table->string('calificacion')->nullable();
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
        Schema::dropIfExists('calificacion_modulos');
    }
};
