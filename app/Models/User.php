<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación muchos a muchos con Sala a través de Personaje
    public function salas()
    {
        return $this->belongsToMany(Sala::class, 'personaje_sala', 'jugador_id', 'sala_id')
                    ->withPivot('personaje_id') // Si deseas acceder al personaje relacionado
                    ->withTimestamps();
    }

    // Relación muchos a muchos con Personaje a través de Sala
    public function personajes()
    {
        return $this->belongsToMany(Personaje::class, 'personaje_sala', 'jugador_id', 'personaje_id')
                    ->withPivot('sala_id') // Si deseas acceder a la sala relacionada
                    ->withTimestamps();
    }

}
