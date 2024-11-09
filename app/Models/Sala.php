<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sala extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'jugador1_id',
        'jugador2_id',
        'estado'
    ];

    static $rules = [
        'uuid' => 'required|uuid',
        'jugador1_id' => 'required|uuid',
        'jugador2_id' => 'nullable|uuid',
        'estado' => 'required|string|in:abierta,cerrada'
    ];

    public function jugador1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jugador1_id');
    }

    public function jugador2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'jugador2_id');
    }

    public function jugador1Personaje(): BelongsTo
    {
        return $this->belongsTo(Personaje::class, 'jugador1_personaje_id');
    }

    public function jugador2Personaje(): BelongsTo
    {
        return $this->belongsTo(Personaje::class, 'jugador2_personaje_id');
    }
}
