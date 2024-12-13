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
        Schema::create('solicitud_instructores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diplomado_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructore_id')->constrained()->onDelete('cascade');
            $table->integer('estatus')->default('0');
            $table->string('carta_terminacion');
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
        Schema::dropIfExists('solicitud_instructores');
    }
};
