<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sala extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'jugador1_id', 'jugador2_id', 'estado'];

    static $rules = [
        'uuid' => 'required|uuid',
        'jugador1_id' => 'required|uuid',
        'jugador2_id' => 'nullable|uuid',
        'estado' => 'required|string|in:abierta,cerrada'
    ];

    // Relación muchos a muchos con Personaje
    public function personajes()
    {
        return $this->belongsToMany(Personaje::class, 'personaje_sala', 'sala_id', 'personaje_id')
            ->withPivot('jugador_id') // Si deseas acceder a la relación del jugador
            ->withTimestamps();
    }

    // Relación muchos a muchos con User (jugadores)
    public function jugadores()
    {
        return $this->belongsToMany(User::class, 'personaje_sala', 'sala_id', 'jugador_id')
            ->withPivot('personaje_id') // Si deseas acceder al personaje relacionado
            ->withTimestamps();
    }


}
