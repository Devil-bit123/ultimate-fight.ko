<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroDeAtaque extends Model
{
    use HasFactory;

    protected $fillable = [
        'jugador_atacante_id',
        'jugador_defensor_id',
        'sala_id',
        'daño',
        'resultado',
    ];

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function atacante()
    {
        return $this->belongsTo(User::class, 'jugador_atacante_id');
    }

    public function defensor()
    {
        return $this->belongsTo(User::class, 'jugador_defensor_id');
    }


}
