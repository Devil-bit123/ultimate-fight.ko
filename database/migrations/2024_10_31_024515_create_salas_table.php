<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('jugador1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jugador2_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('estado', ['abierta', 'bloqueada', 'en_uso', 'terminada'])->default('abierta');
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
        Schema::dropIfExists('salas');
    }
}
