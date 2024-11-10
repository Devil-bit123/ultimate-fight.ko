<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegistrosDeAtaque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_de_ataques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jugador_atacante_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jugador_defensor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sala_id')->constrained('salas')->onDelete('cascade');
            $table->integer('daño')->nullable();
            $table->enum('resultado', ['fallido', 'exitoso'])->default('fallido');
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
        Schema::dropIfExists('registros_de_ataques');
    }
}
