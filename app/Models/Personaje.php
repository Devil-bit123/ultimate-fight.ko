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

    static $rules =[
        'nombre' => 'required|string|max:255',
        'vida' => 'integer|min:0',
        'ataque' => 'integer|min:0',
        'defensa' => 'integer|min:0',
        'velocidad' => 'integer|min:0',
        'habilidades' => 'nullable|array'
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
