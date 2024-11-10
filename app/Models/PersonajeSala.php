<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonajeSala extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'personaje_sala';

    // Definición de atributos asignables en masa
    protected $fillable = [
        'sala_id',
        'personaje_id',
        'jugador_id',
        'vida_personaje',
        'miss_percent',
    ];

    // Definición de las relaciones

    /**
     * Relación con el modelo Sala.
     */
    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    /**
     * Relación con el modelo Personaje.
     */
    public function personaje()
    {
        return $this->belongsTo(Personaje::class);
    }

    /**
     * Relación con el modelo User (jugador).
     */
    public function jugador()
    {
        return $this->belongsTo(User::class);
    }



}
