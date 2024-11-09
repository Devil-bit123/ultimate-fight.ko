<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    use HasFactory;

    /**
     * nombre => nombre del personaje
     * vida => vida del personaje
     * ataque => ataque base del personaje golpes, patadas, etc
     * defensa => defensa del personaje
     * velocidad => velocidad del personaje
     * habilidades => array de habilidades especiales del personaje
     */

    protected $fillable = [
        'nombre',
        'vida',
        'ataque',
        'defensa',
        'velocidad',
        'habilidades'
    ];

    static $rules = [
        'nombre' => 'required|string|max:255',
        'vida' => 'integer|min:130|max:150',
        'ataque' => 'integer|min:25|max:45',
        'defensa' => 'integer|min:20|max:50',
        'velocidad' => 'integer|min:70|max:100',
        'habilidades' => 'nullable|array',
        'habilidades.puño_derecho' => 'integer|min:20|max:50',
        'habilidades.puño_izquierdo' => 'integer|min:20|max:50',
        'habilidades.patada_derecha' => 'integer|min:20|max:50',
        'habilidades.patada_izquierda' => 'integer|min:20|max:50',
        'habilidades.ataque_especial' => 'integer|min:30|max:80',
    ];



    protected $casts = [
        'habilidades' => 'array',
    ];

    public function recibirDanio(int $danio)
    {
        $danioRecibido = max(0, $danio - $this->defensa);
        $this->vida = max(0, $this->vida - $danioRecibido);
        $this->save();
    }

    public function atacar(Personaje $objetivo)
    {
        $objetivo->recibirDanio($this->ataque);
    }

}
